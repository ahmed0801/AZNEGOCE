<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Exports\SalesInvoiceExport;
use App\Exports\SalesOrderExport;
use App\Models\CompanyInformation;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceLine;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteLine;
use App\Models\Souche;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use PDF;

class SalesController extends Controller
{
    /**
     * List all sales orders with filtering capabilities.
     */
    public function list(Request $request)
    {
        $query = SalesOrder::with(['customer', 'lines.item', 'deliveryNote'])->orderBy('updated_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        if ($request->filled('delivery_status')) {
            $query->whereHas('deliveryNote', function ($q) use ($request) {
                $q->where('status', $request->delivery_status);
            });
        }

        $sales = $query->get();
        $customers = Customer::orderBy('name')->get();

        return view('sales.list', compact('sales', 'customers'));
    }

    /**
     * Show the form to create a new sales order.
     */
  public function index()
    {
        $customers = Customer::with('tvaGroup')->get();
        $tvaRates = $customers->mapWithKeys(function ($c) {
            return [$c->id => is_numeric($c->tvaGroup->rate ?? 0) ? (float)$c->tvaGroup->rate : 0];
        })->toJson();

        return view('sales.create', compact('customers', 'tvaRates'));
    }

    public function stockDetails(Request $request)
    {
        $code = $request->query('code');
        // Use fully qualified Log to avoid conflicts
        \Illuminate\Support\Facades\Log::info('Fetching stock details for item code:', ['code' => $code]);

        try {
            $item = Item::where('code', $code)
                        ->with(['stocks.store', 'stockMovements' => function ($query) {
                            $query->latest()->limit(20);
                        }])
                        ->first();

            if (!$item) {
                \Illuminate\Support\Facades\Log::warning('Item not found for code:', ['code' => $code]);
                return response()->json(['error' => 'Article not found'], 404);
            }

            $stocks = $item->stocks->map(function ($stock) {
                return [
                    'store_name' => optional($stock->store)->name ?? '-',
                    'quantity' => (int) ($stock->quantity ?? 0),
                    'updated_at' => $stock->updated_at ? $stock->updated_at->format('d/m/Y H:i') : '-',
                ];
            });

            $movements = $item->stockMovements->map(function ($movement) {
                return [
                    'type' => ucfirst($movement->type ?? 'unknown'),
                    'quantity' => (int) ($movement->quantity ?? 0),
                    'store_name' => optional($movement->store)->name ?? '-',
                    'cost_price' => is_numeric($movement->cost_price) ? (float) $movement->cost_price : null,
                    'supplier_name' => $movement->supplier_name ?? '-',
                    'reference' => $movement->reference ?? '-',
                    'note' => $movement->note ?? '-',
                    'created_at' => $movement->created_at ? $movement->created_at->format('d/m/Y H:i') : '-',
                ];
            });

            $response = [
                'stocks' => $stocks->toArray(),
                'movements' => $movements->toArray(),
            ];

            \Illuminate\Support\Facades\Log::info('Stock details response:', [
                'stocks_count' => $stocks->count(),
                'movements_count' => $movements->count(),
                'stocks' => $response['stocks'],
                'movements' => $response['movements'],
            ]);

            return response()->json($response);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching stock details:', [
                'code' => $code,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error while fetching stock details'], 500);
        }
    }




    /**
     * Store a new sales order.
     */
    /**
 * Store a new sales order.
 */


    /**
 * Store a new sales order.
 */


    /**
 * Store a new sales order.
 */
public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'order_date' => 'required|date',
        'lines' => 'required|array',
        'lines.*.article_code' => 'required|exists:items,code',
        'lines.*.ordered_quantity' => 'required|numeric|min:0',
        'lines.*.unit_price_ht' => 'required|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
    ]);

    return DB::transaction(function () use ($request) {
        $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
        $tvaRate = $customer->tvaGroup->rate ?? 0;
        $status = $request->input('action') === 'validate' ? 'validée' : 'brouillon';

        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            $souche = Souche::where('type', 'commande_vente')->lockForUpdate()->first();
            if (!$souche) {
                \Log::error('Souche commande_vente manquante');
                throw new \Exception('Souche commande vente manquante');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            \Log::info('Generating numdoc', ['numdoc' => $numdoc, 'last_number' => $souche->last_number, 'retry' => $retryCount]);

            if (!SalesOrder::where('numdoc', $numdoc)->exists()) {
                $order = SalesOrder::create([
                    'customer_id' => $request->customer_id,
                    'order_date' => $request->order_date,
                    'numclient' => $customer->code,
                    'status' => $status,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'notes' => $request->notes,
                    'numdoc' => $numdoc,
                    'tva_rate' => $tvaRate,
                    'store_id' => $request->store_id ?? 1,
                ]);

                $total = 0;
                foreach ($request->lines as $line) {
                    $item = Item::where('code', $line['article_code'])->first();
                    if (!$item) {
                        throw new \Exception("Article {$line['article_code']} introuvable.");
                    }
                    if ($status === 'validée' && $item->getStockQuantityAttribute() < $line['ordered_quantity']) {
                        // throw new \Exception("Stock insuffisant pour l'article {$line['article_code']}.");
                    }

                    $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                    $unit_price_ttc = $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100) * (1 + $tvaRate / 100);
                    $total_ligne_ttc = $ligne_total * (1 + $tvaRate / 100);
                    $total += $ligne_total;

                    SalesOrderLine::create([
                        'sales_order_id' => $order->id,
                        'article_code' => $line['article_code'],
                        'ordered_quantity' => $line['ordered_quantity'],
                        'unit_price_ht' => $line['unit_price_ht'],
                        'unit_price_ttc' => $unit_price_ttc,
                        'remise' => $line['remise'] ?? 0,
                        'total_ligne_ht' => $ligne_total,
                        'total_ligne_ttc' => $total_ligne_ttc,
                    ]);
                }

                $order->update([
                    'total_ht' => $total,
                    'total_ttc' => $total * (1 + $tvaRate / 100),
                ]);

                if ($status === 'validée') {
                    $this->createDeliveryNoteFromOrder($order, $request);
                }

                $souche->last_number += 1;
                $souche->save();
                \Log::info('Souche updated', ['numdoc' => $numdoc, 'new_last_number' => $souche->last_number]);

                return redirect()->route('sales.list')->with('success', 'Commande ' . ($status === 'validée' ? 'validée et créée' : 'créée'));
            }

            $souche->last_number += 1;
            $souche->save();
            $retryCount++;
            \Log::warning('Duplicate numdoc detected, retrying', ['numdoc' => $numdoc, 'retry' => $retryCount]);
        }

        \Log::error('Failed to find unique numdoc after retries', ['last_numdoc' => $numdoc, 'retries' => $maxRetries]);
        throw new \Exception('Impossible de générer un numéro de document unique après plusieurs tentatives.');
    });
}

    /**
     * Create a delivery note from a sales order.
     */
    /**
 * Create a delivery note from a sales order.
 */
protected function createDeliveryNoteFromOrder(SalesOrder $order, Request $request)
{
    return DB::transaction(function () use ($order, $request) {
        $deliveryDate = $request->input('delivery_date', now());
        $souche = Souche::where('type', 'bon_livraison')->lockForUpdate()->first();
        if (!$souche) {
            throw new \Exception('Souche bon de livraison manquante');
        }

        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        $deliveryNote = DeliveryNote::create([
            'sales_order_id' => $order->id,
            'delivery_date' => $deliveryDate,
            'status' => 'en_cours',
            'total_delivered' => 0,
            'total_ht' => 0, // Initialize
            'total_ttc' => 0, // Initialize
            'numclient' => $order->numclient,
            'tva_rate' => $order->tva_rate,
            'numdoc' => $numdoc,
            'notes' => $request->notes,
        ]);

        $totalDelivered = 0;
        $totalHt = 0;
        $tvaRate = $order->tva_rate ?? 0;

        foreach ($order->lines as $line) {
            $item = Item::where('code', $line->article_code)->first();
            if (!$item) {
                continue;
            }

            $total_ligne_ht = $line->ordered_quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
            $unit_price_ttc = $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100) * (1 + $tvaRate / 100);
            $total_ligne_ttc = $total_ligne_ht * (1 + $tvaRate / 100);

            DeliveryNoteLine::create([
                'delivery_note_id' => $deliveryNote->id,
                'article_code' => $line->article_code,
                'delivered_quantity' => $line->ordered_quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'unit_price_ttc' => $unit_price_ttc,
                'remise' => $line->remise ?? 0,
                'total_ligne_ht' => $total_ligne_ht,
                'total_ligne_ttc' => $total_ligne_ttc,
            ]);

            $totalDelivered += $line->ordered_quantity;
            $totalHt += $total_ligne_ht;
        }

        $deliveryNote->update([
            'total_delivered' => $totalDelivered,
            'total_ht' => $totalHt,
            'total_ttc' => $totalHt * (1 + $tvaRate / 100),
        ]);

        foreach ($order->lines as $line) {
            $item = Item::where('code', $line->article_code)->first();
            if ($item) {
                $storeId = $order->store_id ?? 1;
                $stock = Stock::firstOrNew([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                ]);
                $stock->quantity = ($stock->quantity ?? 0) - $line->ordered_quantity;
                $stock->save();

                $cost_price = $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
                StockMovement::create([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                    'type' => 'vente',
                    'quantity' => -$line->ordered_quantity,
                    'cost_price' => $cost_price,
                    'supplier_name' => $order->customer->name,
                    'reason' => 'Validation commande vente #' . $order->numdoc,
                    'reference' => $order->numdoc,
                ]);
            }
        }

        $souche->last_number += 1;
        $souche->save();
    });
}

    /**
     * Create a direct delivery note (without a sales order).
     */
    public function createDirectDeliveryNote()
    {
        $customers = Customer::with('tvaGroup')->get();
        $tvaRates = $customers->mapWithKeys(fn($c) => [$c->id => $c->tvaGroup->rate ?? 0])->toJson();
        return view('sales.create_direct_delivery', compact('customers', 'tvaRates'));
    }

    /**
     * Store a direct delivery note.
     */
   /**
 * Store a direct delivery note.
 */

   /**
 * Store a direct delivery note.
 */
public function storeDirectDeliveryNote(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'order_date' => 'required|date',
        'lines' => 'required|array',
        'lines.*.article_code' => 'required|exists:items,code',
        'lines.*.ordered_quantity' => 'required|numeric|min:0',
        'lines.*.unit_price_ht' => 'required|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
    ]);

    return DB::transaction(function () use ($request) {
        $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
        $tvaRate = $customer->tvaGroup->rate ?? 0;
        $status = $request->input('action') === 'validate' ? 'validée' : 'brouillon';

        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            $souche = Souche::where('type', 'commande_vente')->lockForUpdate()->first();
            if (!$souche) {
                \Log::error('Souche commande_vente manquante');
                throw new \Exception('Souche commande vente manquante');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            \Log::info('Generating numdoc', ['numdoc' => $numdoc, 'last_number' => $souche->last_number, 'retry' => $retryCount]);

            if (!SalesOrder::where('numdoc', $numdoc)->exists()) {
                $order = SalesOrder::create([
                    'customer_id' => $request->customer_id,
                    'order_date' => $request->order_date,
                    'numclient' => $customer->code,
                    'status' => 'validée',
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'notes' => $request->notes,
                    'numdoc' => $numdoc,
                    'tva_rate' => $tvaRate,
                    'store_id' => $request->store_id ?? 1,
                ]);

                $total = 0;
                foreach ($request->lines as $line) {
                    $item = Item::where('code', $line['article_code'])->first();
                    if (!$item) {
                        throw new \Exception("Article {$line['article_code']} introuvable.");
                    }
                    if ($status === 'validée' && $item->getStockQuantityAttribute() < $line['ordered_quantity']) {
                        // throw new \Exception("Stock insuffisant pour l'article {$line['article_code']}.");
                    }

                    $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                    $unit_price_ttc = $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100) * (1 + $tvaRate / 100);
                    $total_ligne_ttc = $ligne_total * (1 + $tvaRate / 100);
                    $total += $ligne_total;

                    SalesOrderLine::create([
                        'sales_order_id' => $order->id,
                        'article_code' => $line['article_code'],
                        'ordered_quantity' => $line['ordered_quantity'],
                        'unit_price_ht' => $line['unit_price_ht'],
                        'unit_price_ttc' => $unit_price_ttc,
                        'remise' => $line['remise'] ?? 0,
                        'total_ligne_ht' => $ligne_total,
                        'total_ligne_ttc' => $total_ligne_ttc,
                    ]);
                }

                $order->update([
                    'total_ht' => $total,
                    'total_ttc' => $total * (1 + $tvaRate / 100),
                ]);

                if ($status === 'validée') {
                    $this->createDeliveryNoteFromOrder($order, $request);
                }

                $souche->last_number += 1;
                $souche->save();
                \Log::info('Souche updated', ['numdoc' => $numdoc, 'new_last_number' => $souche->last_number]);

                return redirect()->route('delivery_notes.list')->with('success', 'Commande ' . ($status === 'validée' ? 'validée et créée' : 'créée'));
            }

            $souche->last_number += 1;
            $souche->save();
            $retryCount++;
            \Log::warning('Duplicate numdoc detected, retrying', ['numdoc' => $numdoc, 'retry' => $retryCount]);
        }

        \Log::error('Failed to find unique numdoc after retries', ['last_numdoc' => $numdoc, 'retries' => $maxRetries]);
        throw new \Exception('Impossible de générer un numéro de document unique après plusieurs tentatives.');
    });
}

    /**
     * Edit a sales order.
     */
    public function edit($id)
    {
        $order = SalesOrder::with('lines', 'customer')->findOrFail($id);
        $customers = Customer::with('tvaGroup')->get();
        $tvaRates = $customers->mapWithKeys(fn($c) => [$c->id => $c->tvaGroup->rate ?? 0])->toJson();
        $items = Item::with(['brand', 'supplier', 'tvaGroup'])->get();
        return view('sales.edit', compact('order', 'customers', 'tvaRates', 'items'));
    }

    /**
     * Update a sales order.
     */
   /**
 * Update a sales order.
 */

   /**
 * Update a sales order.
 */
public function update(Request $request, $numdoc)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'order_date' => 'required|date',
        'lines' => 'required|array',
        'lines.*.article_code' => 'required|exists:items,code',
        'lines.*.ordered_quantity' => 'required|numeric|min:0',
        'lines.*.unit_price_ht' => 'required|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
    ]);

    return DB::transaction(function () use ($request, $numdoc) {
        $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
        $tvaRate = $customer->tvaGroup->rate ?? 0;
        $order = SalesOrder::where('numdoc', $numdoc)->firstOrFail();

        $order->update([
            'customer_id' => $request->customer_id,
             'numclient' => $customer->code,
            'order_date' => $request->order_date,
            'notes' => $request->notes,
        ]);

        $order->lines()->delete();
        $total = 0;
        foreach ($request->lines as $line) {
            $item = Item::where('code', $line['article_code'])->first();
            if (!$item) {
                throw new \Exception("Article {$line['article_code']} introuvable.");
            }
            if ($request->input('action') === 'validate' && $item->getStockQuantityAttribute() < $line['ordered_quantity']) {
                // throw new \Exception("Stock insuffisant pour l'article {$line['article_code']}.");
            }

            $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
            $unit_price_ttc = $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100) * (1 + $tvaRate / 100);
            $total_ligne_ttc = $ligne_total * (1 + $tvaRate / 100);
            $total += $ligne_total;

            SalesOrderLine::create([
                'sales_order_id' => $order->id,
                'article_code' => $line['article_code'],
                'ordered_quantity' => $line['ordered_quantity'],
                'unit_price_ht' => $line['unit_price_ht'],
                'unit_price_ttc' => $unit_price_ttc,
                'remise' => $line['remise'] ?? 0,
                'total_ligne_ht' => $ligne_total,
                'total_ligne_ttc' => $total_ligne_ttc,
            ]);
        }

        $order->update([
            'total_ht' => $total,
            'total_ttc' => $total * (1 + $tvaRate / 100),
        ]);

        if ($request->input('action') === 'validate') {
            $order->update(['status' => 'validée']);
            $deliveryNote = $order->deliveryNote;

            if ($deliveryNote) {
                $deliveryNote->update([
                     'numclient' => $customer->code,
                    'delivery_date' => $request->input('delivery_date', now()),
                    'status' => 'en_cours',
                ]);
                $deliveryNote->lines()->delete();
            } else {
                $souche = Souche::where('type', 'bon_livraison')->lockForUpdate()->first();
                if (!$souche) {
                    throw new \Exception('Souche bon de livraison manquante');
                }
                $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
                $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

                $deliveryNote = DeliveryNote::create([
                    'sales_order_id' => $order->id,
                    'delivery_date' => now(),
                    'status' => 'en_cours',
                    'total_delivered' => 0,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'tva_rate' => $tvaRate,
                    'numdoc' => $numdoc,
                    'notes' => $request->notes,
                ]);
                $souche->last_number += 1;
                $souche->save();
            }

            $totalDelivered = 0;
            $totalHt = 0;
            foreach ($order->lines as $line) {
                $total_ligne_ht = $line->ordered_quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
                $unit_price_ttc = $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100) * (1 + $tvaRate / 100);
                $total_ligne_ttc = $total_ligne_ht * (1 + $tvaRate / 100);

                DeliveryNoteLine::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'article_code' => $line->article_code,
                    'delivered_quantity' => $line->ordered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'unit_price_ttc' => $unit_price_ttc,
                    'remise' => $line->remise ?? 0,
                    'total_ligne_ht' => $total_ligne_ht,
                    'total_ligne_ttc' => $total_ligne_ttc,
                ]);

                $totalDelivered += $line->ordered_quantity;
                $totalHt += $total_ligne_ht;

                $item = Item::where('code', $line->article_code)->first();
                if ($item) {
                    $storeId = $order->store_id ?? 1;
                    $stock = Stock::firstOrNew([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) - $line->ordered_quantity;
                    $stock->save();

                    $cost_price = $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
                    StockMovement::create([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                        'type' => 'vente',
                        'quantity' => -$line->ordered_quantity,
                        'cost_price' => $cost_price,
                        'supplier_name' => $customer->name,
                        'reason' => 'Validation MAJ commande vente #' . $order->numdoc,
                        'reference' => $order->numdoc,
                    ]);
                }
            }

            $deliveryNote->update([
                'total_delivered' => $totalDelivered,
                'total_ht' => $totalHt,
                'total_ttc' => $totalHt * (1 + $tvaRate / 100),
            ]);
        }

        return redirect()->route('sales.list')
            ->with('success', $request->input('action') === 'validate'
                ? 'Commande mise à jour et validée avec succès.'
                : 'Commande mise à jour avec succès.');
    });
}

    /**
     * Validate a sales order and create delivery note.
     */
  /**
 * Validate a sales order and create delivery note.
 */
public function validateOrder($id)
{
    return DB::transaction(function () use ($id) {
        $order = SalesOrder::with('lines')->findOrFail($id);
        if ($order->status !== 'brouillon') {
            throw new \Exception('Cette commande est déjà validée.');
        }

        foreach ($order->lines as $line) {
            $item = Item::where('code', $line->article_code)->first();
            // if (!$item || $item->getStockQuantityAttribute() < $line->ordered_quantity) {
            //     throw new \Exception("Stock insuffisant pour l'article {$line->article_code}.");
            // }
        }

        $order->update(['status' => 'validée']);

        $souche = Souche::where('type', 'bon_livraison')->lockForUpdate()->first();
        if (!$souche) {
            throw new \Exception('Souche bon de livraison manquante');
        }
        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        $tvaRate = $order->tva_rate ?? 0;
        $deliveryNote = DeliveryNote::create([
            'sales_order_id' => $order->id,
            'numclient' => $order->numclient,
            'delivery_date' => now(),
            'status' => 'en_cours',
            'total_delivered' => 0,
            'total_ht' => 0,
            'total_ttc' => 0,
            'tva_rate' => $tvaRate,
            'numdoc' => $numdoc,
        ]);

        $totalDelivered = 0;
        $totalHt = 0;
        foreach ($order->lines as $line) {
            $total_ligne_ht = $line->ordered_quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
            $unit_price_ttc = $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100) * (1 + $tvaRate / 100);
            $total_ligne_ttc = $total_ligne_ht * (1 + $tvaRate / 100);

            DeliveryNoteLine::create([
                'delivery_note_id' => $deliveryNote->id,
                'article_code' => $line->article_code,
                'delivered_quantity' => $line->ordered_quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'unit_price_ttc' => $unit_price_ttc,
                'remise' => $line->remise ?? 0,
                'total_ligne_ht' => $total_ligne_ht,
                'total_ligne_ttc' => $total_ligne_ttc,
            ]);

            $totalDelivered += $line->ordered_quantity;
            $totalHt += $total_ligne_ht;

            $item = Item::where('code', $line->article_code)->first();
            if ($item) {
                $storeId = $order->store_id ?? 1;
                $stock = Stock::firstOrNew([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                ]);
                $stock->quantity = ($stock->quantity ?? 0) - $line->ordered_quantity;
                $stock->save();

                $cost_price = $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
                StockMovement::create([
                    'item_id' => $item->id,
                    'store_id' => $storeId,
                    'type' => 'vente',
                    'quantity' => -$line->ordered_quantity,
                    'cost_price' => $cost_price,
                    'supplier_name' => $order->customer->name,
                    'reason' => 'Validation commande vente #' . $order->numdoc,
                    'reference' => $order->numdoc,
                ]);
            }
        }

        $deliveryNote->update([
            'total_delivered' => $totalDelivered,
            'total_ht' => $totalHt,
            'total_ttc' => $totalHt * (1 + $tvaRate / 100),
        ]);

        $souche->last_number += 1;
        $souche->save();

        return redirect()->back()->with('success', 'Commande validée et bon de livraison créé.');
    });
}

    /**
     * Create a direct invoice from a sales order.
     */
    public function createDirectInvoice($orderId)
    {
        $order = SalesOrder::with(['lines.item', 'customer'])
            ->where('status', 'validée')
            ->findOrFail($orderId);

        if ($order->invoiced) {
            return back()->with('error', 'Cette commande est déjà facturée.');
        }

        $customers = Customer::all();
        return view('sales.invoices.create_direct', compact('order', 'customers'));
    }

    /**
     * Create a grouped invoice.
     */
    public function createGroupedInvoice()
    {
        $orders = SalesOrder::where('status', 'validée')
            ->where('invoiced', 0)
            ->with(['lines.item', 'customer'])
            ->get();
        $customers = Customer::all();
        return view('sales.invoices.create_grouped', compact('orders', 'customers'));
    }

    /**
     * Create a free invoice.
     */
    public function createFreeInvoice()
    {
        $customers = Customer::with('tvaGroup')->get();
        $tvaRates = $customers->mapWithKeys(fn($c) => [$c->id => $c->tvaGroup->rate ?? 0])->toJson();
        $items = Item::with(['brand', 'supplier', 'tvaGroup'])->get();
        return view('sales.invoices.create_free', compact('customers', 'tvaRates', 'items'));
    }

    /**
     * Store an invoice.
     */
    public function storeInvoice(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'type' => 'required|in:direct,groupée,libre',
            'lines' => 'required_if:type,direct,groupée|array',
            'lines.*.article_code' => 'required_if:type,direct,groupée|exists:items,code',
            'lines.*.quantity' => 'required_if:type,direct,groupée|numeric|min:0',
            'lines.*.unit_price_ht' => 'required_if:type,direct,groupée|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.description' => 'required_if:type,libre|string|nullable',
            'tva_rate' => 'required_if:type,groupée,libre|numeric|min:0',
            'sales_order_id' => 'required_if:type,direct|exists:sales_orders,id',
        ]);

        return DB::transaction(function () use ($request) {
            $souche = Souche::where('type', 'facture_vente')->lockForUpdate()->first();
            if (!$souche) {
                throw new \Exception('Souche facture vente manquante');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            $tvaRate = 0;
            if ($request->type === 'direct') {
                $order = SalesOrder::findOrFail($request->sales_order_id);
                if ($order->invoiced) {
                    throw new \Exception('Cette commande est déjà facturée.');
                }
                $tvaRate = $order->tva_rate ?? 0;
            } elseif ($request->type === 'groupée') {
                $tvaRate = $request->tva_rate ?? 0;
                $orderIds = array_unique(array_filter(array_column($request->lines, 'sales_order_id')));
                $orders = SalesOrder::whereIn('id', $orderIds)->get();
                $tvaRates = $orders->pluck('tva_rate')->unique();
                if ($tvaRates->count() > 1 || $tvaRates->first() != $tvaRate) {
                    throw new \Exception('Les commandes sélectionnées ont des taux de TVA différents ou incompatibles.');
                }
            } else {
                $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
                $tvaRate = $request->tva_rate ?? $customer->tvaGroup->rate ?? 0;
            }

            $invoice = SalesInvoice::create([
                'customer_id' => $request->customer_id,
                'numdoc' => $numdoc,
                'invoice_date' => $request->invoice_date,
                'status' => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
                'notes' => $request->notes,
                'type' => $request->type,
                'sales_order_id' => $request->type === 'direct' ? $request->sales_order_id : null,
            ]);

            $totalHt = 0;
            $orderIds = [];
            foreach ($request->lines as $line) {
                $totalLigneHt = $line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalHt += $totalLigneHt;

                if ($request->type === 'groupée' && !empty($line['sales_order_id'])) {
                    $orderIds[] = $line['sales_order_id'];
                }

                SalesInvoiceLine::create([
                    'sales_invoice_id' => $invoice->id,
                    'article_code' => $line['article_code'] ?? null,
                    'sales_order_id' => $line['sales_order_id'] ?? null,
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
                    SalesOrder::whereIn('id', array_unique($orderIds))->update(['invoiced' => 1]);
                }
            }

            $souche->last_number += 1;
            $souche->save();

            return redirect()->route('sales.invoices.list')->with('success', 'Facture créée avec succès.');
        });
    }

    /**
     * List all invoices.
     */
    public function invoicesList(Request $request)
    {
        $query = SalesInvoice::with(['customer', 'lines.item'])->orderBy('updated_at', 'desc');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        $customers = Customer::orderBy('name')->get();

        return view('sales.invoices.list', compact('invoices', 'customers'));
    }

    /**
     * Edit an invoice.
     */
    public function editInvoice($id)
    {
        $invoice = SalesInvoice::with(['lines.item', 'customer'])->findOrFail($id);
        if ($invoice->status === 'validée') {
            return back()->with('error', 'Impossible de modifier une facture validée.');
        }
        $customers = Customer::all();
        $tvaRates = Customer::with('tvaGroup')
            ->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->tvaGroup->rate ?? 0])
            ->toJson();
        $items = Item::with(['brand', 'supplier', 'tvaGroup'])->get();
        return view('sales.invoices.edit', compact('invoice', 'customers', 'tvaRates', 'items'));
    }

    /**
     * Update an invoice.
     */
    public function updateInvoice(Request $request, $numdoc)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'lines' => 'required|array',
            'lines.*.article_code' => 'required_if:type,direct,groupée|exists:items,code',
            'lines.*.quantity' => 'required_if:type,direct,groupée|numeric|min:0',
            'lines.*.unit_price_ht' => 'required_if:type,direct,groupée|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
            'lines.*.description' => 'required_if:type,libre|string|nullable',
        ]);

        return DB::transaction(function () use ($request, $numdoc) {
            $invoice = SalesInvoice::where('numdoc', $numdoc)->firstOrFail();
            if ($invoice->status === 'validée') {
                throw new \Exception('Impossible de modifier une facture validée.');
            }

            $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
            $tvaRate = $customer->tvaGroup->rate ?? 0;

            $invoice->update([
                'customer_id' => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'notes' => $request->notes,
            ]);

            $invoice->lines()->delete();
            $totalHt = 0;
            foreach ($request->lines as $line) {
                $totalLigneHt = $line['quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                $totalHt += $totalLigneHt;

                SalesInvoiceLine::create([
                    'sales_invoice_id' => $invoice->id,
                    'article_code' => $line['article_code'] ?? null,
                    'sales_order_id' => $line['sales_order_id'] ?? null,
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

            return redirect()->route('sales.invoices.list')->with('success', 'Facture mise à jour avec succès.');
        });
    }

    /**
     * Search sales orders for grouped invoices.
     */
    public function search(Request $request)
    {
        $customerId = $request->input('customer_id');
        $term = $request->input('term');

        $query = SalesOrder::where('status', 'validée')
            ->where('invoiced', 0)
            ->with(['lines.item', 'customer']);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }
        if ($term) {
            $query->where('numdoc', 'like', '%' . $term . '%');
        }

        $orders = $query->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'numdoc' => $order->numdoc,
                'order_date' => $order->order_date,
                'customer_name' => $order->customer->name,
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

        return response()->json($orders->toArray());
    }

    /**
     * Search items by brand, supplier, description, or reference.
     */
    // la fonction qui search les articles dans nouvelle commande
    public function searchItems(Request $request)
    {
        $query = Item::with(['brand', 'supplier', 'tvaGroup', 'stocks']);

        if ($request->filled('query')) {
            $searchTerm = $request->query('query');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('barcode', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }
        if ($request->filled('supplier_id')) {
            $query->where('codefournisseur', Supplier::find($request->input('supplier_id'))->code ?? null);
        }
        if ($request->filled('reference')) {
            $query->where('code', 'like', '%' . $request->input('reference') . '%')
                ->orWhere('barcode', 'like', '%' . $request->input('reference') . '%');
        }

        $items = $query->get()->map(function ($item) {
            return [
                'code' => $item->code,
                'name' => $item->name,
                'location' => $item->location,
                'description' => $item->description,
                'brand' => $item->brand->name ?? null,
                'supplier' => $item->supplier->name ?? null,
                'stock_quantity' => $item->getStockQuantityAttribute(),
                'cost_price' => $item->cost_price,
                'sale_price' => $item->sale_price,
            ];
        });

        return response()->json($items->toArray());
    }

    /**
     * Get item history (stock movements).
     */
    public function itemHistory($itemId)
    {
        $item = Item::with(['stockMovements' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($itemId);

        return view('sales.item_history', compact('item'));
    }

    /**
     * Export a single sales order.
     */
public function exportSingle($id)
{
    $order = SalesOrder::with(['customer', 'lines.item'])->findOrFail($id);
    return Excel::download(new SalesOrderExport($order), 'sales_order_' . $order->numdoc . '.xlsx');
}

    /**
     * Print a single sales order.
     */
    public function printSingle($id)
    {
        $order = SalesOrder::with(['customer', 'deliveryNote', 'lines.item', 'customer.tvaGroup'])->findOrFail($id);
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
            $generator->getBarcode($order->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = PDF::loadView('pdf.sales_order', compact('order', 'company', 'barcode'));
        return $pdf->stream("commande_vente_{$order->numdoc}.pdf");
    }

    /**
     * Print a single invoice.
     */
    public function printSingleInvoice($id)
    {
        $invoice = SalesInvoice::with(['customer', 'lines.item'])->findOrFail($id);
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

        $pdf = PDF::loadView('pdf.sales_invoice', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_vente_{$invoice->numdoc}.pdf");
    }

    /**
     * Export a single invoice.
     */
    public function exportSingleInvoice($id)
    {
        $invoice = SalesInvoice::with(['customer', 'lines.item'])->findOrFail($id);
        return Excel::download(new SalesInvoiceExport($invoice), "facture_vente_{$invoice->numdoc}.xlsx");
    }
}