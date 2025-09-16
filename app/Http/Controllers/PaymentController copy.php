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
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['payable', 'customer', 'supplier']);
        
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

        $payments = $query->latest()->get();
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $paymentModes = PaymentMode::all();

        return view('payments.index', compact('payments', 'customers', 'suppliers', 'paymentModes'));
    }

    public function exportPdf(Request $request)
    {
        $query = Payment::with(['payable', 'customer', 'supplier']);
        
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
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->getRemainingBalanceAttribute(),
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
            $remainingBalance = $invoice->total_ttc - $invoice->payments->sum('amount');
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
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->getRemainingBalanceAttribute(),
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
            $remainingBalance = $invoice->total_ttc - $invoice->payments->sum('amount');
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
            $remainingBalance = $note->total_ttc - $note->payments->sum('amount');
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
            $remainingBalance = $note->total_ttc - $note->payments->sum('amount');
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
}