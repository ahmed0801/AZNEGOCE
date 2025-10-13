<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Item;
use App\Models\Supplier;

class VoiceCommandController extends Controller
{
    public function handle(Request $request)
    {
        $command = Str::lower(trim($request->input('command')));

        // Expressions déclencheurs
        $triggers = ['commande achat', 'propose une commande', 'proposition d\'achat', 'réapprovisionnement'];

        // Si la commande contient un mot-clé déclencheur
        foreach ($triggers as $trigger) {
            if (Str::contains($command, $trigger)) {
                $suggestions = [];

                $stocks = Stock::with('item')->get();

                foreach ($stocks as $stock) {
                    if ($stock->quantity < $stock->min_quantity) {
                        $item = $stock->item;
                        $neededQty = $stock->max_quantity - $stock->quantity;

                        $supplier = $item->supplier;
                        $lastMovement = $item->stockMovements()->orderByDesc('created_at')->first();

                        $suggestions[] = [
                            'article' => $item->name?? 'Article inconnu',
                            'quantité_suggérée' => $neededQty,
                            'fournisseur' => $supplier->name?? $lastMovement->supplier_name?? 'Fournisseur standard',
                            'prix_unitaire' => $lastMovement->cost_price?? $item->cost_price?? 0
                        ];
}
}

                return response()->json([
                    'status' => '✅ Suggestions générées avec succès',
                    'propositions' => $suggestions
                ]);
}
}

        return response()->json(['status' => '❌ Commande non reconnue']);
}
}