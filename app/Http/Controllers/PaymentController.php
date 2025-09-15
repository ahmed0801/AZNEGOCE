<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\PaymentMode;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use App\Models\PurchaseInvoice;
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
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
        ]);

        $pdf = Pdf::loadView('pdf.payments_report', compact('payments', 'company', 'request'));
        return $pdf->download('payments_report_' . Carbon::now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PaymentsExport($request), 'payments_report_' . Carbon::now()->format('Ymd') . '.xlsx');
    }








// reglement factures achat    
public function makePayment(Request $request, $id)
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
            // Create the payment
            $payment = Payment::create([
                'payable_id' => $invoice->id,
                'payable_type' => PurchaseInvoice::class,
                'supplier_id' => $invoice->supplier_id, // Use supplier_id instead of customer_id
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_mode' => $request->payment_mode,
                'reference' => $request->reference,
                'notes' => $request->notes,
            ]);

            // Generate lettrage code (assuming you have this method in the Payment model)
            $payment->lettrage_code = $payment->generateLettrageCode();
            $payment->save();

            \Log::info('Payment created for purchase invoice', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'invoice_id' => $invoice->id,
            ]);

            // Refresh the invoice's payments relationship
            $invoice->load('payments');

            // Update paid status with floating-point tolerance
            $remainingBalance = $invoice->total_ttc - $invoice->payments->sum('amount');
            \Log::info('Remaining balance calculated for purchase invoice', [
                'invoice_id' => $invoice->id,
                'total_ttc' => $invoice->total_ttc,
                'total_payments' => $invoice->payments->sum('amount'),
                'remaining_balance' => $remainingBalance,
            ]);

            $invoice->update(['paid' => $remainingBalance <= 0.01]);
            \Log::info('Purchase invoice paid status updated', [
                'invoice_id' => $invoice->id,
                'paid' => $remainingBalance <= 0.01,
            ]);

            DB::commit();

            return redirect()->route('invoices.list')->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment creation failed for purchase invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage());
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

        $invoice->markAsPaid();
        \Log::info('Purchase invoice marked as paid', [
            'invoice_id' => $invoice->id,
            'paid' => $invoice->paid,
        ]);

        return redirect()->route('purchaseinvoices.index')->with('success', 'Facture marquée comme payée.');
    }


}