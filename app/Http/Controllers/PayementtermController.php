<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerm;
use Illuminate\Http\Request;

class PayementtermController extends Controller
{
    public function index()
{
    $paymentterms = PaymentTerm::orderBy('label')->get();
    return view('paymentterms', compact('paymentterms'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'label' => 'required|string|max:255|unique:payment_terms,label',
        'days' => 'required',
    ]);

    PaymentTerm::create([ 
        'label' => $request->label,
        'days' => $request->days,
]);

    return back()->with('success', 'Condition de Paiement créée avec succès.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'label' => 'required|string|max:255|unique:payment_terms,label,' . $id
    ]);

    $category = PaymentTerm::findOrFail($id);
    $category->update([ 'label' => $request->label,
    'days' => $request->days,
 ]);

    return back()->with('success', 'Condition de Paiement mise à jour.');
}

public function destroy($id)
{
    $category = PaymentTerm::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Condition de Paiement supprimée.');
}
}
