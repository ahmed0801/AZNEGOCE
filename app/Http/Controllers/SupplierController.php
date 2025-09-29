<?php

namespace App\Http\Controllers;

use App\Exports\SuppliersExport;
use App\Models\DiscountGroup;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\PaymentTerm;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseNote;
use App\Models\Souche;
use App\Models\Supplier;
use App\Models\TvaGroup;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log; // Added Log facade

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






     public function getAccountingEntries($customerId)
    {
        try {
            Log::info("Fetching accounting entries for customer ID: $customerId");

            // Check if customer exists
            $customer = Supplier::find($customerId);
            if (!$customer) {
                Log::warning("Customer not found for ID: $customerId");
                return response()->json(['entries' => []], 200);
            }

            // Fetch invoices
            $invoices = PurchaseInvoice::where('supplier_id', $customerId)
                ->select('id', 'numdoc', 'invoice_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($invoice) {
                    return [
                        'type' => 'Facture',
                        'numdoc' => $invoice->numdoc ?? '-',
                        'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($invoice->amount) ? (float) $invoice->amount : 0,
                        'status' => $invoice->paid ? 'Payée' : 'Non payée',
                        'reference' => $invoice->reference ?? '-'
                    ];
                });

            // Fetch sales notes
            $salesNotes = PurchaseNote::where('supplier_id', $customerId)
                ->select('id', 'numdoc', 'note_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($note) {
                    return [
                        'type' => 'Avoir',
                        'numdoc' => $note->numdoc ?? '-',
                        'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($note->amount) ? -(float) $note->amount : 0,
                        'status' => $note->paid ? 'Payée' : 'Non payée',
                        'reference' => $note->reference ?? '-'
                    ];
                });

            // Fetch payments
            $payments = Payment::where('supplier_id', $customerId)
                ->select('id',  'payment_mode', 'reference', 'payment_date as date', 'amount', 'reconciled as status', 'lettrage_code as reference')
                ->get()
                ->map(function ($payment) {
                    return [
                        'type' => $payment->payment_mode,
                        'numdoc' => $payment->reference ?? '-',
                        'date' => $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($payment->amount) ? (float) $payment->amount : 0,
                        'status' => $payment->status ? 'Validé' : 'Validé',
                    ];
                });

            // Merge and sort entries
            $entries = $invoices->merge($salesNotes)->merge($payments)->sortByDesc('date')->values();

            Log::info("Successfully fetched accounting entries for customer ID: $customerId", ['entry_count' => $entries->count()]);

            return response()->json(['entries' => $entries], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching accounting entries for customer ID: $customerId", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erreur serveur: Impossible de charger les écritures comptables'], 500);
        }
    }







     public function getAllAccountingEntries()
{
    try {
        Log::info("Fetching all accounting entries");


                    // Fetch invoices
            $invoices = PurchaseInvoice::with('supplier')
                ->select('id','supplier_id', 'numdoc', 'invoice_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($invoice) {
                    return [
                        'type' => 'Facture',
                        'customer_id' => $invoice->supplier_id,
                    'customer_name' => $invoice->supplier ? $invoice->supplier->name : '-',
                        'numdoc' => $invoice->numdoc ?? '-',
                        'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($invoice->amount) ? (float) $invoice->amount : 0,
                        'status' => $invoice->paid ? 'Payée' : 'Non payée',
                        'reference' => $invoice->reference ?? '-'
                    ];
                });


        // Fetch sales notes
            $salesNotes = PurchaseNote::with('supplier')
                ->select('id','supplier_id', 'numdoc', 'note_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($note) {
                    return [
                        'type' => 'Avoir',
                        'customer_id' => $note->supplier_id,
                    'customer_name' => $note->supplier ? $note->supplier->name : '-',
                        'numdoc' => $note->numdoc ?? '-',
                        'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($note->amount) ? -(float) $note->amount : 0,
                        'status' => $note->paid ? 'Payée' : 'Non payée',
                        'reference' => $note->reference ?? '-'
                    ];
                });



        // Fetch payments with customer relation
        $payments = Payment::with('supplier')
        ->where('supplier_id','!=',null)
            ->select('id', 'supplier_id', 'payment_mode', 'reference', 'payment_date as date', 'amount', 'reconciled as status', 'lettrage_code as reference')
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => $payment->payment_mode,
                    'customer_id' => $payment->supplier_id,
                    'customer_name' => $payment->supplier ? $payment->supplier->name : '-',
                    'numdoc' => $payment->reference ?? '-',
                    'date' => $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-',
                    'amount' => is_numeric($payment->amount) ? (float) $payment->amount : 0,
                    'status' => $payment->status ? 'Validé' : 'Validé',
                    'reference' => $payment->reference ?? '-'
                ];
            });

        // Merge and sort entries
        $entries = $invoices->merge($salesNotes)->merge($payments)->sortByDesc('date')->values();

        Log::info("Successfully fetched all accounting entries", ['entry_count' => $entries->count()]);

        return response()->json(['entries' => $entries], 200);
    } catch (\Exception $e) {
        Log::error("Error fetching all accounting entries", [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Erreur serveur: Impossible de charger les écritures comptables'], 500);
    }
}






 public function getAllAccountingEntriesHT()
{
    try {
        Log::info("Fetching all accounting entries");


                    // Fetch invoices
            $invoices = PurchaseInvoice::with('supplier')
                ->select('id','supplier_id', 'numdoc', 'invoice_date as date', 'total_ht as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($invoice) {
                    return [
                        'type' => 'Facture',
                        'customer_id' => $invoice->supplier_id,
                    'customer_name' => $invoice->supplier ? $invoice->supplier->name : '-',
                        'numdoc' => $invoice->numdoc ?? '-',
                        'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($invoice->amount) ? (float) $invoice->amount : 0,
                        'status' => $invoice->paid ? 'Payée' : 'Non payée',
                        'reference' => $invoice->reference ?? '-'
                    ];
                });


        // Fetch sales notes
            $salesNotes = PurchaseNote::with('supplier')
                ->select('id','supplier_id', 'numdoc', 'note_date as date', 'total_ht as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($note) {
                    return [
                        'type' => 'Avoir',
                        'customer_id' => $note->supplier_id,
                    'customer_name' => $note->supplier ? $note->supplier->name : '-',
                        'numdoc' => $note->numdoc ?? '-',
                        'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($note->amount) ? -(float) $note->amount : 0,
                        'status' => $note->paid ? 'Payée' : 'Non payée',
                        'reference' => $note->reference ?? '-'
                    ];
                });




        // Merge and sort entries
        $entries = $invoices->merge($salesNotes)->sortByDesc('date')->values();

        Log::info("Successfully fetched all accounting entries", ['entry_count' => $entries->count()]);

        return response()->json(['entries' => $entries], 200);
    } catch (\Exception $e) {
        Log::error("Error fetching all accounting entries", [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Erreur serveur: Impossible de charger les écritures comptables'], 500);
    }
}





}
