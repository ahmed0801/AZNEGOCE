<?php

namespace App\Http\Controllers;

use App\Models\GeneralAccount;
use App\Models\Payment;
use App\Models\AccountTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralAccountsController extends Controller
{
    public function index()
    {
        $generalAccounts = GeneralAccount::orderBy('name')->get();
        return view('generalaccounts', compact('generalAccounts'));
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
}