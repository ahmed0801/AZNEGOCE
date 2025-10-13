<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Customer;
use App\Models\CompanyInformation;
use App\Exports\DeliveryNoteExport;
use App\Exports\SalesReturnExport;
use App\Models\DeliveryNoteLine;
use App\Models\Item;
use App\Models\SalesReturn;
use App\Models\SalesReturnLine;
use App\Models\Souche;
use App\Models\Stock;
use App\Models\StockMovement;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryNotesController extends Controller
{
    public function list(Request $request)
    {
$query = DeliveryNote::with(['customer', 'salesOrder', 'lines.item','vehicle'])
    ->orderBy('delivery_notes.updated_at', 'desc');

        if ($request->filled('numclient')) {
            $query->where('delivery_notes.numclient', $request->numclient);
        }

        if ($request->filled('status')) {
            $query->where('delivery_notes.status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('delivery_notes.delivery_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('delivery_notes.delivery_date', '<=', $request->date_to);
        }

        $deliveryNotes = $query->paginate(50); // Pagination au lieu de get()
        $customers = Customer::orderBy('name')->get();

        return view('delivery_notes.list', compact('deliveryNotes', 'customers'));
    }

 public function exportSingle($id)
    {
        $deliveryNote = DeliveryNote::with(['salesOrder', 'lines.item'])
            ->leftJoin('customers', 'delivery_notes.numclient', '=', 'customers.code')
            ->select('delivery_notes.*', 'customers.name as customer_name')
            ->where('delivery_notes.id', $id)
            ->firstOrFail();

        return Excel::download(new DeliveryNoteExport($deliveryNote), 'bon_livraison_' . $deliveryNote->numdoc . '.xlsx');
    }

   public function printSingle($id)
    {
        $deliveryNote = DeliveryNote::with(['salesOrder', 'lines.item','vehicle'])
            ->leftJoin('customers', 'delivery_notes.numclient', '=', 'customers.code')
            ->select('delivery_notes.*', 'customers.name as customer_name')
            ->where('delivery_notes.id', $id)
            ->firstOrFail();

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
            $generator->getBarcode($deliveryNote->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.delivery_note', compact('deliveryNote', 'company', 'barcode'));
        return $pdf->stream("bon_livraison_{$deliveryNote->numdoc}.pdf");
    }


 public function edit($id)
    {
        $deliveryNote = DeliveryNote::with('lines', 'customer')->findOrFail($id);
        // dd($deliveryNote);
        $customers = Customer::with('tvaGroup')->get();
        $tvaRates = $customers->mapWithKeys(fn($c) => [$c->id => $c->tvaGroup->rate ?? 0])->toJson();

        return view('delivery_notes.edit', compact('deliveryNote', 'customers', 'tvaRates'));
    }

    /**
     * Update the specified delivery note.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'delivery_date' => 'required|date',
            'lines' => 'required|array',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.delivered_quantity' => 'required|numeric|min:0',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
            $tvaRate = $customer->tvaGroup->rate ?? 0;
            $deliveryNote = DeliveryNote::with('lines')->findOrFail($id);

            // Store original quantities for stock adjustment
            $originalQuantities = [];
            foreach ($deliveryNote->lines as $line) {
                $originalQuantities[$line->article_code] = $line->delivered_quantity;
            }

            $deliveryNote->update([
                'customer_id' => $request->customer_id,
                'numclient' => $customer->code,
                'delivery_date' => $request->delivery_date,
                'notes' => $request->notes,
                'tva_rate' => $tvaRate,
            ]);

            $deliveryNote->lines()->delete();
            $totalDelivered = 0;
            $totalHt = 0;

            foreach ($request->lines as $line) {
                $item = Item::where('code', $line['article_code'])->first();
                if (!$item) {
                    throw new \Exception("Article {$line['article_code']} introuvable.");
                }
                if ($request->input('action') === 'validate' && $item->stock_quantity < $line['delivered_quantity']) {
                    // throw new \Exception("Stock insuffisant pour l'article {$line['article_code']}.");
                }

                $totalLigneHt = $line['delivered_quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $unitPriceTtc = $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100) * (1 + $tvaRate / 100);
                $totalLigneTtc = $totalLigneHt * (1 + $tvaRate / 100);

                DeliveryNoteLine::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'article_code' => $line['article_code'],
                    'delivered_quantity' => $line['delivered_quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'unit_price_ttc' => $unitPriceTtc,
                    'remise' => $line['remise'] ?? 0,
                    'total_ligne_ht' => $totalLigneHt,
                    'total_ligne_ttc' => $totalLigneTtc,
                ]);

                $totalDelivered += $line['delivered_quantity'];
                $totalHt += $totalLigneHt;

                if ($request->input('action') === 'validate') {
                    $storeId = $deliveryNote->store_id ?? 1;
                    $stock = Stock::firstOrNew([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                    ]);

                    // Calculate quantity difference
                    $originalQuantity = $originalQuantities[$line['article_code']] ?? 0;
                    $quantityDifference = $originalQuantity - $line['delivered_quantity'];
                    $stock->quantity = ($stock->quantity ?? 0) + $quantityDifference;
                    $stock->save();

                    $costPrice = $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);

                    // Check for existing stock movement
                    $stockMovement = StockMovement::where([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                        'reference' => $deliveryNote->numdoc,
                    ])->first();

                    if ($stockMovement) {
                        // Update existing stock movement
                        $stockMovement->update([
                            'quantity' => -$line['delivered_quantity'],
                            'cost_price' => $costPrice,
                            'supplier_name' => $customer->name,
                            'reason' => 'Validation BL #' . $deliveryNote->numdoc,
                        ]);
                    } else {
                        // Create new stock movement
                        StockMovement::create([
                            'item_id' => $item->id,
                            'store_id' => $storeId,
                            'type' => 'vente',
                            'quantity' => -$line['delivered_quantity'],
                            'cost_price' => $costPrice,
                            'supplier_name' => $customer->name,
                            'reason' => 'Validation BL #' . $deliveryNote->numdoc,
                            'reference' => $deliveryNote->numdoc,
                        ]);
                    }
                }
            }

            $deliveryNote->update([
                'total_delivered' => $totalDelivered,
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
                'status' => $request->input('action') === 'validate' ? 'expédié' : 'en_cours',
            ]);

            return redirect()->route('delivery_notes.list')
                ->with('success', $request->input('action') === 'validate'
                    ? 'Bon de livraison validé et mis à jour avec succès.'
                    : 'Bon de livraison mis à jour avec succès.');
        });
    }



        public function markAsValidated(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $deliveryNote = DeliveryNote::findOrFail($id);

            if ($deliveryNote->status === 'expédié') {
                return redirect()->route('delivery_notes.list')
                    ->with('error', 'Ce bon de livraison est déjà validé.');
            }

            if ($deliveryNote->status === 'annulé') {
                return redirect()->route('delivery_notes.list')
                    ->with('error', 'Ce bon de livraison est annulé et ne peut pas être validé.');
            }

            $deliveryNote->update(['status' => 'expédié']);

            return redirect()->route('delivery_notes.list')
                ->with('success', 'Bon de livraison validé avec succès.');
        });
    }



            public function markAsShipped(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $deliveryNote = DeliveryNote::findOrFail($id);

            $deliveryNote->update(['status_livraison' => 'livré',
        'status' => 'expédié']);

            return redirect()->route('delivery_notes.list')
                ->with('success', 'Bon de livraison validé avec succès.');
        });
    }



    
    
    public function cancel(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $deliveryNote = DeliveryNote::with('lines', 'customer')->findOrFail($id);

            if ($deliveryNote->status !== 'en_cours') {
                return redirect()->route('delivery_notes.list')
                    ->with('error', 'Seuls les bons de livraison en cours peuvent être annulés.');
            }

            foreach ($deliveryNote->lines as $line) {
                $item = Item::where('code', $line->article_code)->first();
                if (!$item) {
                    throw new \Exception("Article {$line->article_code} introuvable.");
                }

                $storeId = $deliveryNote->store_id ?? 1;
                $stock = Stock::firstOrNew([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                ]);

                // Restore the stock by adding back the delivered quantity
                $stock->quantity = ($stock->quantity ?? 0) + $line->delivered_quantity;
                $stock->save();

// Create a new stock movement for cancellation
                $costPrice = $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
                StockMovement::create([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                    'type' => 'annulation_expedition',
                    'quantity' => $line->delivered_quantity,
                    'cost_price' => $costPrice,
                    'supplier_name' => $deliveryNote->customer->name,
                    'reason' => 'Annulation BL #' . $deliveryNote->numdoc,
                    'reference' => $deliveryNote->numdoc,
                ]);
            }

            $deliveryNote->update(['status' => 'annulé']);

            return redirect()->route('delivery_notes.list')
                ->with('success', 'Bon de livraison annulé avec succès.');
        });
    }

    





// imprimer bordereau d envoi

public function shippingNote(Request $request, $id)
{
    $deliveryNote = DeliveryNote::with(['salesOrder', 'lines.item'])
        ->leftJoin('customers', 'delivery_notes.numclient', '=', 'customers.code')
        ->select('delivery_notes.*', 'customers.name as customer_name', 'customers.address')
        ->where('delivery_notes.id', $id)
        ->firstOrFail();

    // Vérifiez que le statut est "expédié"
    if ($deliveryNote->status !== 'expédié') {
        return redirect()->route('delivery_notes.list')->with('error', 'Le bordereau d\'envoi ne peut être généré que pour les bons de livraison expédiés.');
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
        $generator->getBarcode($deliveryNote->numdoc, $generator::TYPE_CODE_128)
    );

    // Récupérer le commentaire depuis la requête
    $comment = $request->input('comment');

    $pdf = Pdf::loadView('pdf.shipping_note', compact('deliveryNote', 'company', 'barcode', 'comment'));
    return $pdf->stream("bordereau_envoi_{$deliveryNote->numdoc}.pdf");
}










     public function createReturn($id)
    {
        $deliveryNote = DeliveryNote::with(['lines.item', 'customer', 'salesOrder'])->findOrFail($id);
        if ($deliveryNote->status !== 'expédié' && $deliveryNote->status !== 'livré') {
            return back()->with('error', 'Seuls les bons de livraison expédiés ou livrés peuvent être retournés.');
        }
        $customers = Customer::orderBy('name')->get();
        return view('sales_returns.create', compact('deliveryNote', 'customers'));
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

        return DB::transaction(function () use ($request, $id) {
            $deliveryNote = DeliveryNote::with(['lines.item', 'customer'])->findOrFail($id);
            $customer = $deliveryNote->customer;
            $tvaRate = $deliveryNote->tva_rate ?? 0;

            $souche = Souche::where('type', 'retour_vente')->lockForUpdate()->first();
            if (!$souche) {
                throw new \Exception('Souche retour vente manquante');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            $return = SalesReturn::create([
                'delivery_note_id' => $deliveryNote->id,
                'customer_id' => $customer->id,
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
            $hasSelectedLines = false;

            if ($request->type === 'total') {
                foreach ($deliveryNote->lines as $deliveryLine) {
                    $totalReturned = SalesReturnLine::where('article_code', $deliveryLine->article_code)
                        ->whereHas('salesReturn', fn($q) => $q->where('delivery_note_id', $deliveryNote->id))
                        ->sum('returned_quantity');
                    $maxReturnable = $deliveryLine->delivered_quantity - $totalReturned;

                    if ($maxReturnable <= 0) {
                        throw new \Exception('Aucune quantité disponible pour retourner l\'article ' . $deliveryLine->article_code);
                    }

                    $returnedQuantity = $maxReturnable;
                    $unitPriceHt = $deliveryLine->unit_price_ht;
                    $remise = $deliveryLine->remise;
                    $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
                    $totalHt += $totalLigneHt;

                    $item = Item::where('code', $deliveryLine->article_code)->first();
                    if (!$item) {
                        throw new \Exception('Article introuvable : ' . $deliveryLine->article_code);
                    }

                    $storeId = $deliveryNote->salesOrder->store_id ?? 1;
                    $stock = Stock::firstOrNew([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) + $returnedQuantity;
                    $stock->save();

                    $costPrice = $unitPriceHt * (1 - $remise / 100);
                    StockMovement::create([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                        'type' => 'retour_vente',
                        'quantity' => $returnedQuantity,
                        'cost_price' => $costPrice,
                        'supplier_name' => $customer->name,
                        'reason' => 'Retour vente #' . $numdoc,
                        'reference' => $numdoc,
                    ]);

                    SalesReturnLine::create([
                        'sales_return_id' => $return->id,
                        'article_code' => $deliveryLine->article_code,
                        'returned_quantity' => $returnedQuantity,
                        'unit_price_ht' => $unitPriceHt,
                        'remise' => $remise,
                        'total_ligne_ht' => $totalLigneHt,
                    ]);
                }
            } else {
                foreach ($request->lines as $articleCode => $line) {
                    if (!isset($line['selected']) || $line['selected'] != 1) {
                        continue;
                    }
                    $hasSelectedLines = true;

                    $deliveryLine = $deliveryNote->lines->where('article_code', $articleCode)->first();
                    if (!$deliveryLine) {
                        throw new \Exception('Article invalide : ' . $articleCode);
                    }

                    $totalReturned = SalesReturnLine::where('article_code', $articleCode)
                        ->whereHas('salesReturn', fn($q) => $q->where('delivery_note_id', $deliveryNote->id))
                        ->sum('returned_quantity');
                    $maxReturnable = $deliveryLine->delivered_quantity - $totalReturned;

                    if ($line['returned_quantity'] > $maxReturnable) {
                        throw new \Exception('Quantité retournée invalide pour ' . $articleCode . ' (max: ' . $maxReturnable . ')');
                    }

                    $returnedQuantity = $line['returned_quantity'];
                    $unitPriceHt = $deliveryLine->unit_price_ht;
                    $remise = $deliveryLine->remise;
                    $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
                    $totalHt += $totalLigneHt;

                    $item = Item::where('code', $articleCode)->first();
                    if (!$item) {
                        throw new \Exception('Article introuvable : ' . $articleCode);
                    }

                    $storeId = $deliveryNote->salesOrder->store_id ?? 1;
                    $stock = Stock::firstOrNew([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) + $returnedQuantity;
                    $stock->save();

                    $costPrice = $unitPriceHt * (1 - $remise / 100);
                    StockMovement::create([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                        'type' => 'retour_vente',
                        'quantity' => $returnedQuantity,
                        'cost_price' => $costPrice,
                        'supplier_name' => $customer->name,
                        'reason' => 'Retour vente #' . $numdoc,
                        'reference' => $numdoc,
                    ]);

                    SalesReturnLine::create([
                        'sales_return_id' => $return->id,
                        'article_code' => $articleCode,
                        'returned_quantity' => $returnedQuantity,
                        'unit_price_ht' => $unitPriceHt,
                        'remise' => $remise,
                        'total_ligne_ht' => $totalLigneHt,
                    ]);
                }

                if (!$hasSelectedLines) {
                    $return->delete();
                    throw new \Exception('Veuillez sélectionner au moins un article pour un retour partiel.');
                }
            }

            $return->update([
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);

            $souche->last_number += 1;
            $souche->save();

            return redirect()->route('delivery_notes.salesreturns.list')->with('success', 'Retour de vente créé avec succès.');
        });
    }

    public function returnsList(Request $request)
{
    $query = SalesReturn::with(['deliveryNote.salesOrder', 'customer', 'lines.item'])->orderBy('updated_at', 'desc');

    if ($request->filled('customer_id')) {
        $query->where('customer_id', $request->customer_id);
    }
    if ($request->filled('delivery_note_id')) {
        $query->where('delivery_note_id', $request->delivery_note_id);
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

    $returns = $query->paginate(10);
    $customers = Customer::orderBy('name')->get();
    // Update 'salesReturn' to 'salesReturns'
    $deliveryNotes = DeliveryNote::whereHas('salesReturns')->get();

    return view('sales_returns.list', compact('returns', 'customers', 'deliveryNotes'));
}


    
    public function showReturn($id)
    {
        $return = SalesReturn::with(['deliveryNote.salesOrder', 'customer', 'lines.item'])->findOrFail($id);
        return view('sales_returns.show', compact('return'));
    }



    

    public function exportSingleReturn($id)
    {
        $return = SalesReturn::with(['deliveryNote.salesOrder', 'customer', 'lines.item'])->findOrFail($id);
        return Excel::download(new SalesReturnExport($return), 'retour_vente_' . $return->numdoc . '.xlsx');
    }

    public function printSingleReturn($id)
    {
        $return = SalesReturn::with(['deliveryNote.salesOrder', 'customer', 'lines.item'])->findOrFail($id);
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

        if (!file_exists(public_path($company->logo_path))) {
            $company->logo_path = 'assets/img/default_logo.png';
        }

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($return->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.sales_return', compact('return', 'company', 'barcode'));
        return $pdf->stream("retour_vente_{$return->numdoc}.pdf");
    }






    public function editReturn($id)
    {
        $return = SalesReturn::with(['deliveryNote.lines.item', 'customer'])->findOrFail($id);
        if ($return->invoiced) {
            return back()->with('error', 'Ce retour est déjà facturé et ne peut pas être modifié.');
        }
        $customers = Customer::orderBy('name')->get();
        return view('sales_returns.edit', compact('return', 'customers'));
    }

    public function updateReturn(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
            'type' => 'required|in:total,partiel',
            'lines' => 'required_if:type,partiel|array',
            'lines.*.selected' => 'nullable|boolean',
            'lines.*.article_code' => 'required_if:lines.*.selected,1|string|exists:items,code',
            'lines.*.returned_quantity' => 'required_if:lines.*.selected,1|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $return = SalesReturn::with(['deliveryNote.lines.item', 'customer', 'lines'])->findOrFail($id);
            if ($return->invoiced) {
                throw new \Exception('Ce retour est déjà facturé et ne peut pas être modifié.');
            }

            $deliveryNote = $return->deliveryNote;
            $customer = $return->customer;
            $tvaRate = $return->tva_rate;

            // Supprimer les anciennes lignes et mouvements de stock
            foreach ($return->lines as $line) {
                $item = Item::where('code', $line->article_code)->first();
                $storeId = $deliveryNote->salesOrder->store_id ?? 1;
                $stock = Stock::firstOrNew([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                ]);
                $stock->quantity -= $line->returned_quantity; // Annuler l'impact des anciennes quantités
                $stock->save();

                StockMovement::create([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                    'type' => 'annulation_retour_vente',
                    'quantity' => -$line->returned_quantity,
                    'cost_price' => $line->unit_price_ht * (1 - $line->remise / 100),
                    'supplier_name' => $customer->name,
                    'reason' => 'Annulation retour vente #' . $return->numdoc,
                    'reference' => $return->numdoc,
                ]);
            }
            $return->lines()->delete();

            // Créer les nouvelles lignes
            $totalHt = 0;
            $hasSelectedLines = false;

            if ($request->type === 'total') {
                foreach ($deliveryNote->lines as $deliveryLine) {
                    $totalReturned = SalesReturnLine::where('article_code', $deliveryLine->article_code)
                        ->whereHas('salesReturn', fn($q) => $q->where('delivery_note_id', $deliveryNote->id)->where('id', '!=', $return->id))
                        ->sum('returned_quantity');
                    $maxReturnable = $deliveryLine->delivered_quantity - $totalReturned;

                    if ($maxReturnable <= 0) {
                        throw new \Exception('Aucune quantité disponible pour retourner l\'article ' . $deliveryLine->article_code);
                    }

                    $returnedQuantity = $maxReturnable;
                    $unitPriceHt = $deliveryLine->unit_price_ht;
                    $remise = $deliveryLine->remise;
                    $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
                    $totalHt += $totalLigneHt;

                    $item = Item::where('code', $deliveryLine->article_code)->first();
                    $storeId = $deliveryNote->salesOrder->store_id ?? 1;
                    $stock = Stock::firstOrNew([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) + $returnedQuantity;
                    $stock->save();

                    StockMovement::create([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                        'type' => 'retour_vente',
                        'quantity' => $returnedQuantity,
                        'cost_price' => $unitPriceHt * (1 - $remise / 100),
                        'supplier_name' => $customer->name,
                        'reason' => 'Retour vente #' . $return->numdoc,
                        'reference' => $return->numdoc,
                    ]);

                    SalesReturnLine::create([
                        'sales_return_id' => $return->id,
                        'article_code' => $deliveryLine->article_code,
                        'returned_quantity' => $returnedQuantity,
                        'unit_price_ht' => $unitPriceHt,
                        'remise' => $remise,
                        'total_ligne_ht' => $totalLigneHt,
                    ]);
                }
            } else {
                foreach ($request->lines as $articleCode => $line) {
                    if (!isset($line['selected']) || $line['selected'] != 1) {
                        continue;
                    }
                    $hasSelectedLines = true;

                    $deliveryLine = $deliveryNote->lines->where('article_code', $articleCode)->first();
                    if (!$deliveryLine) {
                        throw new \Exception('Article invalide : ' . $articleCode);
                    }

                    $totalReturned = SalesReturnLine::where('article_code', $articleCode)
                        ->whereHas('salesReturn', fn($q) => $q->where('delivery_note_id', $deliveryNote->id)->where('id', '!=', $return->id))
                        ->sum('returned_quantity');
                    $maxReturnable = $deliveryLine->delivered_quantity - $totalReturned;

                    if ($line['returned_quantity'] > $maxReturnable) {
                        throw new \Exception('Quantité retournée invalide pour ' . $articleCode . ' (max: ' . $maxReturnable . ')');
                    }

                    $returnedQuantity = $line['returned_quantity'];
                    $unitPriceHt = $deliveryLine->unit_price_ht;
                    $remise = $deliveryLine->remise;
                    $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
                    $totalHt += $totalLigneHt;

                    $item = Item::where('code', $articleCode)->first();
                    $storeId = $deliveryNote->salesOrder->store_id ?? 1;
                    $stock = Stock::firstOrNew([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) + $returnedQuantity;
                    $stock->save();

                    StockMovement::create([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                        'type' => 'retour_vente',
                        'quantity' => $returnedQuantity,
                        'cost_price' => $unitPriceHt * (1 - $remise / 100),
                        'supplier_name' => $customer->name,
                        'reason' => 'Retour vente #' . $return->numdoc,
                        'reference' => $return->numdoc,
                    ]);

                    SalesReturnLine::create([
                        'sales_return_id' => $return->id,
                        'article_code' => $articleCode,
                        'returned_quantity' => $returnedQuantity,
                        'unit_price_ht' => $unitPriceHt,
                        'remise' => $remise,
                        'total_ligne_ht' => $totalLigneHt,
                    ]);
                }

                if (!$hasSelectedLines) {
                    throw new \Exception('Veuillez sélectionner au moins un article pour un retour partiel.');
                }
            }

            $return->update([
                'return_date' => $request->return_date,
                'type' => $request->type,
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
                'notes' => $request->notes,
            ]);

            return redirect()->route('delivery_notes.returns.show', $return->id)->with('success', 'Retour de vente mis à jour avec succès.');
        });
    }









    // tv client
    public function tvClient()
    {
        $today = Carbon::today()->toDateString();
        $deliveryNotes = DeliveryNote::with(['customer'])
            ->whereIn('status', ['en_cours', 'expédié'])
            ->whereDate('delivery_date', $today)
            ->orderBy('updated_at', 'desc')
            ->get();

        $lastDeliveryNotes = $deliveryNotes->map(function ($note) {
            return [
                'id' => $note->id,
                'numdoc' => $note->numdoc,
                'customer_name' => isset($note->customer) ? $note->customer->name ?? 'Client inconnu' : 'Client inconnu',
                'vendeur' => $note->vendeur ?? 'Non assigné',
                'status' => $note->status,
                'updated_at' => $note->updated_at->toIso8601String(),
            ];
        })->toArray();

        return view('tvclient', compact('deliveryNotes', 'lastDeliveryNotes'));
    }

    public function tvClientData(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $deliveryNotes = DeliveryNote::with(['customer'])
            ->whereIn('status', ['en_cours', 'expédié'])
            ->whereDate('delivery_date', $today)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'deliveryNotes' => $deliveryNotes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'numdoc' => $note->numdoc,
                    'customer_name' => isset($note->customer) ? $note->customer->name ?? 'Client inconnu' : 'Client inconnu',
                    'vendeur' => $note->vendeur ?? 'Non assigné',
                    'status' => $note->status,
                    'updated_at' => $note->updated_at->toIso8601String(),
                ];
            })->toArray(),
            'current_time' => Carbon::now('Europe/Paris')->format('d/m/Y H:i:s'),
        ]);
    }

    
}