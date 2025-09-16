<?php

namespace App\Http\Controllers;

use App\Models\GeneralAccount;
use Illuminate\Http\Request;

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
                'balance' => 0.00, // Initialize balance to 0
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
            // Check if the account is linked to any payment modes
            if ($generalAccount->debitPaymentModes()->exists() || $generalAccount->creditPaymentModes()->exists()) {
                return back()->with('error', 'Ce compte est utilisé par des modes de paiement et ne peut pas être supprimé.');
            }
            $generalAccount->delete();
            return back()->with('success', 'Compte général supprimé.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete general account', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la suppression du compte général: ' . $e->getMessage());
        }
    }
}