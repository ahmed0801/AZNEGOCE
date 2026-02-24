<?php

namespace App\Http\Controllers;

use App\Exports\SalesNoteExport;
use App\Exports\SalesNotesExport;
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
use App\Models\Payment;
use App\Models\SalesReturnLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Mail\OrderMail;
use App\Mail\OrderReadyMail;
use App\Models\EmailMessage;
use App\Models\SalesOrder;
use App\Models\User;

class SalesInvoicesController extends Controller
{
    public function invoicesList(Request $request)
    {
        $query = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns','vehicle','creditNotes' ])
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
    if ($request->filled('numdoc')) {
        $query->where('numdoc', 'like', '%' . $request->numdoc . '%');
    }

    // NOUVEAU : Filtre par vendeur
    if ($request->filled('vendeur')) {
        $vendeur = trim($request->vendeur);

        $query->whereHas('deliveryNotes', function ($q) use ($vendeur) {
            $q->where('vendeur', 'LIKE', "%{$vendeur}%");
        });
    }



    // NOUVEAU : Filtre par véhicule (lié OU dans les notes)
    if ($request->filled('search_vehicle')) {
        $search = trim($request->search_vehicle);

        $query->where(function ($q) use ($search) {
            // 1. Véhicule lié
            $q->whereHas('vehicle', function ($sub) use ($search) {
                $sub->where('license_plate', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('model_name', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(brand_name, ' ', model_name) LIKE ?", ["%{$search}%"]);
            })
            // 2. OU dans les notes de la facture
            ->orWhere('notes', 'like', "%{$search}%");
        });
    }





        $invoices = $query->paginate(50);
        $customers = Customer::orderBy('name')->get();


        // On récupère aussi la liste des vendeurs uniques pour le select
    $vendeurs = User::where('role', 'vendeur')
        ->orderBy('name')
        ->pluck('name')
        ->unique();


        return view('salesinvoices.index', compact('invoices', 'customers','vendeurs'));
    }

    public function createDirectInvoice($deliveryNoteId)
    {
        $deliveryNote = DeliveryNote::with(['lines.item', 'customer','vehicle'])
            // ->whereIn('status', ['expédié', 'livré'])
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
            $vehicle_id = $deliveryNote->vehicle_id;
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
                'vehicle_id' => $vehicle_id,
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
                $deliveryNote->update([
                    'invoiced' => true,
            'status' => 'expédié',  
        ]);

                                                 // Update customer balance solde client
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $customer->solde = ($customer->solde ?? 0) + $totalTtc;
                    $customer->save();

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
            ->orderBy('numdoc', 'DESC')
            ->get();
        $salesReturns = SalesReturn::with(['customer', 'lines.item'])
            ->where('invoiced', false)
            ->orderBy('numdoc', 'DESC')
            ->get();
        $customers = Customer::orderBy('name')->get();
        return view('salesinvoices.create_grouped', compact('deliveryNotes', 'salesReturns', 'customers'));
    }





    public function storeGroupedInvoice(Request $request)
{
    

        
        try {
        $validated = $request->validate([
            'customer_id'          => 'required|exists:customers,id',
            'invoice_date'         => 'required|date',
            'documents'            => 'required|array|min:1',
            'documents.*'          => 'string',
            'notes'                => 'nullable|string|max:500',
            'action'               => 'nullable|in:save,validate',
            'tva_rate'             => 'required|numeric|min:0|max:100',  // ← ajoute required !
            'lines'                => 'required|array|min:1',
            'lines.*.quantity'     => 'required|numeric',  // accepte négatifs
            'lines.*.unit_price_ht'=> 'required|numeric|min:0.01',
            'lines.*.remise'       => 'nullable|numeric|min:0|max:100',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.delivery_note_id'  => 'nullable|integer|exists:delivery_notes,id',
            'lines.*.sales_return_id'   => 'nullable|integer|exists:sales_returns,id',
        ]);

        \Log::info('Validation OK', $validated);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation échouée', [
            'errors' => $e->errors(),
            'request' => $request->all(),
        ]);

        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();
    }


    

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
$paymentTermLabel = strtolower($customer->paymentTerm->label); // pour éviter les majuscules

    // calcul echeance"
    $days = $customer->paymentTerm->days?? 0;
    $dueDate = $invoiceDate->copy()->endOfMonth()->addDays($days);




            

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
                'status' => $request->action === 'validate' ? 'validée' : 'validée',
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
    [$type, $id] = explode('_', $document);
    $id = (int) $id;  // sécurité : force entier

    if ($type === 'delivery') {
        $deliveryNoteIds[] = $id;  // ← AJOUT ICI
        $pivotData["delivery_{$id}"] = [
            'delivery_note_id' => $id,
            'sales_return_id'  => null,
        ];
    } else {
        $salesReturnIds[] = $id;   // ← AJOUT ICI
        $pivotData["return_{$id}"] = [
            'delivery_note_id' => null,
            'sales_return_id'  => $id,
        ];
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

            if ($request->action != 'dima validate') {
                if (!empty($deliveryNoteIds)) {
        $updated = DeliveryNote::whereIn('id', $deliveryNoteIds)
            ->where('invoiced', false)
            ->where('numclient', $customer->code)
            ->update(['invoiced' => false]);

        \Log::info('BL marqués facturés', [
            'count_expected' => count($deliveryNoteIds),
            'count_updated'  => $updated,
            'ids'            => $deliveryNoteIds,
        ]);
    }

    if (!empty($salesReturnIds)) {
        $updated = SalesReturn::whereIn('id', $salesReturnIds)
            ->where('invoiced', false)
            ->where('customer_id', $request->customer_id)
            ->update(['invoiced' => true]);

        \Log::info('Retours marqués facturés', [
            'count_expected' => count($salesReturnIds),
            'count_updated'  => $updated,
            'ids'            => $salesReturnIds,
        ]);
    }

                                                 // Update customer balance solde client
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $customer->solde = ($customer->solde ?? 0) + $totalTtc;
                    $customer->save();

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










    public function storeGroupedInvoice_old(Request $request)
    {
        

        
        try {
        $validated = $request->validate([
            'customer_id'          => 'required|exists:customers,id',
            'invoice_date'         => 'required|date',
            'documents'            => 'required|array|min:1',
            'documents.*'          => 'string',
            'notes'                => 'nullable|string|max:500',
            'action'               => 'required|in:save,validate',
            'tva_rate'             => 'required|numeric|min:0|max:100',  // ← ajoute required !
            'lines'                => 'required|array|min:1',
            'lines.*.quantity'     => 'required|numeric',  // accepte négatifs
            'lines.*.unit_price_ht'=> 'required|numeric|min:0.01',
            'lines.*.remise'       => 'nullable|numeric|min:0|max:100',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.delivery_note_id'  => 'nullable|integer|exists:delivery_notes,id',
            'lines.*.sales_return_id'   => 'nullable|integer|exists:sales_returns,id',
        ]);

        \Log::info('Validation OK', $validated);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation échouée', [
            'errors' => $e->errors(),
            'request' => $request->all(),
        ]);

        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();
    }


    

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
$paymentTermLabel = strtolower($customer->paymentTerm->label); // pour éviter les majuscules

    // calcul echeance"
    $days = $customer->paymentTerm->days?? 0;
    $dueDate = $invoiceDate->copy()->endOfMonth()->addDays($days);




            

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
    [$type, $id] = explode('_', $document);
    $id = (int) $id;  // sécurité : force entier

    if ($type === 'delivery') {
        $deliveryNoteIds[] = $id;  // ← AJOUT ICI
        $pivotData["delivery_{$id}"] = [
            'delivery_note_id' => $id,
            'sales_return_id'  => null,
        ];
    } else {
        $salesReturnIds[] = $id;   // ← AJOUT ICI
        $pivotData["return_{$id}"] = [
            'delivery_note_id' => null,
            'sales_return_id'  => $id,
        ];
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
        $updated = DeliveryNote::whereIn('id', $deliveryNoteIds)
            ->where('invoiced', false)
            ->where('numclient', $customer->code)
            ->update(['invoiced' => true]);

        \Log::info('BL marqués facturés', [
            'count_expected' => count($deliveryNoteIds),
            'count_updated'  => $updated,
            'ids'            => $deliveryNoteIds,
        ]);
    }

    if (!empty($salesReturnIds)) {
        $updated = SalesReturn::whereIn('id', $salesReturnIds)
            ->where('invoiced', false)
            ->where('customer_id', $request->customer_id)
            ->update(['invoiced' => true]);

        \Log::info('Retours marqués facturés', [
            'count_expected' => count($salesReturnIds),
            'count_updated'  => $updated,
            'ids'            => $salesReturnIds,
        ]);
    }

                                                 // Update customer balance solde client
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $customer->solde = ($customer->solde ?? 0) + $totalTtc;
                    $customer->save();

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


                                                 // Update customer balance solde client
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $customer->solde = ($customer->solde ?? 0) + $totalTtc;
                    $customer->save();

            }

            return redirect()->route('salesinvoices.index')
                ->with('success', $request->action === 'validate'
                    ? 'Facture validée avec succès.'
                    : 'Facture mise à jour avec succès.');
        });
    }

    public function printSingleInvoice($id)
    {
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns','vehicle'])->findOrFail($id);
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
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns','vehicle'])->findOrFail($id);
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
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns','vehicle'])->findOrFail($id);
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
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns','vehicle'])->findOrFail($id);
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
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns','vehicle'])->findOrFail($id);
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

public function exportInvoices(Request $request)
{
    // Passer la Request directement à l'export
    return Excel::download(
        new \App\Exports\SalesInvoicesExport($request), 
        'factures_ventes_' . date('Y-m-d_H-i-s') . '.xlsx'
    );
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
                    // throw new \Exception("Quantité d'avoir invalide pour l'article {$line['article_code']}.");
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
                    // throw new \Exception("Quantité d'avoir invalide pour l'article {$line['article_code']}.");
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

   































// avoirs vente
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

        $notes = $query->paginate(50); // Pagination au lieu de get()
        $customers = Customer::orderBy('name')->get();

        return view('salesnotes.list', compact('notes', 'customers'));
    }


    public function createSalesNote()
    {
        $customers = Customer::with('tvaGroup')->orderBy('name')->get();
        $returns = SalesReturn::with(['customer', 'lines.item'])
            ->where('invoiced', false)
            ->get();
        $invoices = Invoice::with(['customer', 'lines.item'])
            ->where('status', 'validée')
            ->get();
        $tvaRates = $customers->pluck('tvaGroup.rate', 'id')->toArray();

        return view('salesnotes.create_sales_note', compact('customers', 'returns', 'invoices', 'tvaRates'));
    }




    public function storeSalesNote(Request $request)
{
    $rules = [
    'customer_id'          => 'required|exists:customers,id',
    'note_date'            => 'required|date',
    'tva_rate'             => 'required|numeric|min:0',
    'source_type'          => 'required|in:return,invoice,free',
    'source_ids'           => 'nullable|array',
    'source_ids.*'         => 'nullable|numeric|integer',
    'lines'                => 'required|array|min:1',
    'lines.*.quantity'     => 'required|numeric|gt:0',
    'lines.*.unit_price_ht'=> 'required|numeric|gt:0',
    'lines.*.remise'       => 'nullable|numeric|min:0|max:100',
    'lines.*.article_code' => 'required|string|max:100',
    'lines.*.description'  => 'nullable|string|max:500',
    'lines.*.source_id'    => 'nullable|numeric|integer',
    'notes'                => 'nullable|string|max:500',
    'action'               => 'required|in:save,validate',
];

// Ajustements conditionnels
if (in_array($request->input('source_type'), ['return', 'invoice'])) {
    $rules['source_ids']           = 'required|array|min:1';
    $rules['source_ids.*']         = 'required|numeric|integer';
    $rules['lines.*.source_id']    = 'required|numeric|integer';
    $rules['lines.*.article_code'] .= '|exists:items,code';
}

if ($request->input('source_type') === 'free') {
    $rules['lines.*.description'] = 'required|string|max:500';
    // Pas d'exists sur article_code → on crée l'article
}

$request->validate($rules);

    return DB::transaction(function () use ($request) {

        \Log::info('storeSalesNote called', $request->all());

        $customer = Customer::with(['tvaGroup', 'paymentTerm'])->findOrFail($request->customer_id);
        $tvaRate  = $request->tva_rate;
        $dueDate  = $customer->paymentTerm
            ? Carbon::parse($request->note_date)->addDays($customer->paymentTerm->days)
            : null;

        // Souche avoir vente
        $souche = Souche::where('type', 'avoir_vente')->lockForUpdate()->firstOrFail();

        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        $note = SalesNote::create([
            'numdoc'          => $numdoc,
            'customer_id'     => $request->customer_id,
            'note_date'       => $request->note_date,
            'due_date'        => $dueDate,
            'status'          => $request->action === 'validate' ? 'validée' : 'brouillon',
            'paid'            => false,
            'total_ht'        => 0,
            'total_ttc'       => 0,
            'tva_rate'        => $tvaRate,
            'notes'           => $request->notes,
            // On garde une nomenclature cohérente avec tes autres types
            'type'            => $request->source_type === 'free' ? 'free' : ($request->source_type === 'return' ? 'from_return' : 'from_invoice'),
            'sales_return_id' => $request->source_type === 'return'  ? ($request->source_ids[0] ?? null) : null,
            'sales_invoice_id'=> $request->source_type === 'invoice' ? ($request->source_ids[0] ?? null) : null,
        ]);

        $totalHt = 0;
        $returnLinesForAutoReturn = []; // utilisé seulement si source = invoice

        foreach ($request->lines as $index => $line) {

            $quantity    = (float) $line['quantity'];
            $unitPriceHt = (float) $line['unit_price_ht'];
            $remise      = (float) ($line['remise'] ?? 0);
            $articleCode = trim($line['article_code']);

            // -------------------------------------------------------------------------
            // 1. Gestion de l'article selon le type de source
            // -------------------------------------------------------------------------
            if ($request->source_type === 'free') {
                // Mode libre → on crée ou met à jour l'article
                $item = Item::updateOrCreate(
                    ['code' => $articleCode],
                    [
                        'name'       => $line['description'] ?? $articleCode,
                        'sale_price' => $unitPriceHt,
                        // On peut aussi vouloir conserver / mettre à jour d'autres champs
                        // 'cost_price'  => ...,
                        // 'location'    => ...,
                        'is_active'  => 1,
                    ]
                );
                $description = $line['description'] ?? $item->name;
            } 
            else {
                // return ou invoice → l'article doit déjà exister
                $item = Item::where('code', $articleCode)->first();
                if (!$item) {
                    throw new \Exception("Article {$articleCode} introuvable en base.");
                }
                $description = $line['description'] ?? $item->name;
            }

            // -------------------------------------------------------------------------
            // 2. Contrôles de quantité max selon la source (return / invoice)
            // -------------------------------------------------------------------------
            if ($request->source_type === 'return') {
                $source = SalesReturn::findOrFail($line['source_id']);
                $sourceLine = $source->lines->where('article_code', $articleCode)->first();
                if (!$sourceLine) {
                    throw new \Exception("Ligne de retour introuvable pour l'article {$articleCode}.");
                }
                if ($quantity > $sourceLine->returned_quantity) {
                    // throw new \Exception("Quantité d'avoir invalide pour l'article {$articleCode}.");
                }
            } 
            elseif ($request->source_type === 'invoice') {
                $source = Invoice::findOrFail($line['source_id']);
                $sourceLine = $source->lines->where('article_code', $articleCode)->first();
                if (!$sourceLine) {
                    throw new \Exception("Ligne de facture introuvable pour l'article {$articleCode}.");
                }
                if ($quantity > $sourceLine->quantity) {
                    // throw new \Exception("Quantité d'avoir invalide pour l'article {$articleCode}.");
                }
                if ($sourceLine->quantity <= 0 || $sourceLine->unit_price_ht <= 0) {
                    // throw new \Exception("Données de facture invalides pour l'article {$articleCode}.");
                }

                // Préparation pour créer le retour auto plus tard
                $returnLinesForAutoReturn[] = [
                    'article_code'      => $articleCode,
                    'returned_quantity' => $quantity,
                    'unit_price_ht'     => $unitPriceHt,
                    'remise'            => $remise,
                    'description'       => $description,
                ];
            }

            // -------------------------------------------------------------------------
            // 3. Calculs ligne avoir (négatif)
            // -------------------------------------------------------------------------
            $totalLigneHt  = -$quantity * $unitPriceHt * (1 - $remise / 100);
            $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

            SalesNoteLine::create([
                'sales_note_id'    => $note->id,
                'sales_return_id'  => $request->source_type === 'return'  ? ($line['source_id'] ?? null) : null,
                'sales_invoice_id' => $request->source_type === 'invoice' ? ($line['source_id'] ?? null) : null,
                'article_code'     => $articleCode,
                'quantity'         => -$quantity,
                'unit_price_ht'    => $unitPriceHt,
                'remise'           => $remise,
                'total_ligne_ht'   => $totalLigneHt,
                'total_ligne_ttc'  => $totalLigneTtc,
                'description'      => $description, // ← ajouté pour cohérence
            ]);

            $totalHt += $totalLigneHt;
        }

        $note->update([
            'total_ht'  => $totalHt,
            'total_ttc' => $totalHt * (1 + $tvaRate / 100),
        ]);

        // -------------------------------------------------------------------------
        // Création automatique du retour vente UNIQUEMENT si origine = facture
        // -------------------------------------------------------------------------
        if ($request->action === 'validate' && $request->source_type === 'invoice') {

            $returnSouche = Souche::where('type', 'retour_vente')->lockForUpdate()->firstOrFail();

            $returnNextNumber = str_pad($returnSouche->last_number + 1, $returnSouche->number_length, '0', STR_PAD_LEFT);
            $returnNumdoc = ($returnSouche->prefix ?? '') . ($returnSouche->suffix ?? '') . $returnNextNumber;

            $salesReturn = SalesReturn::create([
                'numdoc'      => $returnNumdoc,
                'customer_id' => $request->customer_id,
                'return_date' => $request->note_date,
                'invoiced'    => true,
                'tva_rate'    => $tvaRate,
                'total_ht'    => 0,
                'total_ttc'   => 0,
                'store_id'    => 1,
                'notes'       => 'Retour généré automatiquement pour avoir #' . $note->numdoc,
            ]);

            $returnTotalHt = 0;

            foreach ($returnLinesForAutoReturn as $rLine) {
                $lineHt = $rLine['returned_quantity'] * $rLine['unit_price_ht'] * (1 - $rLine['remise'] / 100);

                SalesReturnLine::create([
                    'sales_return_id'   => $salesReturn->id,
                    'article_code'      => $rLine['article_code'],
                    'returned_quantity' => $rLine['returned_quantity'],
                    'unit_price_ht'     => $rLine['unit_price_ht'],
                    'remise'            => $rLine['remise'],
                    'total_ligne_ht'    => $lineHt,
                    'description'       => $rLine['description'],
                ]);

                $returnTotalHt += $lineHt;

                // Mouvement stock +++
                $item = Item::where('code', $rLine['article_code'])->first();
                if ($item) {
                    $stock = Stock::firstOrNew([
                        'item_id'  => $item->id,
                        'store_id' => $salesReturn->store_id ?? 1,
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) + $rLine['returned_quantity'];
                    $stock->save();

                    StockMovement::create([
                        'item_id'     => $item->id,
                        'store_id'    => $salesReturn->store_id ?? 1,
                        'type'        => 'retour_vente',
                        'quantity'    => $rLine['returned_quantity'],
                        'cost_price'  => $rLine['unit_price_ht'] * (1 - $rLine['remise'] / 100),
                        'supplier_name' => $customer->name,
                        'reason'      => 'Retour auto pour avoir vente #' . $note->numdoc,
                        'reference'   => $note->numdoc,
                    ]);
                }
            }

            $salesReturn->update([
                'total_ht'  => $returnTotalHt,
                'total_ttc' => $returnTotalHt * (1 + $tvaRate / 100),
            ]);

            $returnSouche->last_number += 1;
            $returnSouche->save();

            $note->update(['sales_return_id' => $salesReturn->id]);
        }

        // -------------------------------------------------------------------------
        // Mise à jour solde client (tous les cas validés)
        // -------------------------------------------------------------------------
        if ($request->action === 'validate') {
            $totalTtc = $totalHt * (1 + $tvaRate / 100);
            $customer->solde = ($customer->solde ?? 0) + $totalTtc; // + car avoir = crédit client
            $customer->save();
        }

        // -------------------------------------------------------------------------
        // Marquer les retours comme facturés (seulement si origine = return)
        // -------------------------------------------------------------------------
        if ($request->action === 'validate' && $request->source_type === 'return') {
            SalesReturn::whereIn('id', $request->source_ids)
                ->where('invoiced', false)
                ->update(['invoiced' => true]);
        }

        $souche->last_number += 1;
        $souche->save();

        \Log::info('SalesNote finalisée', [
            'id'              => $note->id,
            'numdoc'          => $note->numdoc,
            'type'            => $note->type,
            'sales_return_id' => $note->sales_return_id,
            'sales_invoice_id'=> $note->sales_invoice_id,
        ]);

        return redirect()->route('salesnotes.list')
            ->with('success', $request->action === 'validate'
                ? 'Avoir validé avec succès.'
                : 'Avoir enregistré comme brouillon.');
    });
}





public function markAsUninvoiced(Request $request, $id)
{
    $deliveryNote = DeliveryNote::findOrFail($id);

    if (!$deliveryNote->invoiced) {
        return back()->with('warning', 'Ce bon de livraison n\'est pas marqué comme facturé.');
    }

    // Option : vérifier si la facture associée existe encore / est annulable
    // Exemple simple : on suppose qu'on peut toujours "démarquer"
    $deliveryNote->invoiced = false;
    $deliveryNote->save();

    // Option bonus : logger l'action
    // Activity::log("BL {$deliveryNote->numdoc} démarcé comme non facturé par " . auth()->user()->name);

    return back()->with('success', 'Le bon de livraison a été marqué comme **non facturé**.');
}





    
  public function editSalesNote($id)
{
    $salesNote = SalesNote::with(['customer', 'lines'])->findOrFail($id);
    $customers = Customer::with('tvaGroup')->orderBy('name')->get();
    $tvaRates = $customers->mapWithKeys(function ($customer) {
        return [$customer->id => $customer->tvaGroup->rate ?? 0];
    })->toArray();

    // Load source documents based on source_type
    $sourceDocuments = collect();
    if ($salesNote->source_type == 'return') {
        $sourceDocuments = SalesReturn::whereIn('id', $salesNote->source_ids)
            ->with('customer')
            ->get()
            ->map(function ($doc) {
                return (object) [
                    'id' => $doc->id,
                    'numdoc' => $doc->numdoc,
                    'customer_name' => $doc->customer->name ?? 'N/A'
                ];
            });
    } elseif ($salesNote->source_type == 'invoice') {
        $sourceDocuments = Invoice::whereIn('id', $salesNote->source_ids)
            ->with('customer')
            ->get()
            ->map(function ($doc) {
                return (object) [
                    'id' => $doc->id,
                    'numdoc' => $doc->numdoc,
                    'customer_name' => $doc->customer->name ?? 'N/A'
                ];
            });
    }

    $salesNote->sourceDocuments = $sourceDocuments;

    \Log::info('Edit Sales Note Data', [
        'salesNote' => $salesNote->toArray(),
        'tvaRates' => $tvaRates,
        'sourceDocuments' => $sourceDocuments->toArray()
    ]);

    return view('salesnotes.edit', compact('salesNote', 'customers', 'tvaRates'));
}

    public function updateSalesNote(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'note_date' => 'required|date',
            'tva_rate' => 'required|numeric|min:0',
            'source_type' => 'required|in:return,invoice,free',
            'source_ids' => 'array',
            'source_ids.*' => 'numeric',
            'lines' => 'required|array',
            'lines.*.quantity' => 'required|numeric|gt:0',
            'lines.*.unit_price_ht' => 'required|numeric|gt:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.article_code' => 'required',
            'lines.*.source_id' => 'required|numeric',
            'notes' => 'nullable|string|max:500',
            'action' => 'required|in:save,validate',
        ]);

        return DB::transaction(function () use ($request, $id) {
            \Log::info('updateSalesNote called', [
                'id' => $id,
                'customer_id' => $request->customer_id,
                'source_type' => $request->source_type,
                'source_ids' => $request->source_ids,
                'lines' => $request->lines,
                'action' => $request->action,
            ]);

            $salesNote = SalesNote::with(['lines', 'customer', 'salesReturn'])->findOrFail($id);
            if ($salesNote->status === 'validée') {
                throw new \Exception('Un avoir validé ne peut pas être modifié.');
            }

            $customer = Customer::with(['tvaGroup', 'paymentTerm'])->findOrFail($request->customer_id);
            $tvaRate = $request->tva_rate;
            $dueDate = $customer->paymentTerm
                ? Carbon::parse($request->note_date)->addDays($customer->paymentTerm->days)
                : null;

            $salesNote->lines()->delete();
            $totalHt = 0;
            $returnLines = [];

            foreach ($request->lines as $index => $line) {
                if ($request->source_type === 'return') {
                    $source = SalesReturn::findOrFail($line['source_id']);
                    $sourceLine = $source->lines->where('article_code', $line['article_code'])->first();
                    if (!$sourceLine) {
                        throw new \Exception("Ligne de retour introuvable pour l'article {$line['article_code']}.");
                    }
                    if ($line['quantity'] > $sourceLine->returned_quantity) {
                        // throw new \Exception("Quantité d'avoir invalide pour l'article {$line['article_code']}.");
                    }
                } else {
                    $source = Invoice::findOrFail($line['source_id']);
                    $sourceLine = $source->lines->where('article_code', $line['article_code'])->first();
                    if (!$sourceLine) {
                        throw new \Exception("Ligne de facture introuvable pour l'article {$line['article_code']}.");
                    }
                    if ($line['quantity'] > $sourceLine->quantity) {
                        // throw new \Exception("Quantité d'avoir invalide pour l'article {$line['article_code']}.");
                    }
                    
                    $returnLines[] = [
                        'article_code' => $line['article_code'],
                        'returned_quantity' => $line['quantity'],
                        'unit_price_ht' => $line['unit_price_ht'],
                        'remise' => $line['remise'] ?? 0,
                        'description' => $sourceLine->description ?? ($sourceLine->item->name ?? $line['article_code']),
                    ];
                }

                $totalLigneHt = -$line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                SalesNoteLine::create([
                    'sales_note_id' => $salesNote->id,
                    'sales_return_id' => $request->source_type === 'return' ? $line['source_id'] : null,
                    'sales_invoice_id' => $request->source_type === 'invoice' ? $line['source_id'] : null,
                    'article_code' => $line['article_code'],
                    'quantity' => -$line['quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalHt += $totalLigneHt;
            }

            $salesNote->update([
                'customer_id' => $request->customer_id,
                'note_date' => $request->note_date,
                'due_date' => $dueDate,
                'status' => $request->action === 'validate' ? 'validée' : 'brouillon',
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
                'tva_rate' => $tvaRate,
                'notes' => $request->notes,
                'type' => $request->source_type === 'return' ? 'from_return' : 'from_invoice',
                'sales_return_id' => $request->source_type === 'return' ? $request->source_ids[0] : null,
                'sales_invoice_id' => $request->source_type === 'invoice' ? $request->source_ids[0] : null,
            ]);

            if ($request->action === 'validate' && $request->source_type === 'invoice') {
                // Delete existing SalesReturn if it exists
                if ($salesNote->sales_return_id) {
                    $existingReturn = SalesReturn::find($salesNote->sales_return_id);
                    if ($existingReturn) {
                        $existingReturn->lines()->delete();
                        foreach ($existingReturn->lines as $line) {
                            $item = Item::where('code', $line->article_code)->first();
                            if ($item) {
                                $stock = Stock::firstOrNew([
                                    'item_id' => $item->id,
                                    'store_id' => $existingReturn->store_id ?? 1,
                                ]);
                                $stock->quantity = ($stock->quantity ?? 0) - $line->returned_quantity;
                                $stock->save();
                            }
                        }
                        $existingReturn->delete();
                    }
                }

                $returnSouche = Souche::where('type', 'retour_vente')->lockForUpdate()->first();
                if (!$returnSouche) {
                    throw new \Exception('Souche retour vente manquante.');
                }

                $returnNextNumber = str_pad($returnSouche->last_number + 1, $returnSouche->number_length, '0', STR_PAD_LEFT);
                $returnNumdoc = ($returnSouche->prefix ?? '') . ($returnSouche->suffix ?? '') . $returnNextNumber;

                $returnTotalHt = 0;

                $salesReturn = SalesReturn::create([
                    'numdoc' => $returnNumdoc,
                    'customer_id' => $request->customer_id,
                    'return_date' => $request->note_date,
                    'invoiced' => false,
                    'tva_rate' => $tvaRate,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'store_id' => 1,
                    'delivery_note_id' => null,
                    'notes' => 'Retour généré automatiquement pour avoir #' . $salesNote->numdoc,
                ]);

                foreach ($returnLines as $returnLine) {
                    $totalLigneHt = $returnLine['returned_quantity'] * $returnLine['unit_price_ht'] * (1 - ($returnLine['remise'] / 100));
                    $returnTotalHt += $totalLigneHt;

                    SalesReturnLine::create([
                        'sales_return_id' => $salesReturn->id,
                        'article_code' => $returnLine['article_code'],
                        'returned_quantity' => $returnLine['returned_quantity'],
                        'unit_price_ht' => $returnLine['unit_price_ht'],
                        'remise' => $returnLine['remise'],
                        'total_ligne_ht' => $totalLigneHt,
                        'description' => $returnLine['description'],
                    ]);

                    $item = Item::where('code', $returnLine['article_code'])->first();
                    if ($item) {
                        $storeId = $salesReturn->store_id ?? 1;
                        $stock = Stock::firstOrNew([
                            'item_id' => $item->id,
                            'store_id' => $storeId,
                        ]);
                        $stock->quantity = ($stock->quantity ?? 0) + $returnLine['returned_quantity'];
                        $stock->save();

                        StockMovement::create([
                            'item_id' => $item->id,
                            'store_id' => $storeId,
                            'type' => 'retour_vente',
                            'quantity' => $returnLine['returned_quantity'],
                            'cost_price' => $returnLine['unit_price_ht'] * (1 - ($returnLine['remise'] / 100)),
                            'supplier_name' => $customer->name,
                            'reason' => 'Retour automatique pour avoir vente #' . $salesNote->numdoc,
                            'reference' => $salesNote->numdoc,
                        ]);
                    }
                }

                $salesReturn->update([
                    'total_ht' => $returnTotalHt,
                    'total_ttc' => $returnTotalHt * (1 + $tvaRate / 100),
                ]);

                $returnSouche->last_number += 1;
                $returnSouche->save();

                $salesNote->update(['sales_return_id' => $salesReturn->id]);
            }



            if ($request->action === 'validate') {
// Update customer balance solde client
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $customer->solde = ($customer->solde ?? 0) + $totalTtc;
                    $customer->save();
            }

            if ($request->action === 'validate' && $request->source_type === 'return') {
                SalesReturn::whereIn('id', $request->source_ids)
                    ->where('invoiced', false)
                    ->update(['invoiced' => true]);
            }

            \Log::info('SalesNote updated', [
                'id' => $salesNote->id,
                'numdoc' => $salesNote->numdoc,
                'type' => $salesNote->type,
                'sales_return_id' => $salesNote->sales_return_id,
                'sales_invoice_id' => $salesNote->sales_invoice_id,
            ]);

            return redirect()->route('salesnotes.list')
                ->with('success', $request->action === 'validate'
                    ? 'Avoir validé avec succès.'
                    : 'Avoir mis à jour avec succès.');
        });
    }

    public function getSourceDocuments(Request $request)
    {
        try {
            Log::info('getSourceDocuments called', [
                'source_type' => $request->input('source_type'),
                'customer_id' => $request->input('customer_id'),
            ]);

            $sourceType = $request->input('source_type');
            $customerId = $request->input('customer_id');
            $documents = collect();

            if (!in_array($sourceType, ['return', 'invoice'])) {
                Log::warning('Invalid source_type', ['source_type' => $sourceType]);
                return response()->json(['documents' => []], 400);
            }

            if (!$customerId) {
                Log::warning('No customer_id provided');
                return response()->json(['documents' => []], 400);
            }

            if ($sourceType === 'return') {
                $returns = SalesReturn::query()
                    ->where('invoiced', false)
                    ->where('delivery_note_id','!=', null)
                    ->where('customer_id', $customerId)
                    ->with('customer')
                    ->latest()->get();
                Log::info('SalesReturn documents query result', [
                    'count' => $returns->count(),
                    'customer_id' => $customerId,
                ]);
                $documents = $returns->map(function ($return) {
                    return [
                        'id' => $return->id,
                        'numdoc' => $return->numdoc ?? 'N/A',
                        'customer_name' => $return->customer ? $return->customer->name : 'N/A',
                    ];
                });
            } elseif ($sourceType === 'invoice') {
                $invoices = Invoice::query()
                    ->where('status', 'validée')
                    ->where('customer_id', $customerId)
                    ->with('customer')
                    ->latest()->get();
                Log::info('Invoice documents query result', [
                    'count' => $invoices->count(),
                    'customer_id' => $customerId,
                ]);
                $documents = $invoices->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'numdoc' => $invoice->numdoc ?? 'N/A',
                        'customer_name' => $invoice->customer ? $invoice->customer->name : 'N/A',
                    ];
                });
            }

            Log::info('getSourceDocuments response', [
                'documents_count' => $documents->count(),
                'documents' => $documents->toArray(),
            ]);
            return response()->json(['documents' => $documents]);
        } catch (\Exception $e) {
            Log::error('Error in getSourceDocuments', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error occurred while fetching documents'], 500);
        }
    }

    public function getSourceLines(Request $request)
    {
        try {
            Log::info('getSourceLines called', [
                'source_type' => $request->input('source_type'),
                'source_ids' => $request->input('source_ids', []),
            ]);

            $sourceType = $request->input('source_type');
            $sourceIds = Arr::wrap($request->input('source_ids', []));

            if (!in_array($sourceType, ['return', 'invoice'])) {
                Log::warning('Invalid source_type', ['source_type' => $sourceType]);
                return response()->json(['lines' => []], 400);
            }

            if (empty($sourceIds)) {
                Log::warning('No source_ids provided', ['source_ids' => $sourceIds]);
                return response()->json(['lines' => []]);
            }

            $lines = collect();

            if ($sourceType === 'return') {
                $returns = SalesReturn::query()
                    ->whereIn('id', $sourceIds)
                    ->where('invoiced', false)
                    ->with(['lines.item', 'customer'])
                    ->get();

                Log::info('SalesReturn query result', [
                    'count' => $returns->count(),
                    'ids' => $returns->pluck('id')->toArray(),
                ]);

                if ($returns->isEmpty()) {
                    Log::warning('No SalesReturns found for provided IDs', ['source_ids' => $sourceIds]);
                    return response()->json(['lines' => []]);
                }

                $lines = $returns->flatMap(function ($return) {
                    Log::info('Processing SalesReturn', [
                        'id' => $return->id,
                        'numdoc' => $return->numdoc,
                        'customer_exists' => !is_null($return->customer),
                        'lines_count' => $return->lines->count(),
                    ]);

                    return $return->lines->map(function ($line) use ($return) {
                        Log::info('Processing SalesReturn line', [
                            'return_id' => $return->id,
                            'article_code' => $line->article_code,
                            'item_exists' => !is_null($line->item),
                        ]);

                        return [
                            'source_id' => $return->id,
                            'source_numdoc' => $return->numdoc ?? 'N/A',
                            'article_code' => $line->article_code ?? 'N/A',
                            'description' => $line->item ? ($line->item->name ?? $line->description ?? $line->article_code ?? 'N/A') : ($line->description ?? $line->article_code ?? 'N/A'),
                            'quantity' => $line->returned_quantity ?? 0,
                            'unit_price_ht' => $line->unit_price_ht ?? 0,
                            'remise' => $line->remise ?? 0,
                        ];
                    });
                });
            } elseif ($sourceType === 'invoice') {
                $invoices = Invoice::query()
                    ->whereIn('id', $sourceIds)
                    ->where('status', 'validée')
                    ->with(['lines.item', 'customer'])
                    ->get();

                Log::info('Invoice query result', [
                    'count' => $invoices->count(),
                    'ids' => $invoices->pluck('id')->toArray(),
                ]);

                if ($invoices->isEmpty()) {
                    Log::warning('No Invoices found for provided IDs', ['source_ids' => $sourceIds]);
                    return response()->json(['lines' => []]);
                }

                $lines = $invoices->flatMap(function ($invoice) {
                    Log::info('Processing Invoice', [
                        'id' => $invoice->id,
                        'numdoc' => $invoice->numdoc,
                        'customer_exists' => !is_null($invoice->customer),
                        'lines_count' => $invoice->lines->count(),
                    ]);

                    return $invoice->lines->map(function ($line) use ($invoice) {
                        Log::info('Processing Invoice line', [
                            'invoice_id' => $invoice->id,
                            'article_code' => $line->article_code,
                            'item_exists' => !is_null($line->item),
                        ]);

                        return [
                            'source_id' => $invoice->id,
                            'source_numdoc' => $invoice->numdoc ?? 'N/A',
                            'article_code' => $line->article_code ?? 'N/A',
                            'description' => $line->item ? ($line->item->name ?? $line->description ?? $line->article_code ?? 'N/A') : ($line->description ?? $line->article_code ?? 'N/A'),
                            'quantity' => $line->quantity ?? 0,
                            'unit_price_ht' => $line->unit_price_ht ?? 0,
                            'remise' => $line->remise ?? 0,
                        ];
                    });
                });
            }

            Log::info('getSourceLines response', [
                'lines_count' => $lines->count(),
                'lines' => $lines->toArray(),
            ]);

            return response()->json(['lines' => $lines->toArray()]);
        } catch (\Exception $e) {
            Log::error('Error in getSourceLines', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error occurred while fetching lines'], 500);
        }
    }

    public function printSingleNote($id)
    {
        $salesNote = SalesNote::with(['customer', 'lines.item', 'salesInvoice', 'salesReturn'])->findOrFail($id);
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
            $generator->getBarcode($salesNote->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.salesnote', compact('salesNote', 'company', 'barcode'));
        return $pdf->stream("avoir_{$salesNote->numdoc}.pdf");
        
    }

    public function exportSingleNote($id)
    {
        $note = SalesNote::with(['customer', 'lines.item', 'salesInvoice', 'salesReturn'])->findOrFail($id);
        $filename = 'avoir_' . $note->numdoc . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new SalesNoteExport($note), $filename);
    }

    public function exportNotes(Request $request)
    {
        return Excel::download(new SalesNotesExport($request), 'avoirs_ventes_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx');
    }











  public function makePayment(Request $request, $id)
{
    $invoice = Invoice::findOrFail($id);
    if ($invoice->status !== 'validée' || $invoice->paid) {
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
        // Create the payment
        $payment = Payment::create([
            'payable_id' => $invoice->id,
            'payable_type' => Invoice::class,
            'customer_id' => $invoice->customer_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_mode' => $request->payment_mode,
            'reference' => $request->reference,
            'notes' => $request->notes,
        ]);

        // Generate lettrage code
        $payment->lettrage_code = $payment->generateLettrageCode();
        $payment->save();

        // Refresh the invoice's payments relationship to include the new payment
        $invoice->load('payments');

        // Update paid status with floating-point tolerance
        $remainingBalance = $invoice->total_ttc - $invoice->payments->sum('amount');
        $invoice->update(['paid' => $remainingBalance <= 0.01]); // Tolerance for floating-point issues

        DB::commit();

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage());
    }
}






public function sendEmail(Request $request, $id)
{
    // Validation
    $request->validate([
        'emails' => 'required|array|min:1',
        'emails.*' => 'required|email',
        'message' => 'nullable|string',
    ]);

    // Récupérer la facture
    $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns','vehicle'])->findOrFail($id);

    // Company fallback
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

    // Message : soit celui passé par le form, soit la valeur par défaut en BDD
    $defaultMessages = EmailMessage::first();
    $messageText = $request->input('message')
                 ?? ($defaultMessages->messagefacturevente ?? 'Veuillez trouver ci-joint votre facture de vente.');

    // Générer le barcode (si utilisé dans la vue PDF)
    $generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($invoice->numdoc, $generator::TYPE_CODE_128)
    );

    // Générer le PDF en mémoire
    $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'company', 'barcode'))->output();

    // Destinataires : first -> To ; reste -> CC
    $emails = $request->input('emails', []);
    $primary = array_shift($emails); // premier email
    $cc = $emails; // reste des adresses

    try {
        Mail::to($primary)
            ->cc($cc)
            ->send(new InvoiceMail($invoice, $company, $pdf, $messageText));
    } catch (\Exception $e) {
        // log si tu veux : \Log::error($e);
        return back()->with('error', 'Erreur lors de l\'envoi du mail : ' . $e->getMessage());
    }

    return back()->with('success', 'Facture envoyée avec succès !');
}





public function sendOrderReadyEmail(Request $request, $id)
{
    $invoice = Invoice::with('customer')->findOrFail($id);
    $company = CompanyInformation::first() ?? new CompanyInformation();

    $email = $request->input('email', $invoice->customer->email ?? 'test@mail.com');

    $messageText = "Votre commande est prête à retirer. Veuillez passer au magasin dans la journée pour récupérer vos pièces.";

    Mail::to($email)
         ->send(new \App\Mail\OrderReadyMail($invoice, $company, $messageText));

    return back()->with('success', 'Notification de retrait envoyée au client !');
}










public function sendEmailorder(Request $request, $id)
{
    // Validation
    $request->validate([
        'emails' => 'required|array|min:1',
        'emails.*' => 'required|email',
        'message' => 'nullable|string',
    ]);

    // Récupérer la facture
            $order = SalesOrder::with(['customer', 'deliveryNote', 'lines.item', 'customer.tvaGroup'])->findOrFail($id);


    // Company fallback
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

    // Message : soit celui passé par le form, soit la valeur par défaut en BDD
    $defaultMessages = EmailMessage::first();
    $messageText = $request->input('message')
                 ?? ($defaultMessages->messagefacturevente ?? 'Veuillez trouver ci-joint votre facture de vente.');

    // Générer le barcode (si utilisé dans la vue PDF)
    $generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($order->numdoc, $generator::TYPE_CODE_128)
    );

    // Générer le PDF en mémoire
    $pdf = Pdf::loadView('pdf.devissansref', compact('order', 'company', 'barcode'))->output();

    // Destinataires : first -> To ; reste -> CC
    $emails = $request->input('emails', []);
    $primary = array_shift($emails); // premier email
    $cc = $emails; // reste des adresses

    try {
        Mail::to($primary)
            ->cc($cc)
            ->send(new OrderMail($order, $company, $pdf, $messageText));
    } catch (\Exception $e) {
        // log si tu veux : \Log::error($e);
        return back()->with('error', 'Erreur lors de l\'envoi du mail : ' . $e->getMessage());
    }

    return back()->with('success', 'Document envoyé avec succès !');
}







    
}