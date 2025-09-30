<?php

namespace App\Http\Controllers;

use App\Models\GeneralAccount;
use App\Models\Payment;
use App\Models\AccountTransfer;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseNote;
use App\Models\SalesNote;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneralAccountsController extends Controller
{
    public function index()
    {
        $generalAccounts = GeneralAccount::orderBy('name')->get();
                $customers = Customer::with(['vehicles', 'tvaGroup', 'discountGroup', 'paymentMode', 'paymentTerm']);
                        $suppliers = Supplier::with(['tvaGroup', 'discountGroup', 'paymentMode', 'paymentTerm']);


        return view('generalaccounts', compact('generalAccounts','customers','suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string|max:255|unique:general_accounts,account_number',
            'name' => 'required|string|max:255',
            'type' => 'required|in:caisse,banque,coffre',
        ]);

        try {
            GeneralAccount::create([
                'account_number' => $request->account_number,
                'name' => $request->name,
                'type' => $request->type,
                'balance' => 0.00,
            ]);
            return back()->with('success', 'Compte général créé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Failed to create general account', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la création du compte général: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'account_number' => 'required|string|max:255|unique:general_accounts,account_number,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:caisse,banque,coffre',
        ]);

        try {
            $generalAccount = GeneralAccount::findOrFail($id);
            $generalAccount->update([
                'account_number' => $request->account_number,
                'name' => $request->name,
                'type' => $request->type,
            ]);
            return back()->with('success', 'Compte général mis à jour.');
        } catch (\Exception $e) {
            \Log::error('Failed to update general account', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la mise à jour du compte général: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $generalAccount = GeneralAccount::findOrFail($id);
            if ($generalAccount->debitPaymentModes()->exists() || $generalAccount->creditPaymentModes()->exists()) {
                return back()->with('error', 'Ce compte est utilisé par des modes de paiement et ne peut pas être supprimé.');
            }
            if ($generalAccount->payments()->exists() || $generalAccount->fromTransfers()->exists() || $generalAccount->toTransfers()->exists()) {
                return back()->with('error', 'Ce compte est lié à des transactions et ne peut pas être supprimé.');
            }
            $generalAccount->delete();
            return back()->with('success', 'Compte général supprimé.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete general account', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la suppression du compte général: ' . $e->getMessage());
        }
    }

    public function reconcile($id)
    {
        $account = GeneralAccount::findOrFail($id);
        
        // Fetch unreconciled payments
        $payments = Payment::where('account_id', $id)
            ->where('reconciled', false)
            ->with(['payable', 'customer', 'supplier', 'paymentMode'])
            ->orderBy('payment_date', 'desc')
            ->get();
        
        // Fetch unreconciled transfers (from or to this account)
        $transfers = AccountTransfer::where(function ($query) use ($id) {
            $query->where('from_account_id', $id)->orWhere('to_account_id', $id);
        })
            ->where('reconciled', false)
            ->with(['payment', 'fromAccount', 'toAccount'])
            ->orderBy('transfer_date', 'desc')
            ->get();

        return view('generalaccounts.reconcile', compact('account', 'payments', 'transfers'));
    }

    public function storeReconciliation(Request $request, $id)
    {
        $request->validate([
            'payment_ids' => 'nullable|array',
            'payment_ids.*' => 'exists:payments,id',
            'transfer_ids' => 'nullable|array',
            'transfer_ids.*' => 'exists:account_transfers,id',
            'balance' => 'nullable|numeric|min:0', // Optional manual balance adjustment
        ]);

        DB::beginTransaction();
        try {
            // Mark selected payments as reconciled
            if ($request->filled('payment_ids')) {
                Payment::whereIn('id', $request->payment_ids)
                    ->where('account_id', $id)
                    ->update(['reconciled' => true]);
            }

            // Mark selected transfers as reconciled
            if ($request->filled('transfer_ids')) {
                AccountTransfer::whereIn('id', $request->transfer_ids)
                    ->where(function ($query) use ($id) {
                        $query->where('from_account_id', $id)->orWhere('to_account_id', $id);
                    })
                    ->update(['reconciled' => true]);
            }

            // Optional: Update balance only if manually provided
            if ($request->filled('balance')) {
                $account = GeneralAccount::findOrFail($id);
                $account->balance = $request->balance;
                $account->save();

                \Log::info('Manual balance adjustment during reconciliation', [
                    'account_id' => $id,
                    'new_balance' => $request->balance,
                ]);
            }

            \Log::info('Reconciliation completed for account', [
                'account_id' => $id,
                'payment_ids' => $request->payment_ids ?? [],
                'transfer_ids' => $request->transfer_ids ?? [],
            ]);

            DB::commit();
            return redirect()->route('generalaccounts.index')->with('success', 'Rapprochement effectué avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reconciliation failed', [
                'account_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Erreur lors du rapprochement: ' . $e->getMessage())->withInput();
        }
    }




    public function transactions($id)
    {
        $account = GeneralAccount::findOrFail($id);
        
        // Fetch payments directly linked to the account (deposits/withdrawals)
        $directPayments = Payment::where('account_id', $id)
            ->with(['payable', 'customer', 'supplier', 'paymentMode'])
            ->orderBy('payment_date', 'desc')
            ->get();
        
        // Fetch payments linked via PaymentMode (debit or credit account)
        $modePayments = Payment::whereHas('paymentMode', function ($query) use ($id) {
            $query->where('debit_account_id', $id)->orWhere('credit_account_id', $id);
        })
            ->with(['payable', 'customer', 'supplier', 'paymentMode'])
            ->orderBy('payment_date', 'desc')
            ->get();
        
        // Combine and remove duplicates (if any)
        $payments = $directPayments->merge($modePayments)->unique('id')->sortByDesc('payment_date');
        
        // Fetch all transfers (from or to this account)
        $transfers = AccountTransfer::where(function ($query) use ($id) {
            $query->where('from_account_id', $id)->orWhere('to_account_id', $id);
        })
            ->with(['payment', 'fromAccount', 'toAccount'])
            ->orderBy('transfer_date', 'desc')
            ->get();

        return view('generalaccounts.transactions', compact('account', 'payments', 'transfers'));
    }











    
public function getAllAccountingEntriesTVA()
{
    try {
        Log::info("Fetching all accounting entries");

        // Fetch invoices with customer relation
        $invoices = Invoice::with('customer')
            ->select('id', 'customer_id', 'numdoc', 'invoice_date as date', 'total_ht','total_ttc', 'paid', 'numdoc as reference')
            ->get()
            ->map(function ($invoice) {
                return [
                    'type' => 'Facture',
                    'customer_id' => $invoice->customer_id,
                    'customer_name' => $invoice->customer ? $invoice->customer->name : '-',
                    'numdoc' => $invoice->numdoc ?? '-',
                    'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                    'amount' => (abs($invoice->total_ttc) - abs($invoice->total_ht)),
                    'status' => $invoice->paid ? 'Payée' : 'Non payée',
                    'reference' => $invoice->reference ?? '-'
                ];
            });

        // Fetch sales notes with customer relation
        $salesNotes = SalesNote::with('customer')
            ->select('id', 'customer_id', 'numdoc', 'note_date as date', 'total_ht','total_ttc', 'paid', 'numdoc as reference')
            ->get()
            ->map(function ($note) {
                return [
                    'type' => 'Avoir',
                    'customer_id' => $note->customer_id,
                    'customer_name' => $note->customer ? $note->customer->name : '-',
                    'numdoc' => $note->numdoc ?? '-',
                    'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                    'amount' => (abs($note->total_ttc) - abs($note->total_ht)),
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










public function getAllAccountingEntriesTVAD()
{
    try {
        Log::info("Fetching all accounting entries");

          $invoices = PurchaseInvoice::with('supplier')
                ->select('id','supplier_id', 'numdoc', 'invoice_date as date', 'total_ht','total_ttc', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($invoice) {
                    return [
                        'type' => 'Facture',
                        'customer_id' => $invoice->supplier_id,
                    'customer_name' => $invoice->supplier ? $invoice->supplier->name : '-',
                        'numdoc' => $invoice->numdoc ?? '-',
                        'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                    'amount' => (abs($invoice->total_ttc) - abs($invoice->total_ht)),
                        'status' => $invoice->paid ? 'Payée' : 'Non payée',
                        'reference' => $invoice->reference ?? '-'
                    ];
                });


        // Fetch sales notes
            $salesNotes = PurchaseNote::with('supplier')
                ->select('id','supplier_id', 'numdoc', 'note_date as date', 'total_ht','total_ttc', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($note) {
                    return [
                        'type' => 'Avoir',
                        'customer_id' => $note->supplier_id,
                    'customer_name' => $note->supplier ? $note->supplier->name : '-',
                        'numdoc' => $note->numdoc ?? '-',
                        'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                    'amount' => (abs($note->total_ttc) - abs($note->total_ht)),
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