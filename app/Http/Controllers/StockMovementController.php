<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'item_id' => 'required',
        'store_id' => 'required',
        'quantity' => 'required|numeric',
        'type' => 'required',
    ]);

    StockMovement::create($request->all());

    return back()->with('success', 'Mouvement enregistré.');
}
}
