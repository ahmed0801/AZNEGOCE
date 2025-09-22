<?php

namespace App\Http\Controllers;

use App\Exports\SuppliersExport;
use App\Models\DiscountGroup;
use App\Models\PaymentMode;
use App\Models\PaymentTerm;
use App\Models\Souche;
use App\Models\Supplier;
use App\Models\TvaGroup;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
public function index(Request $request)
    {
        $query = Supplier::with(['tvaGroup', 'discountGroup', 'paymentMode', 'paymentTerm']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('phone1', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('blocked', $request->status == 'blocked' ? 1 : 0);
        }

        if ($request->filled('city')) {
            $query->where('city', 'LIKE', "%{$request->city}%");
        }

        if ($request->filled('min_solde')) {
            $query->where('solde', '>=', $request->min_solde);
        }

        if ($request->filled('max_solde')) {
            $query->where('solde', '<=', $request->max_solde);
        }

        if ($request->filled('tva_group_id')) {
            $query->where('tva_group_id', $request->tva_group_id);
        }

        if ($request->filled('discount_group_id')) {
            $query->where('discount_group_id', $request->discount_group_id);
        }

        $suppliers = $query->orderBy('name')->paginate(10);

        $tvaGroups = TvaGroup::all();
        $discountGroups = DiscountGroup::all();
        $paymentModes = PaymentMode::all();
        $paymentTerms = PaymentTerm::all();
        
        // Villes uniques pour le filtre
        $cities = Supplier::distinct()->pluck('city')->filter()->sort()->values();

        return view('suppliers', compact(
            'suppliers', 
            'tvaGroups', 
            'discountGroups', 
            'paymentModes', 
            'paymentTerms',
            'cities'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(new SuppliersExport($request), 'fournisseurs_' . date('Y-m-d_H-i-s') . '.xlsx');
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

        $souche = Souche::where('type', 'fournisseur')->first();
        if (!$souche) return back()->with('error', 'Souche fournisseur manquante');

        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $code = ($souche->prefix ?? '') . ($souche->suffix ?? ''). $nextNumber;

        $customer = new Supplier($request->except('code'));
        $customer->code = $code;
        $customer->save();

        $souche->last_number += 1;
        $souche->save();

        return redirect()->route('supplier.index')->with('success', 'Fournisseur créé avec succès');
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
        'risque' => 'nullable|numeric|max:100',
        'tva_group_id' => 'nullable|exists:tva_groups,id',
        'discount_group_id' => 'nullable|exists:discount_groups,id',
        'payment_mode_id' => 'nullable|exists:payment_modes,id',
        'payment_term_id' => 'nullable|exists:payment_terms,id',
    ]);
        $customer = Supplier::findOrFail($id);
        $customer->update($request->all());
        return redirect()->route('supplier.index')->with('success', 'fournisseur modifié avec succès');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->route('supplier.index')->with('success', 'fournisseur supprimé avec succès');
    }
}
