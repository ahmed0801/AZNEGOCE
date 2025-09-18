<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Exports\PurchaseExport;
use App\Exports\PurchaseInvoiceExport;
use App\Exports\PurchaseNoteExport;
use App\Exports\PurchaseNotesExport;
use App\Exports\PurchaseReturnExport;
use App\Exports\PurchasesExport;
use App\Models\CompanyInformation;
use App\Models\Item;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceLine;
use App\Models\PurchaseNote;
use App\Models\PurchaseNoteLine;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnLine;
use App\Models\Reception;
use App\Models\ReceptionLine;
use App\Models\Souche;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use PDF; 


class PurchaseController extends Controller
{
public function list(Request $request)
{
    $query = PurchaseOrder::with(['supplier', 'lines.item', 'reception'])->orderBy('updated_at', 'desc');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('order_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('order_date', '<=', $request->date_to);
    }

    if ($request->filled('reception_status')) {
        $query->whereHas('reception', function ($q) use ($request) {
            $q->where('status', $request->reception_status);
        });
    }

    $purchases = $query->get();
    $suppliers = \App\Models\Supplier::orderBy('name')->get();

    return view('purchases', compact('purchases', 'suppliers'));
}


    public function index()
    {
        $suppliers = Supplier::all();
        $tvaRates = Supplier::with('tvaGroup')
    ->get()
    ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
    ->toJson();
    // dd($tvaRates);
        return view('purchasecreate', compact('suppliers','tvaRates'));
    }

   
    

    public function store(Request $request)
{
    $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
    $tvaRate = $supplier->tvaGroup->rate ?? 0;

    $status = $request->input('action') === 'validate' ? 'validée' : 'brouillon';

    // Récupérer la souche pour les commandes d'achat
    $souche = Souche::where('type', 'commande_achat')->first();
    if (!$souche) {
        return back()->with('error', 'Souche commande achat manquante');
    }

    // Calcul du numéro de document
    $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
    $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

    // Créer la commande avec numdoc
    $order = PurchaseOrder::create([
        'supplier_id' => $request->supplier_id,
        'order_date' => $request->order_date,
        'status' => $status,
        'total_ht' => 0,
        'total_ttc' => 0,
        'notes' => $request->notes,
        'numdoc' => $numdoc,
        'tva_rate' => $tvaRate,
    ]);

    $total = 0;

    foreach ($request->lines as $line) {
        $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - $line['remise'] / 100);
        $total += $ligne_total;

        PurchaseOrderLine::create([
            'purchase_order_id' => $order->id,
            'article_code' => $line['article_code'],
            'ordered_quantity' => $line['ordered_quantity'],
            'unit_price_ht' => $line['unit_price_ht'],
            'remise' => $line['remise'],
            'total_ligne_ht' => $ligne_total,
        ]);
    }

    $order->update([
        'total_ht' => $total,
        'total_ttc' => $total * (1 + $tvaRate / 100)
    ]);

    // Incrémente et sauvegarde la souche
    $souche->last_number += 1;
    $souche->save();

    if ($status === 'validée') {
        $this->createReceptionFromOrder($order, $request);

        // Mise à jour du stock et création des mouvements
        foreach ($order->lines as $index => $line) {
            $item = \App\Models\Item::where('code', $line->article_code)->first();
            if (!$item) continue;

            // Update sale_price if provided
            $salePrice = isset($request->lines[$index]['sale_price']) ? floatval($request->lines[$index]['sale_price']) : null;
            if ($salePrice !== null) {
                $item->sale_price = $salePrice;
                $item->save();
            }

            // Déterminer le magasin
            $storeId = $order->store_id ?? 1;

            // Mettre à jour ou créer le stock
            $stock = \App\Models\Stock::firstOrNew([
                'item_id' => $item->id,
                'store_id' => $storeId,
            ]);
            $stock->quantity = ($stock->quantity ?? 0) + $line->ordered_quantity;
            $stock->save();

            $cost_price = $line->unit_price_ht * (1 - $line->remise / 100);

            // Créer le mouvement
            \App\Models\StockMovement::create([
                'item_id' => $item->id,
                'store_id' => $storeId,
                'type' => 'achat',
                'quantity' => $line->ordered_quantity,
                'cost_price' => $cost_price,
                'supplier_name' => $order->supplier->name,
                'reason' => 'Validation commande achat #' . $order->numdoc,
                'reference' => $order->numdoc,
            ]);
        }
    }

    return redirect()->route('purchases.list')->with('success', 'Commande ' . ($status === 'validée' ? 'validée et créée' : 'créée'));
}







protected function createReceptionFromOrder(PurchaseOrder $order, Request $request)
{
    // Tu peux ici soit utiliser la date de réception actuelle,
    // ou fournir une date dans la requête, par exemple:
    $receptionDate = $request->input('reception_date', now());

    // Créer la réception
    $reception = Reception::create([
        'purchase_order_id' => $order->id,
        'reception_date' => $receptionDate,
        'status' => 'en_cours',
        'total_received' => 0,
    ]);

    $totalReceived = 0;

    // Par défaut, on peut supposer que la réception est totale = quantités commandées
    foreach ($order->lines as $line) {
        ReceptionLine::create([
            'reception_id' => $reception->id,
            'article_code' => $line->article_code,
            // houni lezem => $line->ordered_quantity
            'received_quantity' => 0,
        ]);
        $totalReceived += $line->ordered_quantity;
    }

    $reception->update(['total_received' => $totalReceived]);
}





    public function edit($id)
    {
        $order = PurchaseOrder::with('lines', 'supplier')->findOrFail($id);
        $suppliers = Supplier::all();
                $tvaRates = Supplier::with('tvaGroup')
    ->get()
    ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
    ->toJson();
        return view('purchases_edit', compact('order', 'suppliers','tvaRates'));
    }

   
    

public function update(Request $request, $numdoc)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'order_date' => 'required|date',
        'lines' => 'required|array',
    ]);

    $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
    $tvaRate = $supplier->tvaGroup->rate ?? 0;

    $order = PurchaseOrder::where('numdoc', $numdoc)->firstOrFail();

    // 1. Mettre à jour les infos générales
    $order->update([
        'supplier_id' => $request->supplier_id,
        'order_date' => $request->order_date,
        'notes' => $request->notes,
    ]);

    // 2. Supprimer anciennes lignes et ajouter les nouvelles
    $order->lines()->delete();
    $total = 0;

    foreach ($request->lines as $index => $line) {
        $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - $line['remise'] / 100);
        $total += $ligne_total;

        PurchaseOrderLine::create([
            'purchase_order_id' => $order->id,
            'article_code' => $line['article_code'],
            'ordered_quantity' => $line['ordered_quantity'],
            'unit_price_ht' => $line['unit_price_ht'],
            'remise' => $line['remise'],
            'tva' => $tvaRate,
            'prix_ttc' => $ligne_total * (1 + $tvaRate / 100),
            'total_ligne_ht' => $ligne_total,
        ]);
    }

    // 3. Mettre à jour les totaux
    $order->update([
        'total_ht' => $total,
        'total_ttc' => $total * (1 + $tvaRate / 100),
    ]);

    // 4. Si bouton "Mettre à jour & Valider"
    if ($request->input('action') === 'validate') {
        $order->update(['status' => 'validée']);

        // Gestion réception
        $reception = $order->reception;

        if ($reception) {
            // Mise à jour réception existante
            $reception->update([
                'reception_date' => $request->input('reception_date', now()),
                'status' => 'en_cours',
            ]);
            $reception->lines()->delete();
        } else {
            // Création réception
            $reception = Reception::create([
                'purchase_order_id' => $order->id,
                'reception_date' => now(),
                'status' => 'en_cours',
                'total_received' => 0,
            ]);
        }

        $totalReceived = 0;
        foreach ($order->lines as $index => $line) {
            ReceptionLine::create([
                'reception_id' => $reception->id,
                'article_code' => $line->article_code,
                'received_quantity' => 0,
            ]);
            $totalReceived += $line->ordered_quantity;

            // Update sale_price if provided
            $salePrice = isset($request->lines[$index]['sale_price']) ? floatval($request->lines[$index]['sale_price']) : null;
            if ($salePrice !== null) {
                $item = \App\Models\Item::where('code', $line->article_code)->first();
                if ($item) {
                    $item->sale_price = $salePrice;
                    $item->save();
                }
            }
        }

        $reception->update(['total_received' => $totalReceived]);

        // Mise à jour des stocks et création des mouvements
        foreach ($order->lines as $line) {
            $item = $line->item ?? \App\Models\Item::where('code', $line->article_code)->first();
            if (!$item) continue;

            $storeId = $order->store_id ?? 1;

            $stock = \App\Models\Stock::firstOrNew([
                'item_id' => $item->id,
                'store_id' => $storeId,
            ]);
            $stock->quantity = ($stock->quantity ?? 0) + $line->ordered_quantity;
            $stock->save();

            $cost_price = $line->unit_price_ht * (1 - $line->remise / 100);

            \App\Models\StockMovement::create([
                'item_id' => $item->id,
                'store_id' => $storeId,
                'type' => 'achat',
                'quantity' => $line->ordered_quantity,
                'cost_price' => $cost_price,
                'supplier_name' => $order->supplier->name,
                'reason' => 'Validation MAJ commande #' . $order->numdoc,
                'reference' => $order->numdoc,
            ]);
        }
    }

    return redirect()->route('purchases.list')
        ->with('success', $request->input('action') === 'validate'
            ? 'Commande mise à jour et validée avec succès.'
            : 'Commande mise à jour avec succès.');
}






public function validateOrder($id)
{
    $order = PurchaseOrder::with('lines')->findOrFail($id);

    if ($order->status !== 'brouillon') {
        return back()->with('error', 'Cette commande est déjà validée.');
    }

    $order->update(['status' => 'validée']);



    foreach ($order->lines as $line) {
    // 1. Identifier l'article (via relation item)
    $item = $line->item ?? Item::where('code', $line->article_code)->first();
    if (!$item) continue;

    // 2. Déterminer le magasin (store_id) — à adapter selon ton système
    $storeId = $order->store_id ?? 1; // ⚠️ Remplace 1 par ton store par défaut ou ajoute store_id dans ta table

    // 3. Mettre à jour ou créer le stock
    $stock = Stock::firstOrNew([
        'item_id' => $item->id,
        'store_id' => $storeId,
    ]);
    $stock->quantity = ($stock->quantity ?? 0) + $line->ordered_quantity;
    $stock->save();


    $cost_price = $line->unit_price_ht * (1 - $line->remise / 100);

    // 4. Créer un mouvement de stock
    StockMovement::create([
        'item_id' => $item->id,
        'store_id' => $storeId,
        'type' => 'achat',
        'quantity' => $line->ordered_quantity,
            'cost_price' => $cost_price, // ✅ Ajout du prix d’achat HT ici
                'supplier_name' => $order->supplier->name,
        'reason' => 'Validation de commande achat #' . $order->numdoc,
        'reference' => $order->numdoc,
    ]);
}





    // Créer réception automatique
    $reception = Reception::create([
        'purchase_order_id' => $order->id,
        'reception_date' => now(),
        'status' => 'en_cours',
        'total_received' => 0,
    ]);

    $totalReceived = 0;
    foreach ($order->lines as $line) {
        ReceptionLine::create([
            'reception_id' => $reception->id,
            'article_code' => $line->article_code,
            'received_quantity' => 0,
        ]);
        $totalReceived += $line->ordered_quantity;
    }

    $reception->update(['total_received' => $totalReceived]);

    return back()->with('success', 'Commande validée et réception créée.');
}




public function export(Request $request)
{
    // Récupérer les filtres de la requête
    $filters = $request->only(['status', 'supplier_id', 'date_from', 'date_to']);

    // Exporter le fichier Excel avec les filtres
    return Excel::download(new PurchasesExport($filters), 'commandes_achat.xlsx');
}



    public function exportSingle($id)
    {
        $purchase = PurchaseOrder::with(['supplier', 'reception', 'lines.item'])->findOrFail($id);
        return Excel::download(new PurchaseExport($purchase), "commande_{$purchase->numdoc}.xlsx");
    }
    




    public function printSingleold($id)
    {
        $purchase = PurchaseOrder::with(['supplier', 'reception', 'lines.item', 'supplier.tvaGroup'])->findOrFail($id);
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
        $pdf = Pdf::loadView('pdf.purchase', compact('purchase', 'company'));
        return $pdf->stream("commande_{$purchase->numdoc}.pdf");
    }

    public function companyInformation()
    {
        $company = CompanyInformation::first();
        return view('company_information', compact('company'));
    }




        public function printSingle($id)
    {
        $purchase = PurchaseOrder::with(['supplier', 'reception', 'lines.item', 'supplier.tvaGroup'])->findOrFail($id);
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
        $generator->getBarcode($purchase->numdoc, $generator::TYPE_CODE_128)
    );

        $pdf = Pdf::loadView('pdf.purchase', compact('purchase', 'company','barcode'));
        return $pdf->stream("commande_{$purchase->numdoc}.pdf");
    }
    





// imprimer avis de retrait

public function withdrawalNotice(Request $request, $id)
{
    $purchase = PurchaseOrder::with(['supplier', 'lines.item'])
        ->where('id', $id)
        ->firstOrFail();

    // Vérifiez que le statut de livraison est "non_récuperée"
    if ($purchase->status_livraison !== 'non_récuperée') {
        return redirect()->route('purchases.list')->with('error', 'L\'avis de retrait ne peut être généré que pour les commandes non récupérées.');
    }

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
        $generator->getBarcode($purchase->numdoc, $generator::TYPE_CODE_128)
    );

    // Récupérer le commentaire depuis la requête
    $comment = $request->input('comment');

    $pdf = Pdf::loadView('pdf.withdrawal_notice', compact('purchase', 'company', 'barcode', 'comment'));
    return $pdf->stream("avis_retrait_{$purchase->numdoc}.pdf");
}








    public function storeCompanyInformation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'matricule_fiscal' => 'required|string|max:50',
            'swift' => 'nullable|string|max:50',
            'rib' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $company = CompanyInformation::first();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = $file->getClientOriginalName();
            $logoPath = 'assets/img/' . $filename;
            $file->move(public_path('assets/img'), $filename);
            $validated['logo_path'] = $logoPath;
        } elseif ($company) {
            $validated['logo_path'] = $company->logo_path;
        } else {
            $validated['logo_path'] = 'assets/img/test_logo.png';
        }

        if ($company) {
            $company->update($validated);
        } else {
            CompanyInformation::create($validated);
        }

        return redirect()->route('company_information.edit')->with('success', 'Informations de l\'entreprise enregistrées avec succès.');
    }









public function createReturn($id)
{
    $order = PurchaseOrder::with(['lines.item', 'supplier', 'reception'])->findOrFail($id);
    // if (!$order->reception || $order->reception->status === 'en_cours') {
    //     return back()->with('error', 'Impossible de créer un retour : aucune réception ou réception non terminée.');
    // }
    $suppliers = Supplier::all();
    return view('purchases_return_create', compact('order', 'suppliers'));
}





public function storeReturn(Request $request, $id)
{
    $request->validate([
        'return_date' => 'required|date',
        'type' => 'required|in:total,partiel',
        'lines' => 'required_if:type,partiel|array',
        'lines.*.selected' => 'nullable|boolean',
        'lines.*.article_code' => 'required_if:lines.*.selected,1|string|exists:items,code',
        'lines.*.returned_quantity' => 'required_if:lines.*.selected,1|numeric|min:0',
    ]);

    $order = PurchaseOrder::with(['lines.item', 'supplier'])->findOrFail($id);

    $supplier = $order->supplier;
    $tvaRate = $order->tva_rate ?? 0;

    // Récupérer la souche pour les retours d'achat
    $souche = Souche::where('type', 'retour_achat')->first();
    if (!$souche) {
        return back()->with('error', 'Souche retour achat manquante');
    }

    // Calcul du numéro de document
    $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
    $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

    // Créer le retour
    $return = PurchaseReturn::create([
        'purchase_order_id' => $order->id,
        'supplier_id' => $supplier->id,
        'numdoc' => $numdoc,
        'return_date' => $request->return_date,
        'type' => $request->type,
        'total_ht' => 0,
        'total_ttc' => 0,
        'tva_rate' => $tvaRate,
        'invoiced' => 0,
        'notes' => $request->notes,
    ]);

    $totalHt = 0;
    if ($request->type === 'total') {
        // Retour total : reprendre toutes les lignes de la commande
        foreach ($order->lines as $orderLine) {
            $returnedQuantity = $orderLine->ordered_quantity;
            $unitPriceHt = $orderLine->unit_price_ht;
            $remise = $orderLine->remise;
            $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
            $totalHt += $totalLigneHt;

            PurchaseReturnLine::create([
                'purchase_return_id' => $return->id,
                'article_code' => $orderLine->article_code,
                'returned_quantity' => $returnedQuantity,
                'unit_price_ht' => $unitPriceHt,
                'remise' => $remise,
                'total_ligne_ht' => $totalLigneHt,
            ]);

            // Mise à jour du stock
            $item = Item::where('code', $orderLine->article_code)->first();
            if ($item) {
                $storeId = $order->store_id ?? 1;
                $stock = Stock::firstOrNew([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                ]);
                $stock->quantity = ($stock->quantity ?? 0) - $returnedQuantity;
                $stock->save();

                $costPrice = $unitPriceHt * (1 - $remise / 100);
                StockMovement::create([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                    'type' => 'retour_achat',
                    'quantity' => -$returnedQuantity,
                    'cost_price' => $costPrice,
                    'supplier_name' => $order->supplier->name,
                    'reason' => 'Retour achat #' . $numdoc,
                    'reference' => $numdoc,
                ]);
            }
        }
    } else {
        // Retour partiel : utiliser les lignes sélectionnées
        $hasSelectedLines = false;
        foreach ($request->lines as $articleCode => $line) {
            if (!isset($line['selected']) || $line['selected'] != 1) {
                continue; // Skip unselected lines
            }
            $hasSelectedLines = true;

            $orderLine = $order->lines->where('article_code', $articleCode)->first();
            if (!$orderLine) {
                return back()->with('error', 'Article invalide : ' . $articleCode);
            }
            if ($line['returned_quantity'] > $orderLine->ordered_quantity) {
                return back()->with('error', 'Quantité retournée invalide pour ' . $articleCode . ' (max: ' . $orderLine->ordered_quantity . ')');
            }

            $returnedQuantity = $line['returned_quantity'];
            $unitPriceHt = $orderLine->unit_price_ht;
            $remise = $orderLine->remise;
            $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
            $totalHt += $totalLigneHt;

            PurchaseReturnLine::create([
                'purchase_return_id' => $return->id,
                'article_code' => $articleCode,
                'returned_quantity' => $returnedQuantity,
                'unit_price_ht' => $unitPriceHt,
                'remise' => $remise,
                'total_ligne_ht' => $totalLigneHt,
            ]);

            // Mise à jour du stock
            $item = Item::where('code', $articleCode)->first();
            if ($item) {
                $storeId = $order->store_id ?? 1;
                $stock = Stock::firstOrNew([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                ]);
                $stock->quantity = ($stock->quantity ?? 0) - $returnedQuantity;
                $stock->save();

                $costPrice = $unitPriceHt * (1 - $remise / 100);
                StockMovement::create([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                    'type' => 'retour_achat',
                    'quantity' => -$returnedQuantity,
                    'cost_price' => $costPrice,
                    'supplier_name' => $order->supplier->name,
                    'reason' => 'Retour achat #' . $numdoc,
                    'reference' => $numdoc,
                ]);
            }
        }

        // Vérifier si au moins une ligne est sélectionnée pour un retour partiel
        if (!$hasSelectedLines) {
            $return->delete(); // Supprimer le retour créé s'il n'y a pas de lignes
            return back()->with('error', 'Veuillez sélectionner au moins un article pour un retour partiel.');
        }
    }

    // Mettre à jour les totaux
    $return->update([
        'total_ht' => $totalHt,
        'total_ttc' => $totalHt * (1 + $tvaRate / 100),
    ]);

    // Incrémenter la souche
    $souche->last_number += 1;
    $souche->save();

    return redirect()->route('purchases.list')->with('success', 'Retour d\'achat créé avec succès.');
}











     public function returnsList(Request $request)
    {
        $query = PurchaseReturn::with(['purchaseOrder.supplier', 'lines.item'])->orderBy('updated_at', 'desc');

        if ($request->filled('supplier_id')) {
            $query->whereHas('purchaseOrder', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        if ($request->filled('purchase_order_id')) {
            $query->where('purchase_order_id', $request->purchase_order_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $request->date_to);
        }

        $returns = $query->get();
        $suppliers = Supplier::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::has('returns')->orderBy('numdoc')->get();

        return view('retourachat', compact('returns', 'suppliers', 'purchaseOrders'));
    }

    public function showReturn($id)
    {
        $return = PurchaseReturn::with(['purchaseOrder.supplier', 'lines.item'])->findOrFail($id);
        return view('retourachatshow', compact('return'));
    }
    
    public function exportSingleReturn($id)
    {
        $return = PurchaseReturn::findOrFail($id);
        return Excel::download(new PurchaseReturnExport($return), 'retour_achat_' . $return->numdoc . '.xlsx');
    }

    public function printSingleReturn($id)
    {
        $return = PurchaseReturn::with(['purchaseOrder.supplier', 'lines.item'])->findOrFail($id);
        $company = CompanyInformation::first();

                        $generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($return->numdoc, $generator::TYPE_CODE_128)
    );


        $pdf = PDF::loadView('pdf.purchase_return', compact('return', 'company','barcode'));
        return $pdf->stream('retour_achat_' . $return->numdoc . '.pdf');
    }










// facture

    public function invoicesList(Request $request)
{
    $query = PurchaseInvoice::with(['supplier', 'lines.item','payments'])->orderBy('updated_at', 'desc');

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('paid')) {
        $query->where('paid', $request->paid === '1');
    }
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    if ($request->filled('date_from')) {
        $query->whereDate('invoice_date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('invoice_date', '<=', $request->date_to);
    }

    $invoices = $query->get();
    $suppliers = Supplier::orderBy('name')->get();

    return view('invoices.list', compact('invoices', 'suppliers'));
}


public function createDirectInvoice($orderId)
{
    $order = PurchaseOrder::with(['lines.item', 'supplier'])
        ->where('status', 'validée')
        ->findOrFail($orderId);

    if ($order->status !== 'validée') {
        return back()->with('error', 'La commande doit être validée.');
    }

    if ($order->invoiced) {
        return back()->with('error', 'Cette commande est déjà facturée.');
    }

    if (!is_numeric($order->tva_rate)) {
        return back()->with('error', 'Le taux de TVA de la commande est invalide.');
    }

    $suppliers = Supplier::all();

    return view('invoices.create_direct', compact('order', 'suppliers'));
}



public function createGroupedInvoice()
{
    $orders = PurchaseOrder::where('status', 'validée')
        ->where('invoiced', 0)
        ->with(['lines.item', 'supplier'])
        ->get();

    $returns = PurchaseReturn::where('invoiced', 0)
        ->with(['lines.item', 'supplier'])
        ->get();

    $suppliers = Supplier::all();

    return view('invoices.create_grouped', compact('orders', 'returns', 'suppliers'));
}




public function createFreeInvoice()
{
    $suppliers = Supplier::with('tvaGroup')->get();
    $tvaMap = $suppliers->mapWithKeys(function ($supplier) {
        return [$supplier->id => $supplier->tvaGroup->rate ?? 0];
    })->toArray();

    return view('invoices.create_free', compact('suppliers', 'tvaMap'));
}






public function storeInvoice(Request $request)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'invoice_date' => 'required|date',
        'type' => 'required|in:direct,groupée,libre',
        'lines' => 'required_if:type,direct,groupée|array',
        'lines.*.article_code' => 'required_if:type,direct,groupée|exists:items,code',
        'lines.*.quantity' => 'required_if:type,direct,groupée|numeric',
        'lines.*.unit_price_ht' => 'required_if:type,direct,groupée|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        'lines.*.description' => 'required_if:type,libre|string|nullable',
        'tva_rate' => 'required_if:type,groupée,libre|numeric|min:0',
        'purchase_order_id' => 'required_if:type,direct|exists:purchase_orders,id',
    ]);

    $souche = Souche::where('type', 'facture_achat')->first();
    if (!$souche) {
        return back()->with('error', 'Souche facture achat manquante');
    }

    $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
    $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

    // Determine TVA rate
    $tvaRate = 0;
    if ($request->type === 'direct') {
        $order = PurchaseOrder::findOrFail($request->purchase_order_id);
        if ($order->invoiced) {
            return back()->with('error', 'Cette commande est déjà facturée.');
        }
        $tvaRate = $order->tva_rate ?? 0;
    } elseif ($request->type === 'groupée') {
        $tvaRate = $request->tva_rate ?? 0;
        // Validate that all orders and returns have the same tva_rate
        $orderIds = array_unique(array_filter(array_column($request->lines, 'purchase_order_id')));
        $returnIds = array_unique(array_filter(array_column($request->lines, 'purchase_return_id')));
        $orders = PurchaseOrder::whereIn('id', $orderIds)->get();
        $returns = PurchaseReturn::whereIn('id', $returnIds)->get();
        $tvaRates = $orders->pluck('tva_rate')->merge($returns->pluck('tva_rate'))->unique();
        if ($tvaRates->count() > 1 || $tvaRates->first() != $tvaRate) {
            return back()->with('error', 'Les commandes et retours sélectionnés ont des taux de TVA différents ou incompatibles.');
        }
    } else { // libre
        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $tvaRate = $request->tva_rate ?? $supplier->tvaGroup->rate ?? 0;
    }

    $invoice = PurchaseInvoice::create([
        'supplier_id' => $request->supplier_id,
        'numdoc' => $numdoc,
        'invoice_date' => $request->invoice_date,
        'status' => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
        'total_ht' => 0,
        'total_ttc' => 0,
        'tva_rate' => $tvaRate,
        'notes' => $request->notes,
        'type' => $request->type,
        'purchase_order_id' => $request->type === 'direct' ? $request->purchase_order_id : null,
    ]);

    $totalHt = 0;
    $orderIds = [];
    $returnIds = [];
    foreach ($request->lines as $line) {
        $totalLigneHt = $line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
        $totalHt += $totalLigneHt;

        if ($request->type === 'groupée' && !empty($line['purchase_order_id'])) {
            $orderIds[] = $line['purchase_order_id'];
        }
        if ($request->type === 'groupée' && !empty($line['purchase_return_id'])) {
            $returnIds[] = $line['purchase_return_id'];
        }

        PurchaseInvoiceLine::create([
            'purchase_invoice_id' => $invoice->id,
            'article_code' => $line['article_code'] ?? null,
            'purchase_order_id' => $line['purchase_order_id'] ?? null,
            'purchase_return_id' => $line['purchase_return_id'] ?? null,
            'quantity' => $line['quantity'],
            'unit_price_ht' => $line['unit_price_ht'],
            'remise' => $line['remise'] ?? 0,
            'total_ligne_ht' => $totalLigneHt,
            'tva' => $tvaRate,
            'prix_ttc' => $totalLigneHt * (1 + $tvaRate / 100),
            'description' => $line['description'] ?? null,
        ]);
    }

    $invoice->update([
        'total_ht' => $totalHt,
        'total_ttc' => $totalHt * (1 + $tvaRate / 100),
    ]);

    if ($request->type === 'direct') {
        $order->invoiced = 1;
        $order->save();
    } elseif ($request->type === 'groupée') {
        if (!empty($orderIds)) {
            PurchaseOrder::whereIn('id', array_unique($orderIds))->update(['invoiced' => 1]);
        }
        if (!empty($returnIds)) {
            PurchaseReturn::whereIn('id', array_unique($returnIds))->update(['invoiced' => 1]);
        }
    }

    $souche->last_number += 1;
    $souche->save();



                            if ($request->action === 'validate') {
                                
// Update customer balance solde fournisseur
$supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $supplier->solde = ($supplier->solde ?? 0) + $totalTtc;
                    $supplier->save();
            }



    return redirect()->route('invoices.list')->with('success', 'Facture créée avec succès.');
}




public function editInvoice($id)
{
    $invoice = PurchaseInvoice::with(['lines.item', 'supplier'])->findOrFail($id);
    if ($invoice->status === 'validée') {
        return back()->with('error', 'Impossible de modifier une facture validée.');
    }
    $suppliers = Supplier::all();
    $tvaRates = Supplier::with('tvaGroup')
        ->get()
        ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
        ->toJson();
    return view('invoices.edit', compact('invoice', 'suppliers', 'tvaRates'));
}

public function updateInvoice(Request $request, $numdoc)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'invoice_date' => 'required|date',
        'lines' => 'required|array',
        'lines.*.article_code' => 'required_if:type,direct,groupée|exists:items,code',
        'lines.*.quantity' => 'required_if:type,direct,groupée|numeric|min:0',
        'lines.*.unit_price_ht' => 'required_if:type,direct,groupée|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        'lines.*.description' => 'required_if:type,libre|string|nullable',
    ]);

    $invoice = PurchaseInvoice::where('numdoc', $numdoc)->firstOrFail();
    if ($invoice->status === 'validée') {
        return back()->with('error', 'Impossible de modifier une facture validée.');
    }

    $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
    $tvaRate = $supplier->tvaGroup->rate ?? 0;

    $invoice->update([
        'supplier_id' => $request->supplier_id,
        'invoice_date' => $request->invoice_date,
        'notes' => $request->notes,
    ]);

    $invoice->lines()->delete();
    $totalHt = 0;
    foreach ($request->lines as $line) {
        $totalLigneHt = $line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
        $totalHt += $totalLigneHt;

        PurchaseInvoiceLine::create([
            'purchase_invoice_id' => $invoice->id,
            'article_code' => $line['article_code'] ?? null,
            'purchase_order_id' => $line['purchase_order_id'] ?? null,
            'quantity' => $line['quantity'],
            'unit_price_ht' => $line['unit_price_ht'],
            'remise' => $line['remise'] ?? 0,
            'total_ligne_ht' => $totalLigneHt,
            'tva' => $tvaRate,
            'prix_ttc' => $totalLigneHt * (1 + $tvaRate / 100),
            'description' => $line['description'] ?? null,
        ]);
    }

    $invoice->update([
        'total_ht' => $totalHt,
        'total_ttc' => $totalHt * (1 + $tvaRate / 100),
        'status' => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
    ]);


                                if ($request->action === 'validate') {
                                
// Update customer balance solde fournisseur
$supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $supplier->solde = ($supplier->solde ?? 0) + $totalTtc;
                    $supplier->save();
            }

    return redirect()->route('invoices.list')->with('success', 'Facture mise à jour avec succès.');
}




public function printSingleInvoice($id)
{
    $invoice = PurchaseInvoice::with(['supplier', 'lines.item'])->findOrFail($id);
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

    $pdf = PDF::loadView('pdf.purchase_invoice', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
}



public function exportSingleInvoice($id)
{
    $invoice = PurchaseInvoice::with(['supplier', 'lines.item'])->findOrFail($id);
    return Excel::download(new PurchaseInvoiceExport($invoice), "facture_{$invoice->numdoc}.xlsx");
}

public function exportinvoices(Request $request)
{
    $filters = $request->only(['supplier_id', 'status', 'type', 'date_from', 'date_to']);
    return Excel::download(new InvoicesExport($filters), 'factures_achat.xlsx');
}



public function old_search_onlycmd(Request $request)
{
    $query = PurchaseOrder::where('status', 'validée')
        ->where('invoiced', 0)
        ->with(['lines.item', 'supplier']);
    
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('term')) {
        $query->where('numdoc', 'like', '%' . $request->term . '%');
    }

    return $query->get()->map(function ($order) {
        return [
            'id' => $order->id,
            'numdoc' => $order->numdoc,
            'order_date' => $order->order_date,
            'supplier_name' => $order->supplier->name,
            'tva_rate' => $order->tva_rate ?? 0,
            'lines' => $order->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'ordered_quantity' => $line->ordered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise,
                ];
            })->toArray(),
        ];
    });
}







public function search(Request $request)
{
    $supplierId = $request->input('supplier_id');
    $term = $request->input('term');

    // Search purchase orders
    $orderQuery = PurchaseOrder::where('status', 'validée')
        ->where('invoiced', 0)
        ->with(['lines.item', 'supplier']);

    if ($supplierId) {
        $orderQuery->where('supplier_id', $supplierId);
    }
    if ($term) {
        $orderQuery->where('numdoc', 'like', '%' . $term . '%');
    }

    $orders = $orderQuery->get()->map(function ($order) {
        return [
            'id' => 'order_' . $order->id,
            'type' => 'order',
            'numdoc' => $order->numdoc,
            'order_date' => $order->order_date,
            'supplier_name' => $order->supplier->name,
            'tva_rate' => $order->tva_rate ?? 0,
            'lines' => $order->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'ordered_quantity' => $line->ordered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise,
                ];
            })->toArray(),
        ];
    });

    // Search purchase returns
    $returnQuery = PurchaseReturn::where('invoiced', 0)
        ->with(['lines.item', 'supplier']);

    if ($supplierId) {
        $returnQuery->where('supplier_id', $supplierId);
    }
    if ($term) {
        $returnQuery->where('numdoc', 'like', '%' . $term . '%');
    }

    $returns = $returnQuery->get()->map(function ($return) {
        return [
            'id' => 'return_' . $return->id,
            'type' => 'return',
            'numdoc' => $return->numdoc,
            'order_date' => $return->return_date,
            'supplier_name' => $return->supplier->name ?? 'N/A',
            'tva_rate' => $return->tva_rate ?? 0,
            'lines' => $return->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'ordered_quantity' => -$line->returned_quantity, // Use returned_quantity
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise,
                ];
            })->toArray(),
        ];
    });

    return response()->json($orders->merge($returns)->toArray());
}





























// Nouvelles méthodes pour le module PurchaseNote avoirs
   public function notesList(Request $request)
{
    $query = PurchaseNote::with(['supplier', 'purchaseReturn', 'purchaseInvoice', 'lines.item'])->orderBy('updated_at', 'desc');

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    if ($request->filled('date_from')) {
        $query->whereDate('note_date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('note_date', '<=', $request->date_to);
    }

    $notes = $query->get();
    $suppliers = Supplier::orderBy('name')->get();
    $returns = PurchaseReturn::with('supplier')->where('invoiced', 0)->get(); // Only non-invoiced returns
    $invoices = PurchaseInvoice::with('supplier')->where('status', 'validée')->get(); // Only validated invoices

    return view('notes.list', compact('notes', 'suppliers', 'returns', 'invoices'));
}





public function createNoteFromReturn()
{
    $returns = PurchaseReturn::with(['lines.item', 'supplier'])
        ->where('invoiced', 0)
        ->get();
    $suppliers = Supplier::all();
    $tvaRates = Supplier::with('tvaGroup')
        ->get()
        ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
        ->toJson();

    return view('notes.create_from_return', compact('returns', 'suppliers', 'tvaRates'));
}



public function createNoteFromInvoice()
{
    $invoices = PurchaseInvoice::with(['lines.item', 'supplier'])
        ->where('status', 'validée')
        ->get();
    $suppliers = Supplier::all();
    $tvaRates = Supplier::with('tvaGroup')
        ->get()
        ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
        ->toJson();

    return view('notes.create_from_invoice', compact('invoices', 'suppliers', 'tvaRates'));
}

public function getReturnLines(Request $request)
{
    $returnIds = $request->input('return_ids', []);
    $lines = PurchaseReturnLine::with(['purchaseReturn.supplier'])
        ->whereIn('purchase_return_id', $returnIds)
        ->get()
        ->map(function ($line) {
            return [
                'purchase_return_id' => $line->purchase_return_id,
                'return_numdoc' => $line->purchaseReturn->numdoc,
                'article_code' => $line->article_code,
                'description' => $line->item ? $line->item->name : ($line->description ?? '-'),
                'returned_quantity' => $line->returned_quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'remise' => $line->remise ?? 0,
            ];
        });

    return response()->json(['lines' => $lines]);
}

public function getInvoiceLines(Request $request)
{
    $invoiceIds = $request->input('invoice_ids', []);
    $lines = PurchaseInvoiceLine::with(['purchaseInvoice.supplier'])
        ->whereIn('purchase_invoice_id', $invoiceIds)
        ->get()
        ->map(function ($line) {
            return [
                'purchase_invoice_id' => $line->purchase_invoice_id,
                'invoice_numdoc' => $line->purchaseInvoice->numdoc,
                'article_code' => $line->article_code,
                'description' => $line->item ? $line->item->name : ($line->description ?? '-'),
                'quantity' => $line->quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'remise' => $line->remise ?? 0,
            ];
        });

    return response()->json(['lines' => $lines]);
}

  


public function storeNote(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'note_date' => 'required|date',
            'type' => 'required|in:from_return,from_invoice',
            'tva_rate' => 'required|numeric|min:0',
            'lines' => 'required|array',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.quantity' => 'required|numeric',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.purchase_return_id' => 'nullable|exists:purchase_returns,id',
            'lines.*.purchase_invoice_id' => 'nullable|exists:purchase_invoices,id',
            'returns' => 'required_if:type,from_return|array|min:1',
            'returns.*' => 'exists:purchase_returns,id',
            'invoices' => 'required_if:type,from_invoice|array|min:1',
            'invoices.*' => 'exists:purchase_invoices,id',
        ], [
            'returns.required_if' => 'Au moins un retour doit être sélectionné pour un avoir de type retour.',
            'invoices.required_if' => 'Au moins une facture doit être sélectionnée pour un avoir de type facture.',
            'lines.*.quantity' => 'La quantité est requise et doit être un nombre.',
            'lines.*.article_code' => 'Le code article est invalide ou introuvable.',
        ]);

        // Fetch souche for credit note
        $souche = Souche::where('type', 'avoir_achat')->first();
        if (!$souche) {
            return back()->with('error', 'Souche avoir achat manquante.');
        }

        // Generate credit note number
        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        // Initialize variables
        $tvaRate = $request->tva_rate;
        $returns = $request->type === 'from_return' ? PurchaseReturn::whereIn('id', $request->returns ?? [])->with('lines')->get() : collect([]);
        $invoices = $request->type === 'from_invoice' ? PurchaseInvoice::whereIn('id', $request->invoices ?? [])->with('lines')->get() : collect([]);

        // Validate returns
        if ($request->type === 'from_return') {
            foreach ($returns as $return) {
                if ($return->invoiced) {
                    return back()->with('error', "Le retour #{$return->numdoc} est déjà facturé.");
                }
                if ($return->tva_rate != $tvaRate) {
                    return back()->with('error', "Le taux de TVA du retour #{$return->numdoc} ne correspond pas au taux fourni ($tvaRate%).");
                }
                if ($return->supplier_id != $request->supplier_id) {
                    return back()->with('error', "Le fournisseur du retour #{$return->numdoc} ne correspond pas au fournisseur sélectionné.");
                }
            }
        }

        // Validate invoices
        if ($request->type === 'from_invoice') {
            foreach ($invoices as $invoice) {
                if ($invoice->status !== 'validée') {
                    return back()->with('error', "La facture #{$invoice->numdoc} doit être validée.");
                }
                if ($invoice->tva_rate != $tvaRate) {
                    return back()->with('error', "Le taux de TVA de la facture #{$invoice->numdoc} ne correspond pas au taux fourni ($tvaRate%).");
                }
                if ($invoice->supplier_id != $request->supplier_id) {
                    return back()->with('error', "Le fournisseur de la facture #{$invoice->numdoc} ne correspond pas au fournisseur sélectionné.");
                }
            }
        }

        // Create the credit note
        $note = PurchaseNote::create([
            'supplier_id' => $request->supplier_id,
            'numdoc' => $numdoc,
            'note_date' => $request->note_date,
            'status' => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
            'total_ht' => 0,
            'total_ttc' => 0,
            'tva_rate' => $tvaRate,
            'notes' => $request->notes,
            'type' => $request->type,
            'purchase_return_id' => null,
            'purchase_invoice_id' => null,
        ]);

        $totalHt = 0;
        $createdReturns = collect([]);

        // Handle lines
        foreach ($request->lines as $index => $line) {
            // Ensure negative quantity for credit notes
            $quantity = $line['quantity'] < 0 ? $line['quantity'] : -$line['quantity'];
            $totalLigneHt = $quantity * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
            $totalHt += $totalLigneHt;

            // Validate line data against return/invoice
            if ($request->type === 'from_return' && $line['purchase_return_id']) {
                $return = $returns->firstWhere('id', $line['purchase_return_id']);
                if (!$return) {
                    $note->delete();
                    return back()->with('error', "Retour #{$line['purchase_return_id']} introuvable dans la sélection.");
                }
                $returnLine = $return->lines->firstWhere('article_code', $line['article_code']);
                if (!$returnLine) {
                    $note->delete();
                    return back()->with('error', "Article {$line['article_code']} non trouvé dans le retour #{$return->numdoc}.");
                }
                if (abs($quantity) > $returnLine->returned_quantity) {
                    $note->delete();
                    return back()->with('error', "Quantité pour l'article {$line['article_code']} dépasse la quantité retournée dans le retour #{$return->numdoc}.");
                }
            } elseif ($request->type === 'from_invoice' && $line['purchase_invoice_id']) {
                $invoice = $invoices->firstWhere('id', $line['purchase_invoice_id']);
                if (!$invoice) {
                    $note->delete();
                    return back()->with('error', "Facture #{$line['purchase_invoice_id']} introuvable dans la sélection.");
                }
                $invoiceLine = $invoice->lines->firstWhere('article_code', $line['article_code']);
                if (!$invoiceLine) {
                    $note->delete();
                    return back()->with('error', "Article {$line['article_code']} non trouvé dans la facture #{$invoice->numdoc}.");
                }
                if (abs($quantity) > $invoiceLine->quantity) {
                    $note->delete();
                    return back()->with('error', "Quantité pour l'article {$line['article_code']} dépasse la quantité facturée dans la facture #{$invoice->numdoc}.");
                }
            }

            // Create note line
            PurchaseNoteLine::create([
                'purchase_note_id' => $note->id,
                'article_code' => $line['article_code'],
                'purchase_return_id' => $line['purchase_return_id'] ?? null,
                'purchase_invoice_id' => $line['purchase_invoice_id'] ?? null,
                'quantity' => $quantity,
                'unit_price_ht' => $line['unit_price_ht'],
                'remise' => $line['remise'] ?? 0,
                'total_ligne_ht' => $totalLigneHt,
                'tva' => $tvaRate,
                'prix_ttc' => $totalLigneHt * (1 + $tvaRate / 100),
                'description' => $line['description'] ?? null,
            ]);

            // Handle automatic return creation and stock update for from_invoice type
            if ($request->type === 'from_invoice' && $request->input('action') === 'validate') {
                $invoice = $invoices->firstWhere('id', $line['purchase_invoice_id']);
                if ($invoice) {
                    $order = $invoice->orders()->first(); // Fetch related order, if any

                    // Create return if not already created for this invoice
                    $createdReturn = $createdReturns->firstWhere('purchase_invoice_id', $invoice->id);
                    if (!$createdReturn) {
                        $soucheReturn = Souche::where('type', 'retour_achat')->first();
                        if (!$soucheReturn) {
                            $note->delete();
                            return back()->with('error', 'Souche retour achat manquante.');
                        }
                        $nextReturnNumber = str_pad($soucheReturn->last_number + 1, $soucheReturn->number_length, '0', STR_PAD_LEFT);
                        $returnNumdoc = ($soucheReturn->prefix ?? '') . ($soucheReturn->suffix ?? '') . $nextReturnNumber;

                        $return = PurchaseReturn::create([
                            'purchase_order_id' => null, // Allow null
                            'supplier_id' => $request->supplier_id,
                            'numdoc' => $returnNumdoc,
                            'return_date' => $request->note_date,
                            'type' => 'partiel',
                            'total_ht' => 0,
                            'total_ttc' => 0,
                            'tva_rate' => $tvaRate,
                            'invoiced' => 1,
                            'notes' => 'Retour généré automatiquement pour avoir #' . $numdoc,
                        ]);

                        $soucheReturn->last_number += 1;
                        $soucheReturn->save();
                        $createdReturns->push(['id' => $return->id, 'purchase_invoice_id' => $invoice->id, 'return' => $return]);
                    } else {
                        $return = $createdReturn['return'];
                    }

                    // Create return line
                    PurchaseReturnLine::create([
                        'purchase_return_id' => $return->id,
                        'article_code' => $line['article_code'],
                        'returned_quantity' => -$quantity, // Positive for return
                        'unit_price_ht' => $line['unit_price_ht'],
                        'remise' => $line['remise'] ?? 0,
                        'total_ligne_ht' => -$totalLigneHt,
                    ]);

                    // Update stock and create stock movement
                    $item = Item::where('code', $line['article_code'])->first();
                    if ($item) {
                        $storeId = $order ? $order->store_id ?? 1 : 1;
                        $stock = Stock::firstOrNew([
                            'item_id' => $item->id,
                            'store_id' => $storeId,
                        ]);
                        $stock->quantity = ($stock->quantity ?? 0) + $quantity; // Negative quantity reduces stock
                        $stock->save();

                        $costPrice = $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                        StockMovement::create([
                            'item_id' => $item->id,
                            'store_id' => $storeId,
                            'type' => 'retour_achat',
                            'quantity' => $quantity,
                            'cost_price' => $costPrice,
                            'supplier_name' => $note->supplier->name,
                            'reason' => 'Retour achat généré pour avoir #' . $numdoc,
                            'reference' => $return->numdoc,
                        ]);
                    }
                }
            }
        }

        // Update note totals
        $note->update([
            'total_ht' => $totalHt,
            'total_ttc' => $totalHt * (1 + $tvaRate / 100),
        ]);

        // Update returns as invoiced for from_return type
        if ($request->type === 'from_return' && $request->input('action') === 'validate') {
            foreach ($returns as $return) {
                $return->invoiced = 1;
                $return->save();
            }
        }

        // Update created returns' totals for from_invoice type
        if ($request->type === 'from_invoice' && $createdReturns->isNotEmpty()) {
            foreach ($createdReturns as $createdReturn) {
                $return = $createdReturn['return'];
                $returnTotalHt = PurchaseReturnLine::where('purchase_return_id', $return->id)->sum('total_ligne_ht');
                $return->update([
                    'total_ht' => $returnTotalHt,
                    'total_ttc' => $returnTotalHt * (1 + $tvaRate / 100),
                ]);
            }
        }

        // Increment souche number
        $souche->last_number += 1;
        $souche->save();

        return redirect()->route('notes.list')->with('success', 'Avoir créé avec succès.');
    }



    // ffffffffffffffffffffff
public function storeReturnNote(Request $request)
{


// dd($request);
    // Validate the incoming request
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'note_date' => 'required|date',
        'tva_rate' => 'required|numeric|min:0',
        'lines' => 'required|array',
        'lines.*.article_code' => 'required|exists:items,code',
        'lines.*.quantity' => 'required|numeric',
        'lines.*.unit_price_ht' => 'required|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        'lines.*.purchase_return_id' => 'required|exists:purchase_returns,id',
        'returns' => 'required|array|min:1',
        'returns.*' => 'exists:purchase_returns,id',
    ], [
        'returns.required' => 'Au moins un retour doit être sélectionné.',
        'lines.*.quantity' => 'La quantité est requise et doit être un nombre.',
        'lines.*.article_code' => 'Le code article est invalide ou introuvable.',
        'lines.*.purchase_return_id' => 'L’ID du retour est requis pour chaque ligne.',
    ]);

    // Fetch souche for credit note
    $souche = Souche::where('type', 'avoir_achat')->first();
    if (!$souche) {
        return back()->with('error', 'Souche avoir achat manquante.');
    }

    // Generate credit note number
    $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
    $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

    // Fetch returns
    $returns = PurchaseReturn::whereIn('id', $request->returns)->with('lines')->get();
    $tvaRate = $request->tva_rate;

    // Validate returns
    foreach ($returns as $return) {
        if ($return->invoiced) {
            return back()->with('error', "Le retour #{$return->numdoc} est déjà facturé.");
        }
        if ($return->tva_rate != $tvaRate) {
            return back()->with('error', "Le taux de TVA du retour #{$return->numdoc} ne correspond pas au taux fourni ($tvaRate%).");
        }
        if ($return->supplier_id != $request->supplier_id) {
            return back()->with('error', "Le fournisseur du retour #{$return->numdoc} ne correspond pas au fournisseur sélectionné.");
        }
    }
    

    // Create the credit note
    $note = PurchaseNote::create([
        'supplier_id' => $request->supplier_id,
        'numdoc' => $numdoc,
        'note_date' => $request->note_date,
        'status' => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
        'total_ht' => 0,
        'total_ttc' => 0,
        'tva_rate' => $tvaRate,
        'notes' => $request->notes,
        'type' => 'from_return',
        'purchase_return_id' => null,
        'purchase_invoice_id' => null,
    ]);

    $totalHt = 0;

    // Handle lines
    foreach ($request->lines as $index => $line) {
        // Ensure negative quantity for credit notes
        $quantity = $line['quantity'] < 0 ? $line['quantity'] : -$line['quantity'];
        $totalLigneHt = $quantity * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
        $totalHt += $totalLigneHt;

        // Validate line against return
        $return = $returns->firstWhere('id', $line['purchase_return_id']);
        if (!$return) {
            $note->delete();
            return back()->with('error', "Retour #{$line['purchase_return_id']} introuvable dans la sélection.");
        }
        $returnLine = $return->lines->firstWhere('article_code', $line['article_code']);
        if (!$returnLine) {
            $note->delete();
            return back()->with('error', "Article {$line['article_code']} non trouvé dans le retour #{$return->numdoc}.");
        }
        if (abs($quantity) > abs($returnLine->returned_quantity)) {
            $note->delete();
            return back()->with('error', "Quantité pour l'article {$line['article_code']} dépasse la quantité retournée dans le retour #{$return->numdoc}.");
        }

        // Create note line
        PurchaseNoteLine::create([
            'purchase_note_id' => $note->id,
            'article_code' => $line['article_code'],
            'purchase_return_id' => $line['purchase_return_id'],
            'purchase_invoice_id' => null,
            'quantity' => $quantity,
            'unit_price_ht' => $line['unit_price_ht'],
            'remise' => $line['remise'] ?? 0,
            'total_ligne_ht' => $totalLigneHt,
            'tva' => $tvaRate,
            'prix_ttc' => $totalLigneHt * (1 + $tvaRate / 100),
            'description' => $line['description'] ?? null,
        ]);
    }

    // Update note totals
    $note->update([
        'total_ht' => $totalHt,
        'total_ttc' => $totalHt * (1 + $tvaRate / 100),
    ]);

    // Mark returns as invoiced if validated
    if ($request->input('action') === 'validate') {
        foreach ($returns as $return) {
            $return->invoiced = 1;
            $return->save();
        }
    }

    // Increment souche number
    $souche->last_number += 1;
    $souche->save();

    return redirect()->route('notes.list')->with('success', 'Avoir créé avec succès.');
}

public function storeInvoiceNote(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'note_date' => 'required|date',
        'tva_rate' => 'required|numeric|min:0',
        'lines' => 'required|array',
        'lines.*.article_code' => 'required|exists:items,code',
        'lines.*.quantity' => 'required|numeric',
        'lines.*.unit_price_ht' => 'required|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        'lines.*.purchase_invoice_id' => 'required|exists:purchase_invoices,id',
        'invoices' => 'required|array|min:1',
        'invoices.*' => 'exists:purchase_invoices,id',
    ], [
        'invoices.required' => 'Au moins une facture doit être sélectionnée.',
        'lines.*.quantity' => 'La quantité est requise et doit être un nombre.',
        'lines.*.article_code' => 'Le code article est invalide ou introuvable.',
        'lines.*.purchase_invoice_id' => 'L’ID de la facture est requis pour chaque ligne.',
    ]);

    // Fetch souche for credit note
    $souche = Souche::where('type', 'avoir_achat')->first();
    if (!$souche) {
        return back()->with('error', 'Souche avoir achat manquante.');
    }

    // Generate credit note number
    $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
    $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

    // Fetch invoices
    $invoices = PurchaseInvoice::whereIn('id', $request->invoices)->with('lines')->get();
    $tvaRate = $request->tva_rate;

    // Validate invoices
    foreach ($invoices as $invoice) {
        if ($invoice->status !== 'validée') {
            return back()->with('error', "La facture #{$invoice->numdoc} doit être validée.");
        }
        if ($invoice->tva_rate != $tvaRate) {
            return back()->with('error', "Le taux de TVA de la facture #{$invoice->numdoc} ne correspond pas au taux fourni ($tvaRate%).");
        }
        if ($invoice->supplier_id != $request->supplier_id) {
            return back()->with('error', "Le fournisseur de la facture #{$invoice->numdoc} ne correspond pas au fournisseur sélectionné.");
        }
    }

    // Create the credit note
    $note = PurchaseNote::create([
        'supplier_id' => $request->supplier_id,
        'numdoc' => $numdoc,
        'note_date' => $request->note_date,
        'status' => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
        'total_ht' => 0,
        'total_ttc' => 0,
        'tva_rate' => $tvaRate,
        'notes' => $request->notes,
        'type' => 'from_invoice',
        'purchase_return_id' => null,
        'purchase_invoice_id' => null,
    ]);

    $totalHt = 0;
    $createdReturns = collect([]);

    // Handle lines
    foreach ($request->lines as $index => $line) {
        // Ensure negative quantity for credit notes
        $quantity = $line['quantity'] < 0 ? $line['quantity'] : -$line['quantity'];
        $totalLigneHt = $quantity * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
        $totalHt += $totalLigneHt;

        // Validate line against invoice
        $invoice = $invoices->firstWhere('id', $line['purchase_invoice_id']);
        if (!$invoice) {
            $note->delete();
            return back()->with('error', "Facture #{$line['purchase_invoice_id']} introuvable dans la sélection.");
        }
        $invoiceLine = $invoice->lines->firstWhere('article_code', $line['article_code']);
        if (!$invoiceLine) {
            $note->delete();
            return back()->with('error', "Article {$line['article_code']} non trouvé dans la facture #{$invoice->numdoc}.");
        }
        if (abs($quantity) > $invoiceLine->quantity) {
            $note->delete();
            return back()->with('error', "Quantité pour l'article {$line['article_code']} dépasse la quantité facturée dans la facture #{$invoice->numdoc}.");
        }

        // Create note line
        PurchaseNoteLine::create([
            'purchase_note_id' => $note->id,
            'article_code' => $line['article_code'],
            'purchase_return_id' => null,
            'purchase_invoice_id' => $line['purchase_invoice_id'],
            'quantity' => $quantity,
            'unit_price_ht' => $line['unit_price_ht'],
            'remise' => $line['remise'] ?? 0,
            'total_ligne_ht' => $totalLigneHt,
            'tva' => $tvaRate,
            'prix_ttc' => $totalLigneHt * (1 + $tvaRate / 100),
            'description' => $line['description'] ?? null,
        ]);

        // Handle return creation and stock update for validated notes
        if ($request->input('action') === 'validate') {
            $createdReturn = $createdReturns->firstWhere('purchase_invoice_id', $invoice->id);
            if (!$createdReturn) {
                $soucheReturn = Souche::where('type', 'retour_achat')->first();
                if (!$soucheReturn) {
                    $note->delete();
                    return back()->with('error', 'Souche retour achat manquante.');
                }
                $nextReturnNumber = str_pad($soucheReturn->last_number + 1, $soucheReturn->number_length, '0', STR_PAD_LEFT);
                $returnNumdoc = ($soucheReturn->prefix ?? '') . ($soucheReturn->suffix ?? '') . $nextReturnNumber;

                $return = PurchaseReturn::create([
                    'purchase_order_id' => null,
                    'supplier_id' => $request->supplier_id,
                    'numdoc' => $returnNumdoc,
                    'return_date' => $request->note_date,
                    'type' => 'partiel',
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'tva_rate' => $tvaRate,
                    'invoiced' => 1,
                    'notes' => 'Retour généré automatiquement pour avoir #' . $numdoc,
                ]);

                $soucheReturn->last_number += 1;
                $soucheReturn->save();
                $createdReturns->push(['id' => $return->id, 'purchase_invoice_id' => $invoice->id, 'return' => $return]);
            } else {
                $return = $createdReturn['return'];
            }

            // Create return line
            PurchaseReturnLine::create([
                'purchase_return_id' => $return->id,
                'article_code' => $line['article_code'],
                'returned_quantity' => -$quantity, // Positive for return
                'unit_price_ht' => $line['unit_price_ht'],
                'remise' => $line['remise'] ?? 0,
                'total_ligne_ht' => -$totalLigneHt,
            ]);

            // Update stock
            $item = Item::where('code', $line['article_code'])->first();
            if ($item) {
                $storeId = $invoice->orders()->first()->store_id ?? 1;
                $stock = Stock::firstOrNew([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                ]);
                $stock->quantity = ($stock->quantity ?? 0) + $quantity; // Negative quantity reduces stock
                $stock->save();

                $costPrice = $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                StockMovement::create([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                    'type' => 'retour_achat',
                    'quantity' => $quantity,
                    'cost_price' => $costPrice,
                    'supplier_name' => $note->supplier->name,
                    'reason' => 'Retour achat généré pour avoir #' . $numdoc,
                    'reference' => $return->numdoc,
                ]);
            }
        }
    }

    // Update note totals
    $note->update([
        'total_ht' => $totalHt,
        'total_ttc' => $totalHt * (1 + $tvaRate / 100),
    ]);

    // Update created returns' totals
    foreach ($createdReturns as $createdReturn) {
        $return = $createdReturn['return'];
        $returnTotalHt = PurchaseReturnLine::where('purchase_return_id', $return->id)->sum('total_ligne_ht');
        $return->update([
            'total_ht' => $returnTotalHt,
            'total_ttc' => $returnTotalHt * (1 + $tvaRate / 100),
        ]);
    }

    // Increment souche number
    $souche->last_number += 1;
    $souche->save();



                                if ($request->action === 'validate') {
                                
// Update customer balance solde fournisseur
$supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $supplier->solde = ($supplier->solde ?? 0) + $totalTtc;
                    $supplier->save();
            }



    return redirect()->route('notes.list')->with('success', 'Avoir créé avec succès.');
}

    // fffffffffffffffffffffffffff







    public function editNote($id)
    {
        $note = PurchaseNote::with(['lines.item', 'supplier'])->findOrFail($id);
        if ($note->status === 'validée') {
            return back()->with('error', 'Impossible de modifier un avoir validé.');
        }
        $suppliers = Supplier::all();
        $tvaRates = Supplier::with('tvaGroup')
            ->get()
            ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
            ->toJson();
        return view('notes.edit', compact('note', 'suppliers', 'tvaRates'));
    }

    public function updateNote(Request $request, $numdoc)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'note_date' => 'required|date',
            'lines' => 'required|array',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.quantity' => 'required|numeric',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        ]);

        $note = PurchaseNote::where('numdoc', $numdoc)->firstOrFail();
        if ($note->status === 'validée') {
            return back()->with('error', 'Impossible de modifier un avoir validé.');
        }

        $tvaRate = $note->tva_rate;

        $note->update([
            'supplier_id' => $request->supplier_id,
            'note_date' => $request->note_date,
            'notes' => $request->notes,
        ]);

        $note->lines()->delete();
        $totalHt = 0;
        foreach ($request->lines as $line) {
            $quantity = $line['quantity'] < 0 ? $line['quantity'] : -$line['quantity'];
            $totalLigneHt = $quantity * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
            $totalHt += $totalLigneHt;

            PurchaseNoteLine::create([
                'purchase_note_id' => $note->id,
                'article_code' => $line['article_code'],
                'purchase_return_id' => $note->purchase_return_id,
                'purchase_invoice_id' => $note->purchase_invoice_id,
                'quantity' => $quantity,
                'unit_price_ht' => $line['unit_price_ht'],
                'remise' => $line['remise'] ?? 0,
                'total_ligne_ht' => $totalLigneHt,
                'tva' => $tvaRate,
                'prix_ttc' => $totalLigneHt * (1 + $tvaRate / 100),
                'description' => $line['description'] ?? null,
            ]);
        }

        $note->update([
            'total_ht' => $totalHt,
            'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            'status' => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
        ]);


                                    if ($request->action === 'validate') {
                                
// Update customer balance solde fournisseur
$supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
                     $totalTtc = $totalHt * (1 + $tvaRate / 100);
                    $supplier->solde = ($supplier->solde ?? 0) + $totalTtc;
                    $supplier->save();
            }

            

        return redirect()->route('notes.list')->with('success', 'Avoir mis à jour avec succès.');
    }

    public function exportSingleNote($id)
    {
        $note = PurchaseNote::with(['supplier', 'lines.item', 'purchaseReturn', 'purchaseInvoice'])->findOrFail($id);
        return Excel::download(new PurchaseNoteExport($note), "avoir_{$note->numdoc}.xlsx");
    }

    public function exportNotes(Request $request)
    {
        $filters = $request->only(['supplier_id', 'status', 'type', 'date_from', 'date_to']);
        return Excel::download(new PurchaseNotesExport($filters), 'avoirs_achat.xlsx');
    }

    public function printSingleNote($id)
    {
        $note = PurchaseNote::with(['supplier', 'lines.item', 'purchaseReturn', 'purchaseInvoice'])->findOrFail($id);
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

        $pdf = PDF::loadView('pdf.purchase_note', compact('note', 'company','barcode'));
        return $pdf->stream("avoir_{$note->numdoc}.pdf");
    }



    public function searchInvoices(Request $request)
{
    $supplierId = $request->input('supplier_id');
    $term = $request->input('term');

    $query = PurchaseInvoice::where('status', 'validée')
        ->with(['lines.item', 'supplier']);

    if ($supplierId) {
        $query->where('supplier_id', $supplierId);
    }
    if ($term) {
        $query->where('numdoc', 'like', '%' . $term . '%');
    }

    $invoices = $query->get()->map(function ($invoice) {
        return [
            'id' => $invoice->id,
            'numdoc' => $invoice->numdoc,
            'invoice_date' => $invoice->invoice_date,
            'supplier_name' => $invoice->supplier->name ?? 'N/A',
            'tva_rate' => $invoice->tva_rate ?? 0,
            'lines' => $invoice->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'quantity' => $line->quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise ?? 0,
                ];
            })->toArray(),
        ];
    });

    return response()->json($invoices->toArray());
}




public function searchReturns(Request $request)
{
    $supplierId = $request->input('supplier_id');
    $term = $request->input('term');

    $query = PurchaseReturn::where('invoiced', 0)
        ->with(['lines.item', 'supplier']);

    if ($supplierId) {
        $query->where('supplier_id', $supplierId);
    }
    if ($term) {
        $query->where(function ($q) use ($term) {
            $q->where('numdoc', 'like', '%' . $term . '%')
              ->orWhereHas('supplier', function ($q) use ($term) {
                  $q->where('name', 'like', '%' . $term . '%');
              });
        });
    }

    $returns = $query->get()->map(function ($return) {
        return [
            'id' => $return->id,
            'numdoc' => $return->numdoc,
            'return_date' => $return->return_date,
            'supplier_name' => $return->supplier->name ?? 'N/A',
            'tva_rate' => $return->tva_rate ?? 0,
            'lines' => $return->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'returned_quantity' => abs($line->returned_quantity), // Ensure positive for display
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise ?? 0,
                ];
            })->toArray(),
        ];
    });

    return response()->json($returns->toArray());
}






            public function markAsShipped(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $deliveryNote = PurchaseOrder::findOrFail($id);

            $deliveryNote->update(['status_livraison' => 'Reçu']);

            return redirect("/purchases/list")
                ->with('success', 'Bon de livraison validé avec succès.');
        });
    }








    // route joindre factures fournisseurs

    public function uploadSupplierInvoice(Request $request, $id)
    {
        $request->validate([
            'supplier_invoice_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max, allow PDF and images
        ]);

        $invoice = PurchaseInvoice::findOrFail($id);

        if ($invoice->supplier_invoice_file) {
            Storage::delete($invoice->supplier_invoice_file);
        }

        $file = $request->file('supplier_invoice_file');
        $fileName = 'supplier_invoices/purchase_invoice_' . $invoice->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public', $fileName);

        $invoice->update(['supplier_invoice_file' => $path]);

        Log::info('Supplier invoice uploaded', [
            'purchase_invoice_id' => $invoice->id,
            'file_path' => $path,
        ]);

        return redirect()->route('invoices.list')
            ->with('success', 'Facture fournisseur jointe avec succès.');
    }

    public function downloadSupplierInvoice($id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);

        if (!$invoice->supplier_invoice_file || !Storage::exists($invoice->supplier_invoice_file)) {
            Log::warning('Supplier invoice file not found', ['purchase_invoice_id' => $invoice->id]);
            return redirect()->route('invoices.list')
                ->with('error', 'Fichier de la facture fournisseur non trouvé.');
        }

        return Storage::download($invoice->supplier_invoice_file, 'facture_fournisseur_' . $invoice->numdoc . '.' . pathinfo($invoice->supplier_invoice_file, PATHINFO_EXTENSION));
    }

    public function deleteSupplierInvoice($id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);

        if ($invoice->supplier_invoice_file && Storage::exists($invoice->supplier_invoice_file)) {
            Storage::delete($invoice->supplier_invoice_file);
            $invoice->update(['supplier_invoice_file' => null]);

            Log::info('Supplier invoice deleted', ['purchase_invoice_id' => $invoice->id]);
        }

        return redirect()->route('invoices.list')
            ->with('success', 'Facture fournisseur supprimée avec succès.');
    }

    

    


    
}
