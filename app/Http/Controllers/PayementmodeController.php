<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use Illuminate\Http\Request;

class PayementmodeController extends Controller
{
    public function index()
{
    $paymentmodes = PaymentMode::orderBy('name')->get();
    return view('paymentmodes', compact('paymentmodes'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'name' => 'required|string|max:255|unique:payment_modes,name',
    ]);

    PaymentMode::create([ 
        'name' => $request->name,
]);

    return back()->with('success', 'Mode de Paiement créée avec succès.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:discount_groups,name,' . $id
    ]);

    $category = PaymentMode::findOrFail($id);
    $category->update([ 'name' => $request->name,
 ]);

    return back()->with('success', 'Mode de Paiement mise à jour.');
}

public function destroy($id)
{
    $category = PaymentMode::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Mode de Paiement supprimée.');
}
}
