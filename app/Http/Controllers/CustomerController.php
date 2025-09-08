<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DiscountGroup;
use App\Models\PaymentMode;
use App\Models\PaymentTerm;
use App\Models\Souche;
use App\Models\TvaGroup;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $tvaGroups = TvaGroup::all();
$discountGroups = DiscountGroup::all();
$paymentModes = PaymentMode::all();
$paymentTerms = PaymentTerm::all();
 $customers = Customer::all();
return view('customers', compact('customers', 'tvaGroups', 'discountGroups', 'paymentModes', 'paymentTerms'));

    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone1' => 'nullable|string|max:20',
        'phone2' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'address_delivery' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        'matfiscal' => 'nullable|string|max:100',
        'bank_no' => 'nullable|string|max:100',
        'solde' => 'nullable|numeric|min:0',
        'plafond' => 'nullable|numeric|min:0',
        'risque' => 'nullable|string|max:100',
        'tva_group_id' => 'nullable|exists:tva_groups,id',
        'discount_group_id' => 'nullable|exists:discount_groups,id',
        'payment_mode_id' => 'nullable|exists:payment_modes,id',
        'payment_term_id' => 'nullable|exists:payment_terms,id',
    ]);

        $souche = Souche::where('type', 'client')->first();
        if (!$souche) return back()->with('error', 'Souche client manquante');

        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $code = ($souche->prefix ?? '') . ($souche->suffix ?? ''). $nextNumber;

        $customer = new Customer($request->except('code'));
        $customer->code = $code;
        $customer->save();

        $souche->last_number += 1;
        $souche->save();

        return redirect()->route('customer.index')->with('success', 'Client créé avec succès');
    }

    public function update(Request $request, $id)
    {
         $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone1' => 'nullable|string|max:20',
        'phone2' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'address_delivery' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        'matfiscal' => 'nullable|string|max:100',
        'bank_no' => 'nullable|string|max:100',
        'solde' => 'nullable|numeric|min:0',
        'plafond' => 'nullable|numeric|min:0',
        'risque' => 'nullable|string|max:100',
        'tva_group_id' => 'nullable|exists:tva_groups,id',
        'discount_group_id' => 'nullable|exists:discount_groups,id',
        'payment_mode_id' => 'nullable|exists:payment_modes,id',
        'payment_term_id' => 'nullable|exists:payment_terms,id',
    ]);
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
        return redirect()->route('customer.index')->with('success', 'Client modifié avec succès');
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->route('customer.index')->with('success', 'Client supprimé avec succès');
    }
}