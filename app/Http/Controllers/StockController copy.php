<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{

   public function updateOrCreate(Request $request)
{
    $validated = $request->validate([
        'item_id' => 'required|exists:items,id',
        'store_id' => 'required|exists:stores,id',
        'quantity' => 'required|numeric',
        'movement_type' => 'required|in:achat,vente,ajustement,transfert,inventaire',
        'reason' => 'nullable|string',
        'reference' => 'required',
    ]);

    // On récupère le stock actuel ou on initialise à 0
    $stock = Stock::firstOrNew([
        'item_id' => $validated['item_id'],
        'store_id' => $validated['store_id'],
    ]);

    $originalQty = $stock->quantity ?? 0;

    // On ajuste selon le type
    if ($validated['movement_type'] === 'ajustement') {
        $stock->quantity = $originalQty + $validated['quantity'];

    } elseif ($validated['movement_type'] === 'vente') {
        // $stock->quantity = max(0, $originalQty - $validated['quantity']); // éviter stock négatif
         $stock->quantity = $originalQty - $validated['quantity'];
    } elseif ($validated['movement_type'] === 'inventaire') {
        $stock->quantity = $validated['quantity']; // Ajustement direct
    }

    $stock->save();

    // Enregistre le mouvement pour historique
    StockMovement::create([
        'item_id' => $validated['item_id'],
        'store_id' => $validated['store_id'],
        'type' => $validated['movement_type'],
        'quantity' => $validated['quantity'],
        'reason' => $validated['reason'],
        'reference' => $validated['reference'],
    ]);

    return back()->with('success', 'Stock mis à jour avec succès.');
}




}
