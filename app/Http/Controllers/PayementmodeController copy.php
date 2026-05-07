<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use App\Models\GeneralAccount;
use Illuminate\Http\Request;

class PayementmodeController extends Controller
{
    public function index()
    {
        $paymentmodes = PaymentMode::with(['debitAccount', 'creditAccount'])->orderBy('name')->get();
        $generalAccounts = GeneralAccount::orderBy('name')->get();
        return view('paymentmodes', compact('paymentmodes', 'generalAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_modes,name',
            'customer_balance_action' => 'required|in:+,-',
            'supplier_balance_action' => 'required|in:+,-',
            'type' => 'required|in:décaissement,encaissement',
            'debit_account_id' => 'nullable|exists:general_accounts,id',
            'credit_account_id' => 'nullable|exists:general_accounts,id',
        ]);

        try {
            PaymentMode::create([
                'name' => $request->name,
                'customer_balance_action' => $request->customer_balance_action,
                'supplier_balance_action' => $request->supplier_balance_action,
                'type' => $request->type,
                'debit_account_id' => $request->debit_account_id,
                'credit_account_id' => $request->credit_account_id,
            ]);
            return back()->with('success', 'Mode de paiement créé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Failed to create payment mode', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la création du mode de paiement: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_modes,name,' . $id,
            'customer_balance_action' => 'required|in:+,-',
            'supplier_balance_action' => 'required|in:+,-',
            'type' => 'required|in:décaissement,encaissement',
            'debit_account_id' => 'nullable|exists:general_accounts,id',
            'credit_account_id' => 'nullable|exists:general_accounts,id',
        ]);

        try {
            $paymentmode = PaymentMode::findOrFail($id);
            $paymentmode->update([
                'name' => $request->name,
                'customer_balance_action' => $request->customer_balance_action,
                'supplier_balance_action' => $request->supplier_balance_action,
                'type' => $request->type,
                'debit_account_id' => $request->debit_account_id,
                'credit_account_id' => $request->credit_account_id,
            ]);
            return back()->with('success', 'Mode de paiement mis à jour.');
        } catch (\Exception $e) {
            \Log::error('Failed to update payment mode', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la mise à jour du mode de paiement: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $paymentmode = PaymentMode::findOrFail($id);
            $paymentmode->delete();
            return back()->with('success', 'Mode de paiement supprimé.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete payment mode', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la suppression du mode de paiement: ' . $e->getMessage());
        }
    }
}