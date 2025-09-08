<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\SalesReturn;
use App\Models\SalesNote;
use App\Models\SalesNoteLine;
use App\Models\Souche;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\CompanyInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Carbon\Carbon;

class SalesInvoicesController extends Controller
{
    public function invoicesList(Request $request)
    {
        $query = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns'])
            ->orderBy('updated_at', 'desc');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('paid')) {
            $query->where('paid', $request->paid === '1');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->get();
        $customers = Customer::orderBy('name')->get();

        return view('salesinvoices.index', compact('invoices', 'customers'));
    }

    public function createDirectInvoice($deliveryNoteId)
    {
        $deliveryNote = DeliveryNote::with(['lines.item', 'customer'])
            ->whereIn('status', ['expédié', 'livré'])
            ->findOrFail($deliveryNoteId);

        if ($deliveryNote->invoiced) {
            return back()->with('error', 'Ce bon de livraison est déjà facturé.');
        }

        $customers = Customer::orderBy('name')->get();
        return view('salesinvoices.create_direct', compact('deliveryNote', 'customers'));
    }



    
    public function storeDirectInvoice(Request $request, $deliveryNoteId)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
        ]);

        return DB::transaction(function () use ($request, $deliveryNoteId) {
            $deliveryNote = DeliveryNote::with(['lines.item', 'customer'])->findOrFail($deliveryNoteId);
            if ($deliveryNote->invoiced) {
                throw new \Exception('Ce bon de livraison est déjà facturé.');
            }

            $customer = $deliveryNote->customer;
            $tvaRate = $deliveryNote->tva_rate ?? 0;
            $dueDate = $customer->paymentTerm
                ? Carbon::parse($request->invoice_date)->addDays($customer->paymentTerm->days)
                : null;

            $souche = Souche::where('type', 'facture_vente')->lockForUpdate()->first();
            if (!$souche) {
                throw new \Exception('Souche facture vente manquante.');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            $invoice = Invoice::create([
                'numdoc' => $numdoc,
                'type' => 'direct',
                'numclient' => $customer->code ?? null,
                'customer_id' => $customer->id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
                'paid' => false,
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
                'notes' => $request->notes,
            ]);

            $totalHt = 0;
            foreach ($deliveryNote->lines as $line) {
                $totalLigneHt = $line->delivered_quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'delivery_note_id' => $deliveryNote->id,
                    'sales_return_id' => null,
                    'article_code' => $line->article_code,
                    'quantity' => $line->delivered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalHt += $totalLigneHt;
            }

            $invoice->update([
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);

            if ($request->action === 'validate') {
                $deliveryNote->update(['invoiced' => true]);
            }

            $souche->last_number += 1;
            $souche->save();

            $invoice->deliveryNotes()->attach($deliveryNote->id);

            return redirect()->route('salesinvoices.index')
                ->with('success', $request->action === 'validate'
                    ? 'Facture validée avec succès.'
                    : 'Facture enregistrée comme brouillon.');
        });
    }

    public function createGroupedInvoice()
    {
        $deliveryNotes = DeliveryNote::with(['customer', 'lines.item'])
            ->where('invoiced', false)
            ->whereIn('status', ['expédié', 'livré'])
            ->get();
        $salesReturns = SalesReturn::with(['customer', 'lines.item'])
            ->where('invoiced', false)
            ->get();
        $customers = Customer::orderBy('name')->get();
        return view('salesinvoices.create_grouped', compact('deliveryNotes', 'salesReturns', 'customers'));
    }




    public function storeGroupedInvoice(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'documents' => 'required|array',
            'documents.*' => 'string',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
            'lines' => 'required|array',
            'lines.*.quantity' => 'required|numeric',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.delivery_note_id' => 'nullable',
            'lines.*.sales_return_id' => 'nullable',
        ]);

        return DB::transaction(function () use ($request) {
$customer = Customer::with(['tvaGroup', 'paymentTerm'])->findOrFail($request->customer_id);
            $tvaRate = $request->tva_rate ?? ($customer->tvaGroup->rate ?? 0);
            $dueDate = $customer->paymentTerm
                ? Carbon::parse($request->invoice_date)->addDays($customer->paymentTerm->days)
                : null;


             
                if (!$customer->paymentTerm) {
                \Log::warning('No payment term for customer', ['customer_id' => $customer->id]);
                throw new \Exception('Terme de paiement manquant pour le client.');
            }


                $paymentTermLabel = strtolower($customer->paymentTerm->label ?? '');

$invoiceDate = Carbon::parse($request->invoice_date);
if (strpos($paymentTermLabel, 'fin du mois') !== false) {
    // $dueDate = $invoiceDate->copy()->endOfMonth()->addDays($customer->paymentTerm->days);
                    $dueDate = $invoiceDate->copy()->endOfMonth();

} else {
    // $dueDate = $invoiceDate->copy()->addDays($customer->paymentTerm->days);
                    $dueDate = $invoiceDate->copy()->addDays($customer->paymentTerm->days);

}


            

            $souche = Souche::where('type', 'facture_vente')->lockForUpdate()->first();
            if (!$souche) {
                throw new \Exception('Souche facture vente manquante.');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            $invoice = Invoice::create([
                'numdoc' => $numdoc,
                'type' => 'groupée',
                'numclient' => $customer->code ?? null,
                'customer_id' => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
                'paid' => false,
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
                'notes' => $request->notes,
            ]);

            $totalHt = 0;
            $pivotData = [];
            $deliveryNoteIds = [];
            $salesReturnIds = [];

            foreach ($request->documents as $document) {
                // dd($document);
                [$type, $id] = explode('_', $document);
                if ($type === 'delivery') {
                    $deliveryNoteIds[] = $id;
                    $pivotData[$id] = ['delivery_note_id' => $id];
                } else {
                    $salesReturnIds[] = $id;
                    $pivotData[$id] = ['sales_return_id' => $id];
                }
            }

            foreach ($request->lines as $index => $line) {
                $totalLigneHt = $line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'delivery_note_id' => $line['delivery_note_id'] ?? null,
                    'sales_return_id' => $line['sales_return_id'] ?? null,
                    'article_code' => $line['article_code'],
                    'quantity' => $line['quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalHt += $totalLigneHt;
            }

            $invoice->update([
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);

            if ($request->action === 'validate') {
                if (!empty($deliveryNoteIds)) {
                    DeliveryNote::whereIn('id', $deliveryNoteIds)
                        ->where('invoiced', false)
                        ->where('numclient', $customer->code)
                        ->update(['invoiced' => true]);
                }
                if (!empty($salesReturnIds)) {
                    SalesReturn::whereIn('id', $salesReturnIds)
                        ->where('invoiced', false)
                        ->where('customer_id', $request->customer_id)
                        ->update(['invoiced' => true]);
                }
            }

            $souche->last_number += 1;
            $souche->save();

            $invoice->deliveryNotes()->sync($pivotData);

            return redirect()->route('salesinvoices.index')
                ->with('success', $request->action === 'validate'
                    ? 'Facture groupée validée avec succès.'
                    : 'Facture groupée enregistrée comme brouillon.');
        });
    }



    

    public function createFreeInvoice()
    {
        $customers = Customer::with('tvaGroup')->orderBy('name')->get();
        return view('salesinvoices.create_free', compact('customers'));
    }

   public function storeFreeInvoice(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'tva_rate' => 'required|numeric|min:0',
            'lines' => 'required|array',
            'lines.*.description' => 'required|string',
            'lines.*.quantity' => 'required|numeric|min:0',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
        ]);

        return DB::transaction(function () use ($request) {
            $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
            $tvaRate = $request->tva_rate;
            $dueDate = $customer->paymentTerm
                ? Carbon::parse($request->invoice_date)->addDays($customer->paymentTerm->days)
                : null;
                

            $souche = Souche::where('type', 'facture_vente')->lockForUpdate()->first();
            if (!$souche) {
                throw new \Exception('Souche facture vente manquante.');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            $invoice = Invoice::create([
                'numdoc' => $numdoc,
                'type' => 'libre',
                'numclient' => $customer->code ?? null,
                'customer_id' => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
                'paid' => false,
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
                'notes' => $request->notes,
            ]);

            $totalHt = 0;
            foreach ($request->lines as $line) {
                $totalLigneHt = $line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'delivery_note_id' => null,
                    'sales_return_id' => null,
                    'article_code' => null,
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalHt += $totalLigneHt;
            }

            $invoice->update([
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);

            $souche->last_number += 1;
            $souche->save();

            return redirect()->route('salesinvoices.index')
                ->with('success', $request->action === 'validate'
                    ? 'Facture libre validée avec succès.'
                    : 'Facture libre enregistrée comme brouillon.');
        });
    }

public function editInvoice($id)
{
    $invoice = Invoice::with(['lines.item', 'lines.deliveryNote', 'lines.salesReturn', 'customer', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
    // dd($invoice);
    if ($invoice->status === 'validée') {
        return back()->with('error', 'Une facture validée ne peut pas être modifiée.');
    }
    $customers = Customer::with('tvaGroup')->orderBy('name')->get();
    $tvaRates = $customers->pluck('tvaGroup.rate', 'id')->toArray();
    return view('salesinvoices.edit', compact('invoice', 'customers', 'tvaRates'));
}
  
  
  
    public function updateInvoice(Request $request, $numdoc)
    {
        // dd($request->all());
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
            'lines' => 'required|array',
            'lines.*.quantity' => 'required|numeric',
            'lines.*.unit_price_ht' => 'required|numeric',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.description' => 'required_if:lines.*.article_code,null|string',
            'lines.*.article_code' => 'nullable|exists:items,code',
        ]);

        return DB::transaction(function () use ($request, $numdoc) {
            $invoice = Invoice::with(['lines', 'customer'])->where('numdoc', $numdoc)->firstOrFail();
            if ($invoice->status === 'validée') {
                throw new \Exception('Une facture validée ne peut pas être modifiée.');
            }

            $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
            $tvaRate = $invoice->tva_rate;
            $dueDate = $customer->paymentTerm
                ? Carbon::parse($request->invoice_date)->addDays($customer->paymentTerm->days)
                : null;

            $invoice->lines()->delete();
            $totalHt = 0;

            foreach ($request->lines as $index => $line) {
                $totalLigneHt = $line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'delivery_note_id' =>$line['delivery_note_id'] ?? null,
                    'sales_return_id' => $line['sales_return_id'] ?? null,
                    'article_code' => $line['article_code'] ?? null,
                    'description' => $line['description'] ?? null,
                    'quantity' => $line['quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalHt += $totalLigneHt;
            }

            $invoice->update([
                'customer_id' => $request->customer_id,
                'numclient' => $customer->code ?? null,
                'invoice_date' => $request->invoice_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
                'notes' => $request->notes,
            ]);

            if ($request->action === 'validate') {
                foreach ($invoice->deliveryNotes as $deliveryNote) {
                    $deliveryNote->update(['invoiced' => true]);
                }
                foreach ($invoice->salesReturns as $salesReturn) {
                    $salesReturn->update(['invoiced' => true]);
                }
            }

            return redirect()->route('salesinvoices.index')
                ->with('success', $request->action === 'validate'
                    ? 'Facture validée avec succès.'
                    : 'Facture mise à jour avec succès.');
        });
    }

    public function printSingleInvoice($id)
    {
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($invoice->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
    }




    public function printSingleInvoiceduplicata($id)
    {
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($invoice->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.invoiceduplic', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
    }

     public function printSingleInvoicesansref($id)
    {
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($invoice->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.invoicesansref', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
    }


         public function printSingleInvoicesansrem($id)
    {
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
        // dd($invoice);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($invoice->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.invoicesansrem', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
    }



         public function printSingleInvoicesans2($id)
    {
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($invoice->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.invoicesans2', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
    }






    public function exportSingleInvoice($id)
    {
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
        return Excel::download(new \App\Exports\SalesInvoiceExport($invoice), 'facture_' . $invoice->numdoc . '.xlsx');
    }

    public function exportInvoices()
    {
        $invoices = Invoice::with(['customer', 'lines.item'])->get();
        return Excel::download(new \App\Exports\SalesInvoicesExport($invoices), 'factures_ventes.xlsx');
    }

    public function markAsPaid(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status !== 'validée') {
            return back()->with('error', 'Seules les factures validées peuvent être marquées comme payées.');
        }

        $invoice->update(['paid' => true]);
        return redirect()->route('salesinvoices.index')->with('success', 'Facture marquée comme payée.');
    }




   public function search(Request $request)
{
    $customerId = $request->input('customer_id');
    $customerCode = $request->input('customer_code');
    $term = $request->input('term', '');
    $type = $request->input('type', 'all');

    // Log inputs for debugging
    \Log::info('sales.orders.search called', [
        'customer_id' => $customerId,
        'customer_code' => $customerCode,
        'term' => $term,
        'type' => $type
    ]);

    $results = collect();

    // Search delivery notes if type is 'all' or 'delivery'
    if ($type === 'all' || $type === 'delivery') {
        $deliveryQuery = DeliveryNote::query()
            ->whereIn('status', ['expédié', 'livré'])
            ->where('invoiced', false)
            ->with(['lines.item', 'customer'])
            ->when($customerCode, function ($query, $customerCode) {
                return $query->where('numclient', $customerCode);
            })
            ->when($term, function ($query, $term) {
                return $query->where('numdoc', 'like', "%{$term}%")
                            ->orWhereHas('customer', function ($q) use ($term) {
                                $q->where('name', 'like', "%{$term}%");
                            });
            });

        $deliveries = $deliveryQuery->get()->map(function ($delivery) {
            return [
                'id' => $delivery->id,
                'type' => 'delivery',
                'numdoc' => $delivery->numdoc ?? 'N/A',
                'order_date' => $delivery->delivery_date ? \Carbon\Carbon::parse($delivery->delivery_date)->format('Y-m-d') : null,
                'customer_name' => $delivery->customer->name ?? $delivery->customer_name ?? 'N/A',
                'tva_rate' => $delivery->tva_rate ?? 0,
                'lines' => $delivery->lines->map(function ($line) {
                    return [
                        'article_code' => $line->article_code ?? 'N/A',
                        'item_name' => $line->item->name ?? $line->description ?? $line->article_code ?? 'N/A',
                        'ordered_quantity' => $line->delivered_quantity ?? 0,
                        'unit_price_ht' => $line->unit_price_ht ?? 0,
                        'remise' => $line->remise ?? 0,
                    ];
                })->toArray(),
            ];
        });

        \Log::info('Delivery Notes Found', ['count' => $deliveries->count(), 'results' => $deliveries->toArray()]);
        $results = $results->merge($deliveries);
    }

    // Search sales returns if type is 'all' or 'return'
    if ($type === 'all' || $type === 'return') {
        $returnQuery = SalesReturn::query()
            ->where('invoiced', false)
            ->with(['lines.item', 'customer'])
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customer_id', $customerId);
            })
            ->when($term, function ($query, $term) {
                return $query->where('numdoc', 'like', "%{$term}%")
                            ->orWhereHas('customer', function ($q) use ($term) {
                                $q->where('name', 'like', "%{$term}%");
                            });
            });

        $returns = $returnQuery->get()->map(function ($return) {
            return [
                'id' => $return->id,
                'type' => 'return',
                'numdoc' => $return->numdoc ?? 'N/A',
                'order_date' => $return->return_date ? \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') : null,
                'customer_name' => $return->customer->name ?? $return->customer_name ?? 'N/A',
                'tva_rate' => $return->tva_rate ?? 0,
                'lines' => $return->lines->map(function ($line) {
                    return [
                        'article_code' => $line->article_code ?? 'N/A',
                        'item_name' => $line->item->name ?? $line->description ?? $line->article_code ?? 'N/A',
                        'ordered_quantity' => -($line->returned_quantity ?? 0), // Negative for returns
                        'unit_price_ht' => $line->unit_price_ht ?? 0,
                        'remise' => $line->remise ?? 0,
                    ];
                })->toArray(),
            ];
        });

        \Log::info('Sales Returns Found', ['count' => $returns->count(), 'results' => $returns->toArray()]);
        $results = $results->merge($returns);
    }

    // Log final result
    \Log::info('Search Result', ['count' => $results->count(), 'results' => $results->toArray()]);

    return response()->json($results->toArray());
}





 public function searchold(Request $request)
{
    $customerId = $request->input('customer_id');
    $customerCode = $request->input('customer_code');
    $term = $request->input('term');

    // Log inputs for debugging
    \Log::info('Search Inputs:', [
        'customer_id' => $customerId,
        'customer_code' => $customerCode,
        'term' => $term
    ]);

    // Search delivery notes
    $deliveryQuery = DeliveryNote::whereIn('status', ['expédié', 'livré'])
        ->where('invoiced', false)
        ->with(['lines.item', 'customer']);

    if ($customerCode) {
        $deliveryQuery->where('numclient', $customerCode);
    }
    if ($term) {
        $deliveryQuery->where('numdoc', 'like', '%' . $term . '%');
    }

    $deliveries = $deliveryQuery->get()->map(function ($delivery) {
        return [
            'id' => $delivery->id,
            'type' => 'delivery',
            'numdoc' => $delivery->numdoc,
            'order_date' => $delivery->delivery_date,
            'customer_name' => $delivery->customer->name ?? 'N/A',
            'tva_rate' => $delivery->tva_rate ?? 0,
            'lines' => $delivery->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? $line->article_code,
                    'ordered_quantity' => $line->delivered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise ?? 0,
                ];
            })->toArray(),
        ];
    });

    // Log delivery results
    \Log::info('Delivery Notes Found:', ['count' => $deliveries->count()]);

    // Search sales returns
    $returnQuery = SalesReturn::where('invoiced', false)
        ->with(['lines.item', 'customer']);

    if ($customerId) {
        $returnQuery->where('customer_id', $customerId);
    }
    if ($term) {
        $returnQuery->where('numdoc', 'like', '%' . $term . '%');
    }

    $returns = $returnQuery->get()->map(function ($return) {
        return [
            'id' => $return->id,
            'type' => 'return',
            'numdoc' => $return->numdoc,
            'order_date' => $return->return_date,
            'customer_name' => $return->customer->name ?? 'N/A',
            'tva_rate' => $return->tva_rate ?? 0,
            'lines' => $return->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? $line->article_code,
                    'ordered_quantity' => -$line->returned_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise ?? 0,
                ];
            })->toArray(),
        ];
    });

    // Log return results
    \Log::info('Sales Returns Found:', ['count' => $returns->count()]);

    $result = $deliveries->merge($returns)->toArray();

    // Log final result
    \Log::info('Search Result:', ['count' => count($result)]);

    return response()->json($result);
}






    public function notesList(Request $request)
    {
        $query = SalesNote::with(['customer', 'lines.item', 'salesInvoice', 'salesReturn'])
            ->orderBy('updated_at', 'desc');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('note_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('note_date', '<=', $request->date_to);
        }

        $notes = $query->get();
        $customers = Customer::orderBy('name')->get();

        return view('notes.list', compact('notes', 'customers'));
    }

    public function createReturnNote()
    {
        $customers = Customer::orderBy('name')->get();
        $returns = SalesReturn::with(['customer', 'lines.item'])
            ->where('invoiced', false)
            ->get();
        return view('notes.create_return', compact('customers', 'returns'));
    }

    public function storeReturnNote(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'note_date' => 'required|date',
            'tva_rate' => 'required|numeric|min:0',
            'returns' => 'required|array',
            'returns.*' => 'exists:sales_returns,id',
            'lines' => 'required|array',
            'lines.*.quantity' => 'required|numeric|min:0',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.sales_return_id' => 'required|exists:sales_returns,id',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
        ]);

        return DB::transaction(function () use ($request) {
            $customer = Customer::findOrFail($request->customer_id);
            $tvaRate = $request->tva_rate;
            $dueDate = $customer->paymentTerm
                ? Carbon::parse($request->note_date)->addDays($customer->paymentTerm->days)
                : null;

            $souche = Souche::where('type', 'avoir_vente')->lockForUpdate()->first();
            if (!$souche) {
                throw new \Exception('Souche avoir vente manquante.');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            $note = SalesNote::create([
                'numdoc' => $numdoc,
                'customer_id' => $request->customer_id,
                'note_date' => $request->note_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
                'paid' => false,
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
                'notes' => $request->notes,
                'type' => 'from_return',
                'sales_return_id' => $request->returns[0],
            ]);

            $totalHt = 0;
            foreach ($request->lines as $index => $line) {
                $salesReturn = SalesReturn::findOrFail($line['sales_return_id']);
                $returnLine = $salesReturn->lines->where('article_code', $line['article_code'])->first();
                if (!$returnLine) {
                    throw new \Exception("Ligne de retour introuvable pour l'article {$line['article_code']}.");
                }
                if ($line['quantity'] > $returnLine->returned_quantity) {
                    throw new \Exception("Quantité d'avoir invalide pour l'article {$line['article_code']}.");
                }

                $totalLigneHt = -$line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                SalesNoteLine::create([
                    'sales_note_id' => $note->id,
                    'sales_return_id' => $line['sales_return_id'],
                    'sales_invoice_id' => null,
                    'article_code' => $line['article_code'],
                    'quantity' => -$line['quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalHt += $totalLigneHt;
            }

            $note->update([
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);

            if ($request->action === 'validate') {
                SalesReturn::whereIn('id', $request->returns)
                    ->where('invoiced', false)
                    ->update(['invoiced' => true]);
            }

            $souche->last_number += 1;
            $souche->save();

            return redirect()->route('notes.list')
                ->with('success', $request->action === 'validate'
                    ? 'Avoir validé avec succès.'
                    : 'Avoir enregistré comme brouillon.');
        });
    }

    public function createInvoiceNote()
    {
        $customers = Customer::orderBy('name')->get();
        $invoices = Invoice::with(['customer', 'lines.item'])
            ->where('status', 'validée')
            ->get();
        return view('notes.create_invoice', compact('customers', 'invoices'));
    }

    public function storeInvoiceNote(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'note_date' => 'required|date',
            'tva_rate' => 'required|numeric|min:0',
            'invoices' => 'required|array',
            'invoices.*' => 'exists:invoices,id',
            'lines' => 'required|array',
            'lines.*.quantity' => 'required|numeric|min:0',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.sales_invoice_id' => 'required|exists:invoices,id',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
        ]);

        return DB::transaction(function () use ($request) {
            $customer = Customer::findOrFail($request->customer_id);
            $tvaRate = $request->tva_rate;
            $dueDate = $customer->paymentTerm
                ? Carbon::parse($request->note_date)->addDays($customer->paymentTerm->days)
                : null;

            $souche = Souche::where('type', 'avoir_vente')->lockForUpdate()->first();
            if (!$souche) {
                throw new \Exception('Souche avoir vente manquante.');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            $note = SalesNote::create([
                'numdoc' => $numdoc,
                'customer_id' => $request->customer_id,
                'note_date' => $request->note_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
                'paid' => false,
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
                'notes' => $request->notes,
                'type' => 'from_invoice',
                'sales_invoice_id' => $request->invoices[0],
            ]);

            $totalHt = 0;
            foreach ($request->lines as $index => $line) {
                $invoice = Invoice::findOrFail($line['sales_invoice_id']);
                $invoiceLine = $invoice->lines->where('article_code', $line['article_code'])->first();
                if (!$invoiceLine) {
                    throw new \Exception("Ligne de facture introuvable pour l'article {$line['article_code']}.");
                }
                if ($line['quantity'] > $invoiceLine->quantity) {
                    throw new \Exception("Quantité d'avoir invalide pour l'article {$line['article_code']}.");
                }

                $totalLigneHt = -$line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                SalesNoteLine::create([
                    'sales_note_id' => $note->id,
                    'sales_invoice_id' => $line['sales_invoice_id'],
                    'sales_return_id' => null,
                    'article_code' => $line['article_code'],
                    'quantity' => -$line['quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalHt += $totalLigneHt;
            }

            $note->update([
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);

            $souche->last_number += 1;
            $souche->save();

            return redirect()->route('notes.list')
                ->with('success', $request->action === 'validate'
                    ? 'Avoir validé avec succès.'
                    : 'Avoir enregistré comme brouillon.');
        });
    }

    public function getReturnLines(Request $request)
    {
        $returnIds = $request->input('return_ids', []);
        $returns = SalesReturn::with(['lines.item', 'customer'])
            ->whereIn('id', $returnIds)
            ->where('invoiced', false)
            ->get();

        $lines = $returns->flatMap(function ($return) {
            return $return->lines->map(function ($line) use ($return) {
                return [
                    'sales_return_id' => $return->id,
                    'return_numdoc' => $return->numdoc,
                    'article_code' => $line->article_code,
                    'description' => $line->item->name ?? $line->article_code,
                    'returned_quantity' => $line->returned_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise ?? 0,
                ];
            });
        });

        return response()->json(['lines' => $lines]);
    }

    public function getInvoiceLines(Request $request)
    {
        $invoiceIds = $request->input('invoice_ids', []);
        $invoices = Invoice::with(['lines.item', 'customer'])
            ->whereIn('id', $invoiceIds)
            ->where('status', 'validée')
            ->get();

        $lines = $invoices->flatMap(function ($invoice) {
            return $invoice->lines->map(function ($line) use ($invoice) {
                return [
                    'sales_invoice_id' => $invoice->id,
                    'invoice_numdoc' => $invoice->numdoc,
                    'article_code' => $line->article_code,
                    'description' => $line->item->name ?? $line->description ?? $line->article_code,
                    'quantity' => $line->quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise ?? 0,
                ];
            });
        });

        return response()->json(['lines' => $lines]);
    }

    public function printSingleNote($id)
    {
        $note = SalesNote::with(['customer', 'lines.item', 'salesInvoice', 'salesReturn'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($note->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('notes.pdf', compact('note', 'company', 'barcode'));
        return $pdf->stream("avoir_{$note->numdoc}.pdf");
    }

    public function exportSingleNote($id)
    {
        $note = SalesNote::with(['customer', 'lines.item', 'salesInvoice', 'salesReturn'])->findOrFail($id);
        return Excel::download(new \App\Exports\SalesNoteExport($note), 'avoir_' . $note->numdoc . '.xlsx');
    }





    
}