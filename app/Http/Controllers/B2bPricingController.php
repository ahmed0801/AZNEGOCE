<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Services\B2bPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Simulateur de prix du portail B2B (onglet « Simulateur » des paramètres B2B). */
class B2bPricingController extends Controller
{
    private function guard()
    {
        if (Auth::user() && Auth::user()->role === 'vendeur') abort(403);
    }

    public function searchCustomers(Request $request)
    {
        $this->guard();
        $q = trim((string) $request->query('q'));
        if (mb_strlen($q) < 2) return response()->json([]);
        $rows = Customer::with('discountGroup')
            ->where(function ($w) use ($q) {
                $w->where('name', 'LIKE', "%{$q}%")->orWhere('code', 'LIKE', "%{$q}%");
            })
            ->orderBy('name')->limit(8)->get();
        return response()->json($rows->map(function ($c) {
            return ['id' => $c->id, 'code' => $c->code, 'name' => $c->name, 'type' => $c->type,
                    'group' => optional($c->discountGroup)->name];
        })->values());
    }

    public function searchItems(Request $request)
    {
        $this->guard();
        $q = trim((string) $request->query('q'));
        if (mb_strlen($q) < 2) return response()->json([]);
        $rows = Item::where(function ($w) use ($q) {
                $w->where('code', 'LIKE', "%{$q}%")->orWhere('name', 'LIKE', "%{$q}%");
            })
            ->orderBy('code')->limit(8)->get(['id', 'code', 'name', 'sale_price', 'prix_net']);
        return response()->json($rows->map(function ($i) {
            return ['id' => $i->id, 'code' => $i->code, 'name' => $i->name, 'sale_price' => (float) $i->sale_price, 'prix_net' => (bool) $i->prix_net];
        })->values());
    }

    public function simulate(Request $request)
    {
        $this->guard();
        $v = $request->validate(['customer_id' => 'required|integer', 'item_id' => 'required|integer']);
        $customer = Customer::with(['discountGroup', 'tvaGroup'])->findOrFail($v['customer_id']);
        $item     = Item::with('discountGroup')->findOrFail($v['item_id']);

        return response()->json(['ok' => true] + (new B2bPricingService())->price($item, $customer));
    }
}
