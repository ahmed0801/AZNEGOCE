<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\PaymentMode;
use App\Models\Invoice;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseNote;
use App\Models\SalesNote;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use App\Models\AccountTransfer;
use App\Models\GeneralAccount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
{
    $query = Payment::with(['payable', 'customer', 'supplier', 'paymentMode', 'transfers.toAccount', 'account']);
    
    // Apply filters only if they are provided
    $hasFilters = $request->filled('date_from') || $request->filled('date_to') || 
                  $request->filled('customer_id') || $request->filled('supplier_id') || 
                  $request->filled('payment_mode') || $request->filled('lettrage_code');

    if ($request->filled('date_from')) {
        $query->where('payment_date', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->where('payment_date', '<=', $request->date_to);
    }
    
    if ($request->filled('customer_id')) {
        $query->where('customer_id', $request->customer_id);
    }
    
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    
    if ($request->filled('payment_mode')) {
        $query->where('payment_mode', $request->payment_mode);
    }
    
    if ($request->filled('lettrage_code')) {
        $query->where('lettrage_code', 'like', '%' . $request->lettrage_code . '%');
    }

    // Apply limit for initial load (no filters)
    if (!$hasFilters) {
        $query->take(150); // Limit to 150 recent payments
    }

    $payments = $query->orderBy('updated_at', 'desc')->get();
    $customers = Customer::all();
    $suppliers = Supplier::all();
    $paymentModes = PaymentMode::all();
    $generalAccounts = GeneralAccount::orderBy('name')->get();

    // Pass a flag to indicate if initial load is limited
    $isLimited = !$hasFilters;

    return view('payments.index', compact('payments', 'customers', 'suppliers', 'paymentModes', 'generalAccounts', 'isLimited'));
}






   public function deposit(Request $request)
{
    $request->validate([
        'account_id' => 'required|exists:general_accounts,id',
        'amount' => 'required|numeric|min:0',
        'transaction_date' => 'required|date',
        'reference' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        $account = GeneralAccount::findOrFail($request->account_id);
        
        $payment = Payment::create([
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'payment_date' => $request->transaction_date,
            'payment_mode' => 'Direct',
            'reference' => $request->reference,
            'notes' => $request->notes,
            'lettrage_code' => 'DEP-' . Carbon::parse($request->transaction_date)->format('Ymd') . '-TEMP',
        ]);

        $payment->lettrage_code = 'DEP-' . Carbon::parse($request->transaction_date)->format('Ymd') . '-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT);
        $payment->save();

        // Update account balance (increase for deposit)
        $account->balance += $request->amount;
        $account->save();

        DB::commit();
        return redirect()->route('payments.index')->with('success', 'Dépôt effectué avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Deposit failed', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors du dépôt: ' . $e->getMessage())->withInput();
    }
}

public function withdraw(Request $request)
{
    $request->validate([
        'account_id' => 'required|exists:general_accounts,id',
        'amount' => 'required|numeric|min:0',
        'transaction_date' => 'required|date',
        'reference' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        $account = GeneralAccount::findOrFail($request->account_id);
        
        // Check if sufficient balance
        if ($account->balance < $request->amount) {
            return back()->with('error', 'Solde insuffisant pour le retrait.');
        }

        $payment = Payment::create([
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'payment_date' => $request->transaction_date,
            'payment_mode' => 'Direct',
            'reference' => $request->reference,
            'notes' => $request->notes,
            'lettrage_code' => 'WTH-' . Carbon::parse($request->transaction_date)->format('Ymd') . '-TEMP',
        ]);

        $payment->lettrage_code = 'WTH-' . Carbon::parse($request->transaction_date)->format('Ymd') . '-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT);
        $payment->save();

        // Update account balance (decrease for withdrawal)
        $account->balance -= $request->amount;
        $account->save();

        DB::commit();
        return redirect()->route('payments.index')->with('success', 'Retrait effectué avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Withdrawal failed', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors du retrait: ' . $e->getMessage())->withInput();
    }
}








    public function transfer(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $paymentMode = PaymentMode::where('name', $payment->payment_mode)->first();

        if (!$paymentMode || (!$paymentMode->debit_account_id && !$paymentMode->credit_account_id)) {
            \Log::warning('Payment cannot be transferred, no associated accounts', [
                'payment_id' => $payment->id,
                'payment_mode' => $payment->payment_mode,
            ]);
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être transféré car aucun compte général n\'est associé.');
        }

        $request->validate([
            'to_account_id' => 'required|exists:general_accounts,id',
            'transfer_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Determine the source account (use debit_account_id or credit_account_id)
        $fromAccountId = $paymentMode->debit_account_id ?? $paymentMode->credit_account_id;
        if ($fromAccountId == $request->to_account_id) {
            return redirect()->back()->with('error', 'Le compte de destination doit être différent du compte source.');
        }

        DB::beginTransaction();
        try {
            $transfer = AccountTransfer::create([
                'payment_id' => $payment->id,
                'from_account_id' => $fromAccountId,
                'to_account_id' => $request->to_account_id,
                'amount' => abs($payment->amount),
                'transfer_date' => $request->transfer_date,
                'reference' => $request->reference,
                'notes' => $request->notes,
            ]);

            \Log::info('Account transfer created', [
                'transfer_id' => $transfer->id,
                'payment_id' => $payment->id,
                'from_account_id' => $fromAccountId,
                'to_account_id' => $request->to_account_id,
                'amount' => $transfer->amount,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Transfert effectué avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Account transfer failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors du transfert: ' . $e->getMessage())->withInput();
        }
    }







    public function cancelTransfer($transferId)
    {
        $transfer = AccountTransfer::findOrFail($transferId);

        DB::beginTransaction();
        try {
            // Reverse the balance adjustments
            if ($transfer->from_account_id) {
                $fromAccount = GeneralAccount::find($transfer->from_account_id);
                if ($fromAccount) {
                    $fromAccount->balance += abs($transfer->amount);
                    $fromAccount->save();
                    \Log::info('From account balance restored', [
                        'account_id' => $fromAccount->id,
                        'transfer_id' => $transfer->id,
                        'amount' => $transfer->amount,
                        'new_balance' => $fromAccount->balance,
                    ]);
                }
            }

            $toAccount = GeneralAccount::find($transfer->to_account_id);
            if ($toAccount) {
                $toAccount->balance -= abs($transfer->amount);
                $toAccount->save();
                \Log::info('To account balance adjusted', [
                    'account_id' => $toAccount->id,
                    'transfer_id' => $transfer->id,
                    'amount' => $transfer->amount,
                    'new_balance' => $toAccount->balance,
                ]);
            }

            // Delete the transfer record
            $transfer->delete();

            \Log::info('Account transfer cancelled', [
                'transfer_id' => $transferId,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Transfert annulé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Account transfer cancellation failed', [
                'transfer_id' => $transferId,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'annulation du transfert: ' . $e->getMessage());
        }
    }


    

    






    
public function exportPdf(Request $request)
{
    $query = Payment::with(['payable', 'customer', 'supplier', 'paymentMode', 'transfers.toAccount', 'account']);
    
    if ($request->filled('date_from')) {
        $query->where('payment_date', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->where('payment_date', '<=', $request->date_to);
    }
    
    if ($request->filled('payment_mode')) {
        $query->where('payment_mode', $request->payment_mode);
    }

    if ($request->filled('customer_id')) {
        $query->where('customer_id', $request->customer_id);
    }
    
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }

    if ($request->filled('lettrage_code')) {
        $query->where('lettrage_code', 'like', '%' . $request->lettrage_code . '%');
    }

    $payments = $query->latest()->get();
    $company = \App\Models\CompanyInformation::first() ?? new \App\Models\CompanyInformation([
        'name' => 'AZ NEGOCE',
        'address' => '123 Rue Fictive, Tunis 1000',
        'phone' => '+216 12 345 678',
        'email' => 'contact@aznegoce.com',
    ]);

    $pdf = Pdf::loadView('pdf.payments_report', compact('payments', 'company', 'request'));
    return $pdf->download('payments_report_' . Carbon::now()->format('Ymd') . '.pdf');
}



    public function exportExcel(Request $request)
    {
        return Excel::download(new PaymentsExport($request), 'payments_report_' . Carbon::now()->format('Ymd') . '.xlsx');
    }

    public function makePayment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status !== 'validée' || $invoice->paid) {
            \Log::warning('Sales invoice cannot be paid', [
                'invoice_id' => $invoice->id,
                'status' => $invoice->status,
                'paid' => $invoice->paid,
            ]);
            return redirect()->back()->with('error', 'Cette facture ne peut pas être payée.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . abs($invoice->getRemainingBalanceAttribute()),
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string|exists:payment_modes,name',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'payable_id' => $invoice->id,
                'payable_type' => Invoice::class,
                'customer_id' => $invoice->customer_id,
                'amount' => $request->amount, // Will be adjusted in Payment::saving
                'payment_date' => $request->payment_date,
                'payment_mode' => $request->payment_mode,
                'reference' => $request->reference,
                'notes' => $request->notes,
                'lettrage_code' => 'CL-' . $invoice->numdoc . '-' . Carbon::parse($request->payment_date)->format('Ymd') . '-' . str_pad(Payment::max('id') + 1, 4, '0', STR_PAD_LEFT),
            ]);

            $invoice->load('payments');
            $remainingBalance = $invoice->getRemainingBalanceAttribute();
            $invoice->update(['paid' => abs($remainingBalance) <= 0.01]);

            \Log::info('Payment created for sales invoice', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'invoice_id' => $invoice->id,
                'remaining_balance' => $remainingBalance,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment creation failed for sales invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage())->withInput();
        }
    }

    public function makePaymentPurchase(Request $request, $id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);
        if ($invoice->status !== 'validée' || $invoice->paid) {
            \Log::warning('Purchase invoice cannot be paid', [
                'invoice_id' => $invoice->id,
                'status' => $invoice->status,
                'paid' => $invoice->paid,
            ]);
            return redirect()->back()->with('error', 'Cette facture d\'achat ne peut pas être payée.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . abs($invoice->getRemainingBalanceAttribute()),
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string|exists:payment_modes,name',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'payable_id' => $invoice->id,
                'payable_type' => PurchaseInvoice::class,
                'supplier_id' => $invoice->supplier_id,
                'amount' => $request->amount, // Will be adjusted in Payment::saving
                'payment_date' => $request->payment_date,
                'payment_mode' => $request->payment_mode,
                'reference' => $request->reference,
                'notes' => $request->notes,
                'lettrage_code' => 'FR-' . $invoice->numdoc . '-' . Carbon::parse($request->payment_date)->format('Ymd') . '-' . str_pad(Payment::max('id') + 1, 4, '0', STR_PAD_LEFT),
            ]);

            $invoice->load('payments');
            $remainingBalance = $invoice->getRemainingBalanceAttribute();
            $invoice->update(['paid' => abs($remainingBalance) <= 0.01]);

            \Log::info('Payment created for purchase invoice', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'invoice_id' => $invoice->id,
                'remaining_balance' => $remainingBalance,
            ]);

            DB::commit();
            return redirect()->route('invoices.list')->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment creation failed for purchase invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage())->withInput();
        }
    }

    public function markAsPaid(Request $request, $id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);
        if ($invoice->status !== 'validée') {
            \Log::warning('Purchase invoice cannot be marked as paid', [
                'invoice_id' => $invoice->id,
                'status' => $invoice->status,
            ]);
            return redirect()->back()->with('error', 'Seules les factures validées peuvent être marquées comme payées.');
        }

        $invoice->update(['paid' => true]);
        \Log::info('Purchase invoice marked as paid', [
            'invoice_id' => $invoice->id,
            'paid' => $invoice->paid,
        ]);

        return redirect()->route('purchaseinvoices.index')->with('success', 'Facture marquée comme payée.');
    }

    public function makePaymentSalesNote(Request $request, $id)
    {
        \Log::debug('makePaymentSalesNote called', ['note_id' => $id, 'request' => $request->all()]);

        $note = SalesNote::findOrFail($id);
        if ($note->status !== 'validée' || $note->paid) {
            \Log::warning('Sales note cannot be paid', [
                'note_id' => $note->id,
                'status' => $note->status,
                'paid' => $note->paid,
            ]);
            return redirect()->back()->with('error', 'Cet avoir de vente ne peut pas être payé.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . abs($note->getRemainingBalanceAttribute()),
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string|exists:payment_modes,name',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'payable_id' => $note->id,
                'payable_type' => SalesNote::class,
                'customer_id' => $note->customer_id,
                'amount' => $request->amount, // Will be adjusted in Payment::saving
                'payment_date' => $request->payment_date,
                'payment_mode' => $request->payment_mode,
                'reference' => $request->reference,
                'notes' => $request->notes,
                'lettrage_code' => 'AVCL-' . $note->numdoc . '-' . Carbon::parse($request->payment_date)->format('Ymd') . '-' . str_pad(Payment::max('id') + 1, 4, '0', STR_PAD_LEFT),
            ]);

            $note->load('payments');
            $remainingBalance = $note->getRemainingBalanceAttribute();
            $note->update(['paid' => abs($remainingBalance) <= 0.01]);

            \Log::info('Payment created for sales note', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'note_id' => $note->id,
                'remaining_balance' => $remainingBalance,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment creation failed for sales note', [
                'note_id' => $note->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage())->withInput();
        }
    }

    public function makePaymentPurchaseNote(Request $request, $id)
    {
        $note = PurchaseNote::findOrFail($id);
        if ($note->status !== 'validée' || $note->paid) {
            \Log::warning('Purchase note cannot be paid', [
                'note_id' => $note->id,
                'status' => $note->status,
                'paid' => $note->paid,
            ]);
            return redirect()->back()->with('error', 'Cet avoir d\'achat ne peut pas être payé.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . abs($note->getRemainingBalanceAttribute()),
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string|exists:payment_modes,name',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'payable_id' => $note->id,
                'payable_type' => PurchaseNote::class,
                'supplier_id' => $note->supplier_id,
                'amount' => $request->amount, // Will be adjusted in Payment::saving
                'payment_date' => $request->payment_date,
                'payment_mode' => $request->payment_mode,
                'reference' => $request->reference,
                'notes' => $request->notes,
                'lettrage_code' => 'AVFR-' . $note->numdoc . '-' . Carbon::parse($request->payment_date)->format('Ymd') . '-' . str_pad(Payment::max('id') + 1, 4, '0', STR_PAD_LEFT),
            ]);

            $note->load('payments');
            $remainingBalance = $note->getRemainingBalanceAttribute();
            $note->update(['paid' => abs($remainingBalance) <= 0.01]);

            \Log::info('Payment created for purchase note', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'note_id' => $note->id,
                'remaining_balance' => $remainingBalance,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment creation failed for purchase note', [
                'note_id' => $note->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage())->withInput();
        }
    }






    public function validatePayment(Payment $payment)
{
    if ($payment->validation_comptable === 'en_attente') {
        $payment->update(['validation_comptable' => 'validé']);
        return redirect()->back()->with('success', 'Paiement validé avec succès.');
    }

    return redirect()->back()->with('error', 'Ce paiement ne peut pas être validé.');
}




}