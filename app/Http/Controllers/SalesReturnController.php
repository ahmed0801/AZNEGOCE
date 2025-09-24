<?php

namespace App\Http\Controllers;

use App\Exports\SalesReturnExport;
use App\Exports\SalesReturnsExport;
use App\Models\CompanyInformation;
use App\Models\Item;
use App\Models\SalesReturn;
use App\Models\SalesReturnLine;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\DeliveryNote;
use App\Models\Customer;
use App\Models\Souche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use PDF;

class SalesReturnController extends Controller
{
    public function list(Request $request)
    {
        $query = SalesReturn::with(['customer', 'deliveryNote', 'lines.item'])->orderBy('updated_at', 'desc');

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

        $returns = $query->paginate(50);
        $customers = Customer::orderBy('name')->get();
        $deliveryNotes = DeliveryNote::orderBy('numdoc')->get();

        return view('sales_returns.list', compact('returns', 'customers', 'deliveryNotes'));
    }

    public function createFromDeliveryNote($id)
    {
        $deliveryNote = DeliveryNote::with(['lines.item', 'customer'])->findOrFail($id);
        $customers = Customer::all();

        return view('sales_returns.create_from_delivery_note', compact('deliveryNote', 'customers'));
    }

    public function storeFromDeliveryNote(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
            'type' => 'required|in:total,partiel',
            'lines' => 'required_if:type,partiel|array',
            'lines.*.selected' => 'nullable|boolean',
            'lines.*.article_code' => 'required_if:lines.*.selected,1|string|exists:items,code',
            'lines.*.returned_quantity' => 'required_if:lines.*.selected,1|numeric|min:0',
        ]);

        $deliveryNote = DeliveryNote::with(['lines.item', 'customer'])->findOrFail($id);

        $customer = $deliveryNote->customer;
        $tvaRate = $deliveryNote->tva_rate ?? 0;

        // Récupérer la souche pour les retours de vente
        $souche = Souche::where('type', 'retour_vente')->first();
        if (!$souche) {
            return back()->with('error', 'Souche retour vente manquante');
        }

        // Calcul du numéro de document
        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        // Créer le retour
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
        if ($request->type === 'total') {
            // Retour total : reprendre toutes les lignes du BL
            foreach ($deliveryNote->lines as $deliveryLine) {
                $returnedQuantity = $deliveryLine->delivered_quantity;
                $unitPriceHt = $deliveryLine->unit_price_ht;
                $remise = $deliveryLine->remise;
                $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
                $totalHt += $totalLigneHt;

                SalesReturnLine::create([
                    'sales_return_id' => $return->id,
                    'article_code' => $deliveryLine->article_code,
                    'returned_quantity' => $returnedQuantity,
                    'unit_price_ht' => $unitPriceHt,
                    'remise' => $remise,
                    'total_ligne_ht' => $totalLigneHt,
                ]);

                // Mise à jour du stock
                $item = Item::where('code', $deliveryLine->article_code)->first();
                if ($item) {
                    $storeId = $deliveryNote->store_id ?? 1;
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

                $deliveryLine = $deliveryNote->lines->where('article_code', $articleCode)->first();
                if (!$deliveryLine) {
                    return back()->with('error', 'Article invalide : ' . $articleCode);
                }
                if ($line['returned_quantity'] > $deliveryLine->delivered_quantity) {
                    return back()->with('error', 'Quantité retournée invalide pour ' . $articleCode . ' (max: ' . $deliveryLine->delivered_quantity . ')');
                }

                $returnedQuantity = $line['returned_quantity'];
                $unitPriceHt = $deliveryLine->unit_price_ht;
                $remise = $deliveryLine->remise;
                $totalLigneHt = $returnedQuantity * $unitPriceHt * (1 - $remise / 100);
                $totalHt += $totalLigneHt;

                SalesReturnLine::create([
                    'sales_return_id' => $return->id,
                    'article_code' => $articleCode,
                    'returned_quantity' => $returnedQuantity,
                    'unit_price_ht' => $unitPriceHt,
                    'remise' => $remise,
                    'total_ligne_ht' => $totalLigneHt,
                ]);

                // Mise à jour du stock
                $item = Item::where('code', $articleCode)->first();
                if ($item) {
                    $storeId = $deliveryNote->store_id ?? 1;
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
                }
            }

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

        return redirect()->route('sales_returns.list')->with('success', 'Retour de vente créé avec succès.');
    }

    public function exportSingle($id)
    {
        $return = SalesReturn::findOrFail($id);
        return Excel::download(new SalesReturnExport($return), 'retour_vente_' . $return->numdoc . '.xlsx');
    }

    public function printSingle($id)
    {
        $return = SalesReturn::with(['customer', 'lines.item'])->findOrFail($id);
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
            $generator->getBarcode($return->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = PDF::loadView('pdf.sales_return', compact('return', 'company', 'barcode'));
        return $pdf->stream("retour_vente_{$return->numdoc}.pdf");
    }

    public function export(Request $request)
    {
        $filters = $request->only(['customer_id', 'delivery_note_id', 'type', 'date_from', 'date_to']);
        return Excel::download(new SalesReturnsExport($filters), 'retours_vente.xlsx');
    }
}