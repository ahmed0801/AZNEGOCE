<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\DeliveryNote;
use App\Models\SalesReturn;
use App\Models\Customer;
use App\Models\CompanyInformation;
use App\Models\Souche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
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

    public function createDirect($deliveryNoteId)
    {
        $deliveryNote = DeliveryNote::with(['lines.item', 'customer'])->findOrFail($deliveryNoteId);
        if ($deliveryNote->invoiced) {
            return back()->with('error', 'Ce bon de livraison est déjà facturé.');
        }
        if ($deliveryNote->status !== 'expédié' && $deliveryNote->status !== 'livré') {
            return back()->with('error', 'Seuls les bons de livraison expédiés ou livrés peuvent être facturés.');
        }
        $customers = Customer::orderBy('name')->get();
        return view('salesinvoices.create_direct', compact('deliveryNote', 'customers'));
    }

    public function createGrouped()
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

    public function storeDirect(Request $request, $deliveryNoteId)
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

    public function storeGrouped(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'delivery_notes' => 'nullable|array',
            'delivery_notes.*' => 'exists:delivery_notes,id',
            'sales_returns' => 'nullable|array',
            'sales_returns.*' => 'exists:sales_returns,id',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
        ]);

        if (empty($request->delivery_notes) && empty($request->sales_returns)) {
            return back()->with('error', 'Veuillez sélectionner au moins un bon de livraison ou un retour.');
        }

        return DB::transaction(function () use ($request) {
            $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
            $tvaRate = $customer->tvaGroup->rate ?? 0;
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

            if (!empty($request->delivery_notes)) {
                $deliveryNotes = DeliveryNote::with('lines.item')
                    ->whereIn('id', $request->delivery_notes)
                    ->where('invoiced', false)
                    ->where('numclient', $customer->code)
                    ->get();

                foreach ($deliveryNotes as $deliveryNote) {
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
                    $pivotData[] = ['delivery_note_id' => $deliveryNote->id];
                }
            }

            if (!empty($request->sales_returns)) {
                $salesReturns = SalesReturn::with('lines.item')
                    ->whereIn('id', $request->sales_returns)
                    ->where('invoiced', false)
                    ->where('customer_id', $customer->id)
                    ->get();

                foreach ($salesReturns as $salesReturn) {
                    foreach ($salesReturn->lines as $line) {
                        $totalLigneHt = -$line->returned_quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
                        $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                        InvoiceLine::create([
                            'invoice_id' => $invoice->id,
                            'delivery_note_id' => null,
                            'sales_return_id' => $salesReturn->id,
                            'article_code' => $line->article_code,
                            'quantity' => -$line->returned_quantity,
                            'unit_price_ht' => $line->unit_price_ht,
                            'remise' => $line->remise ?? 0,
                            'total_ligne_ht' => $totalLigneHt,
                            'total_ligne_ttc' => $totalLigneTtc,
                        ]);

                        $totalHt += $totalLigneHt;
                    }
                    $pivotData[] = ['sales_return_id' => $salesReturn->id];
                }
            }

            $invoice->update([
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);

            if ($request->action === 'validate') {
                if (!empty($request->delivery_notes)) {
                    DeliveryNote::whereIn('id', $request->delivery_notes)
                        ->where('invoiced', false)
                        ->update(['invoiced' => true]);
                }
                if (!empty($request->sales_returns)) {
                    SalesReturn::whereIn('id', $request->sales_returns)
                        ->where('invoiced', false)
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

    public function edit($id)
    {
        $invoice = Invoice::with(['lines.item', 'customer', 'deliveryNotes', 'salesReturns'])->findOrFail($id);
        if ($invoice->status === 'validée') {
            return back()->with('error', 'Une facture validée ne peut pas être modifiée.');
        }
        $customers = Customer::orderBy('name')->get();
        return view('salesinvoices.edit', compact('invoice', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $invoice = Invoice::with(['lines', 'customer'])->findOrFail($id);
            if ($invoice->status === 'validée') {
                throw new \Exception('Une facture validée ne peut pas être modifiée.');
            }

            $dueDate = $invoice->customer->paymentTerm
                ? Carbon::parse($request->invoice_date)->addDays($invoice->customer->paymentTerm->days)
                : null;

            $invoice->update([
                'invoice_date' => $request->invoice_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
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

    public function print($id)
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

        $pdf = Pdf::loadView('salesinvoices.pdf', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
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
}