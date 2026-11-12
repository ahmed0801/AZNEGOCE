<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use App\Services\CompanionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SalesCompanionController extends Controller
{
    /** GET /sales/items/companions?codes[]=KH123&codes[]=... */
    public function index(Request $request, CompanionService $service)
    {
        $codes = array_slice((array) $request->input('codes', []), 0, 40);
        $sorted = $codes; sort($sorted);
        // Cache 10 min sur le calcul statistique (le stock est relu à chaque fois, hors cache)
        $raw = Cache::remember('companions:' . md5(implode('|', $sorted)), 600, function () use ($service, $codes) {
            return $service->suggest($codes, 14);
        });
        if (!$raw) return response()->json([]);

        $byCode = collect($raw)->keyBy('code');
        $items = Item::with(['brand', 'supplier', 'stocks', 'discountGroup'])
            ->where('is_active', 1)->whereIn('code', array_keys($byCode->all()))->get();

        $rows = $items->map(function ($item) use ($byCode) {
            $c = $byCode[$item->code];
            $stock = (float) $item->getStockQuantityAttribute();
            return [
                // même structure que /sales/items/search : le front peut appeler addItemLine() tel quel
                'code' => $item->code, 'name' => $item->name, 'location' => $item->location, 'description' => $item->description,
                'brand' => $item->brand->name ?? null, 'supplier' => $item->supplier->name ?? null, 'supplier_id' => $item->supplier->id ?? null,
                'stock_quantity' => $stock, 'cost_price' => $item->cost_price, 'remise_achat' => $item->remise_achat, 'sale_price' => $item->sale_price,
                'discount_rate' => $item->discountGroup->discount_rate ?? 0,
                'discount_rate_jobber' => $item->discountGroup->discount_rate_jobber ?? 0,
                'discount_rate_professionnel' => $item->discountGroup->discount_rate_professionnel ?? 0,
                'category_id' => $item->category_id, 'subcategory_id' => $item->subcategory_id,
                'Poids' => $item->Poids, 'Hauteur' => $item->Hauteur, 'Longueur' => $item->Longueur, 'Largeur' => $item->Largeur,
                'Ref_TecDoc' => $item->Ref_TecDoc, 'Code_pays' => $item->Code_pays, 'Code_douane' => $item->Code_douane,
                // explication affichée au vendeur
                'companion' => [
                    'percent' => (int) round($c['confidence'] * 100), 'times' => $c['support'],
                    'anchor' => $c['anchor'], 'anchor_sales' => $c['anchor_sales'],
                ],
            ];
        })->sort(function ($a, $b) {
            // par fréquence d'association ; le stock n'influence pas le classement (stocks souvent mal tenus)
            if ($a['companion']['percent'] !== $b['companion']['percent']) return $b['companion']['percent'] <=> $a['companion']['percent'];
            return $b['companion']['times'] <=> $a['companion']['times'];
        })->values()->take(8);

        return response()->json($rows->all());
    }
}
