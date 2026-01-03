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
use App\Models\DiscountGroup;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\PaymentMode;
use App\Models\PaymentTerm;
use App\Models\Souche;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\TvaGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use PDF;
use Illuminate\Support\Facades\Http;

class SalesController extends Controller
{
    /**
     * List all sales orders with filtering capabilities.
     */
    public function list(Request $request)
    {
        $query = SalesOrder::with(['customer', 'lines.item', 'deliveryNote'])
                        ->where('numdoc', 'like', 'C%') // ✅ uniquement les devis
                        ->orderBy('updated_at', 'desc');

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

                 // ✅ NOUVEAU : filtre vendeur
    if ($request->filled('vendeur')) {
        $query->where('vendeur', $request->vendeur);
    }


        if ($request->filled('delivery_status')) {
            $query->whereHas('deliveryNote', function ($q) use ($request) {
                $q->where('status', $request->delivery_status);
            });
        }


                        // On récupère aussi la liste des vendeurs uniques pour le select
    $vendeurs = User::where('role', 'vendeur')
        ->orderBy('name')
        ->pluck('name')
        ->unique();

        $sales = $query->get();
        $customers = Customer::orderBy('name')->get();


        $sales = $query->get();
        $customers = Customer::orderBy('name')->get();

        return view('sales.list', compact('sales', 'customers','vendeurs'));
    }






    public function devislist(Request $request)
    {
        $query = SalesOrder::with(['customer', 'lines.item', 'deliveryNote'])
                ->where('numdoc', 'like', 'D%') // ✅ uniquement les devis
                ->orderBy('updated_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

         // ✅ NOUVEAU : filtre vendeur
    if ($request->filled('vendeur')) {
        $query->where('vendeur', $request->vendeur);
    }


        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        if ($request->filled('delivery_status')) {
            $query->whereHas('deliveryNote', function ($q) use ($request) {
                $q->where('status', $request->delivery_status);
            });
        }

                // On récupère aussi la liste des vendeurs uniques pour le select
    $vendeurs = User::where('role', 'vendeur')
        ->orderBy('name')
        ->pluck('name')
        ->unique();

        $sales = $query->get();
        $customers = Customer::orderBy('name')->get();

        return view('sales.devislist', compact('sales', 'customers','vendeurs'));
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
            'vehicle_id' => $request->vehicle_id,
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
                // === COPIE DES 3 CHAMPS DEPUIS LA COMMANDE ===
    'supplier_id' => $line->supplier_id,
    'unit_coast' => $line->unit_coast,
    'discount_coast' => $line->discount_coast,
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
    public function createDirectDeliveryNote_withoutajax()
    {
        $customers = Customer::with(['tvaGroup', 'vehicles'])->get();
        $tvaRates = $customers->mapWithKeys(fn($c) => [$c->id => $c->tvaGroup->rate ?? 0])->toJson();

                $tvaGroups = TvaGroup::all();
$discountGroups = DiscountGroup::all();
$paymentModes = PaymentMode::all();
$paymentTerms = PaymentTerm::all();


        return view('sales.create_direct_delivery', compact('customers', 'tvaRates','tvaGroups','discountGroups','paymentModes','paymentTerms'));
    }










    private function getTecdocBrands()
{
    $response = Http::withHeaders([
        'X-Api-Key' => env('TECDOC_API_KEY', '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg')
    ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
        "getLinkageTargets" => [
            "provider" => env('TECDOC_PROVIDER_ID', 23454),
            "linkageTargetCountry" => env('TECDOC_COUNTRY', 'TN'),
            "lang" => env('TECDOC_LANG', 'fr'),
            "linkageTargetType" => "P",
            "perPage" => 0,
            "page" => 1,
            "includeMfrFacets" => true
        ]
    ]);

    return $response->successful() && isset($response->json()['mfrFacets']['counts'])
        ? $response->json()['mfrFacets']['counts']
        : [];
}


    public function createDirectDeliveryNote()
{
    $tvaRates = []; // Empty since tvaRate is fetched via AJAX
    $tvaGroups = TvaGroup::all();
    $discountGroups = DiscountGroup::all();
    $paymentModes = PaymentMode::all();
    $paymentTerms = PaymentTerm::all();

    $brands = $this->getTecdocBrands();
// ou directement le code si tu ne veux pas créer la fonction privée


// CORRECTION :
    $suppliers = Supplier::where('has_b2b', true)
                     ->orderBy('name')->get(['id', 'name', 'code']);
    // OU si tu veux filtrer les actifs :
    // $suppliers = Supplier::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);

    $suppliersForSelect2 = $suppliers->map(function($s) {
        return [
            'id'   => $s->id,
            'text' => $s->name . ' (' . ($s->code ?? 'N/A') . ')'
        ];
    })->toArray();

    return view('sales.create_direct_delivery', compact('tvaRates', 'tvaGroups', 'discountGroups', 'paymentModes', 'paymentTerms','brands','suppliers','suppliersForSelect2'));
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
    // Validation globale sans exists sur items.code
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'order_date' => 'required|date',
        'lines' => 'required|array',
        'lines.*.ordered_quantity' => 'required|numeric|min:0.01',
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

foreach ($request->lines as $index => $line) {



    // ajoute ca correctif total
                    $line['unit_price_ht']    = (float) str_replace(',', '.', $line['unit_price_ht'] ?? 0);
    $line['ordered_quantity'] = (float) str_replace(',', '.', $line['ordered_quantity'] ?? 0);
    $line['remise']           = (float) str_replace(',', '.', $line['remise'] ?? 0);

// fin correctif total




    $articleCode = $line['article_code'] ?? null;
    $isNewItem = !empty($line['is_new_item']);
    $item = null;

    // === CAS 1 : Nouvel article "Divers" (créé à la volée) ===
    if ($isNewItem) {
        $request->validate([
            "lines.$index.item_name" => 'required|string|max:255',
            "lines.$index.unit_price_ht" => 'required|numeric|min:0',
        ]);

        $code = trim($line['article_code'] ?? 'DIVERS');

        $item = Item::where('code', $code)->first();

        if ($item) {
            $item->update([
                'name' => $line['item_name'],
                'sale_price' => $line['unit_price_ht'],
                'cost_price' => $line['unit_price_ht'],
                'location' => 'Divers',
                'is_active' => 1,
            ]);
        } else {
            $item = Item::create([
                'code' => $code,
                'name' => $line['item_name'],
                'sale_price' => $line['unit_price_ht'],
                'cost_price' => $line['unit_price_ht'],
                'is_active' => 1,
                'store_id' => $request->store_id ?? 1,
                'location' => 'Divers',
            ]);
        }
    }
    // === CAS 2 : Article existant (normal) ===
    else {
        $item = Item::where('code', $articleCode)->first();

        if (!$item) {
            throw ValidationException::withMessages([
                "lines.$index.article_code" => "L'article avec le code '$articleCode' n'existe pas."
            ]);
        }
    }

    // === À partir d'ici, $item est TOUJOURS un objet valide ===
    $remise = $line['remise'] ?? 0;
    $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - $remise / 100);
    $unit_price_ttc = $line['unit_price_ht'] * (1 - $remise / 100) * (1 + $tvaRate / 100);
    $total_ligne_ttc = $ligne_total * (1 + $tvaRate / 100);
    $total += $ligne_total;

    // Vérification stock uniquement si on valide
    if ($status === 'validée') {
        if ($item->getStockQuantityAttribute() < $line['ordered_quantity']) {
            throw new \Exception("Stock insuffisant pour l'article {$item->code} ({$item->name}).");
        }

        // Déduction du stock (comme dans l'autre fonction)
        $storeId = $order->store_id ?? 1;
        $stock = Stock::firstOrNew([
            'item_id' => $item->id,
            'store_id' => $storeId,
        ]);
        $stock->quantity = ($stock->quantity ?? 0) - $line['ordered_quantity'];
        $stock->save();

        StockMovement::create([
            'item_id' => $item->id,
            'store_id' => $storeId,
            'type' => 'vente',
            'quantity' => -$line['ordered_quantity'],
            'cost_price' => $line['unit_price_ht'] * (1 - $remise / 100),
            'supplier_name' => $customer->name,
            'reason' => 'Livraison directe depuis commande #' . $order->numdoc,
            'reference' => $order->numdoc,
        ]);
    }

    SalesOrderLine::create([
        'sales_order_id' => $order->id,
        'article_code' => $item->code, // maintenant $item est toujours valide
        'ordered_quantity' => $line['ordered_quantity'],
        'unit_price_ht' => $line['unit_price_ht'],
        'unit_price_ttc' => $unit_price_ttc,
        'remise' => $remise,
        'total_ligne_ht' => $ligne_total,
        'total_ligne_ttc' => $total_ligne_ttc,
        // === LES 3 CHAMPS ===
    'supplier_id' => $line['supplier_id'] ?? null,
    'unit_coast' => $line['unit_coast'] ?? $item->cost_price ?? 0,
    'discount_coast' => $line['discount_coast'] ?? 0,
    ]);
}

                $order->update([
                    'total_ht' => $total,
                    'total_ttc' => $total * (1 + $tvaRate / 100),
                ]);

                // Création du BL à partir de la commande
                $this->createDeliveryNoteFromOrder($order, $request);

                $souche->last_number += 1;
                $souche->save();
                \Log::info('Souche updated', ['numdoc' => $numdoc, 'new_last_number' => $souche->last_number]);

                return redirect()->route('delivery_notes.list')
                    ->with('success', 'Commande ' . ($status === 'validée' ? 'validée et bon de livraison créé' : 'créée en brouillon'));
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










// creer et facturer un BL 
public function storedeliveryandinvoice(Request $request)
{
    // Validation globale (on enlève exists:items,code → gérée plus bas)
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'order_date' => 'required|date',
        'lines' => 'required|array|min:1',
        'lines.*.ordered_quantity' => 'required|numeric|min:0.01',
        'lines.*.unit_price_ht' => 'required|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
    ]);

    return DB::transaction(function () use ($request) {
        $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
        $tvaRate = $customer->tvaGroup->rate ?? 0;

        // === GÉNÉRATION NUMÉRO BL ===
        $maxRetries = 3;
        $retryCount = 0;
        $souche = Souche::where('type', 'bon_livraison')->lockForUpdate()->first();
        if (!$souche) {
            \Log::error('Souche bon_livraison manquante');
            throw new \Exception('Souche pour bon de livraison manquante');
        }

        while ($retryCount < $maxRetries) {
            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            if (!DeliveryNote::where('numdoc', $numdoc)->exists()) {

                // === CRÉATION DU BL ===
                $deliveryNote = DeliveryNote::create([
                    'customer_id' => $request->customer_id,
                    'vehicle_id' => $request->vehicle_id,
                    'delivery_date' => $request->order_date,
                    'numclient' => $customer->code,
                    'status' => 'expédié',
                    'status_livraison' => 'livré',
                    'total_delivered' => 0,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'notes' => $request->notes,
                    'numdoc' => $numdoc,
                    'tva_rate' => $tvaRate,
                    'store_id' => $request->store_id ?? 1,
                    'invoiced' => true,
                ]);

                // === INIT DES TOTAUX (comme avant) ===
                $totalDelivered = 0;
                $totalHt = 0;

                // === BOUCLE SUR LES LIGNES ===
                foreach ($request->lines as $index => $line) {

// ajoute ca correctif total
                    $line['unit_price_ht']    = (float) str_replace(',', '.', $line['unit_price_ht'] ?? 0);
    $line['ordered_quantity'] = (float) str_replace(',', '.', $line['ordered_quantity'] ?? 0);
    $line['remise']           = (float) str_replace(',', '.', $line['remise'] ?? 0);

// fin correctif total



                    $articleCode = $line['article_code'] ?? null;
                    $isNewItem = !empty($line['is_new_item']);

                    $item = null;

                    // === SI NOUVEL ARTICLE (divers) ===
                    // === SI NOUVEL ARTICLE (divers) ===
if ($isNewItem) {
    $request->validate([
        "lines.$index.item_name" => 'required|string|max:255',
        "lines.$index.unit_price_ht" => 'required|numeric|min:0',
    ]);

    $code = trim($line['article_code'] ?? 'DIVERS');

    $item = Item::where('code', $code)->first();

    if ($item) {
        $item->update([
            'name' => $line['item_name'],
            'sale_price' => $line['unit_price_ht'],
            'cost_price' => $line['unit_price_ht'],
            'location' => 'Divers',
            'is_active' => 1,
        ]);
    } else {
        $item = Item::create([
            'code' => $code,
            'name' => $line['item_name'],
            'sale_price' => $line['unit_price_ht'],
            'cost_price' => $line['unit_price_ht'],
            'is_active' => 1,
            'store_id' => $request->store_id ?? 1,
            'location' => 'Divers',
        ]);
    }

    $line['article_code'] = $item->code;
} else {
    $item = Item::where('code', $articleCode)->first();
    if (!$item) {
        throw new \Exception("Article {$articleCode} introuvable.");
    }
}

                    // === CALCULS (identiques à l’ancienne version) ===
                    $remise = $line['remise'] ?? 0;
                    $total_ligne_ht = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - $remise / 100);
                    $unit_price_ttc = $line['unit_price_ht'] * (1 - $remise / 100) * (1 + $tvaRate / 100);
                    $total_ligne_ttc = $total_ligne_ht * (1 + $tvaRate / 100);

                    // === CRÉATION DE LA LIGNE BL ===
                    DeliveryNoteLine::create([
                        'delivery_note_id' => $deliveryNote->id,
                        'article_code' => $item->code,
                        'delivered_quantity' => $line['ordered_quantity'],
                        'unit_price_ht' => $line['unit_price_ht'],
                        'unit_price_ttc' => $unit_price_ttc,
                        'remise' => $remise,
                        'total_ligne_ht' => $total_ligne_ht,
                        'total_ligne_ttc' => $total_ligne_ttc,
                        // === ENREGISTREMENT DES 3 NOUVEAUX CHAMPS ===
    'supplier_id' => $line['supplier_id'] ?? null,
    'unit_coast' => $line['unit_coast'] ?? $item->cost_price ?? 0,
    'discount_coast' => $line['discount_coast'] ?? 0,
                    ]);

                    // === MISE À JOUR DES TOTAUX ===
                    $totalDelivered += $line['ordered_quantity'];
                    $totalHt += $total_ligne_ht;

                    // === GESTION STOCK (identique) ===
                    $storeId = $deliveryNote->store_id ?? 1;
                    $stock = Stock::firstOrNew([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) - $line['ordered_quantity'];
                    $stock->save();

                    $cost_price = $line['unit_price_ht'] * (1 - $remise / 100);
                    StockMovement::create([
                        'item_id' => $item->id,
                        'store_id' => $storeId,
                        'type' => 'vente',
                        'quantity' => -$line['ordered_quantity'],
                        'cost_price' => $cost_price,
                        'supplier_name' => $customer->name,
                        'reason' => 'Validation bon de livraison #' . $deliveryNote->numdoc,
                        'reference' => $deliveryNote->numdoc,
                    ]);
                }

                // === MISE À JOUR DU BL ===
                $deliveryNote->update([
                    'total_delivered' => $totalDelivered,
                    'total_ht' => $totalHt,
                    'total_ttc' => $totalHt * (1 + $tvaRate / 100),
                    'invoiced' => true,
                ]);

                // === CRÉATION FACTURE (identique) ===
                $soucheInvoice = Souche::where('type', 'facture_vente')->lockForUpdate()->first();
                if (!$soucheInvoice) {
                    throw new \Exception('Souche facture vente manquante.');
                }

                $nextNumberInvoice = str_pad($soucheInvoice->last_number + 1, $soucheInvoice->number_length, '0', STR_PAD_LEFT);
                $numdocInvoice = ($soucheInvoice->prefix ?? '') . ($soucheInvoice->suffix ?? '') . $nextNumberInvoice;

                $dueDate = $customer->paymentTerm
                    ? Carbon::parse($request->order_date)->addDays($customer->paymentTerm->days)
                    : null;

                $invoice = Invoice::create([
                    'numdoc' => $numdocInvoice,
                    'type' => 'direct',
                    'numclient' => $customer->code,
                    'customer_id' => $customer->id,
                    'vehicle_id' => $request->vehicle_id,
                    'invoice_date' => $request->order_date,
                    'due_date' => $dueDate,
                    'status' => 'validée',
                    'paid' => false,
                    'total_ht' => $totalHt,
                    'total_ttc' => $totalHt * (1 + $tvaRate / 100),
                    'tva_rate' => $tvaRate,
                    'notes' => $request->notes,
                ]);

                foreach ($deliveryNote->lines as $line) {
                    InvoiceLine::create([
                        'invoice_id' => $invoice->id,
                        'delivery_note_id' => $deliveryNote->id,
                        'article_code' => $line->article_code,
                        'quantity' => $line->delivered_quantity,
                        'unit_price_ht' => $line->unit_price_ht,
                        'remise' => $line->remise ?? 0,
                        'total_ligne_ht' => $line->total_ligne_ht,
                        'total_ligne_ttc' => $line->total_ligne_ttc,
                    ]);
                }

                // === MISE À JOUR SOLDE CLIENT ===
                $totalTtc = $totalHt * (1 + $tvaRate / 100);
                $customer->solde = ($customer->solde ?? 0) + $totalTtc;
                $customer->save();

                \Log::info('Customer balance updated', [
                    'customer_id' => $customer->id,
                    'invoice_id' => $invoice->id,
                    'numdoc' => $numdocInvoice,
                    'amount_added' => $totalTtc,
                    'new_balance' => $customer->solde,
                ]);

                // === FINALISATION ===
                $deliveryNote->update(['invoiced' => true]);
                $invoice->deliveryNotes()->attach($deliveryNote->id);

                $souche->last_number += 1;
                $souche->save();
                $soucheInvoice->last_number += 1;
                $soucheInvoice->save();

                return redirect("/salesinvoices")
                    ->with('success', 'Bon de livraison validé et facture créée avec succès. Facture n°' . $invoice->numdoc);
            }

            $souche->last_number += 1;
            $souche->save();
            $retryCount++;
        }

        throw new \Exception('Impossible de générer un numéro de document unique.');
    });
}
// fin creer et facturer un BL








    /**
     * Edit a sales order.
     */
    /**
 * Edit a sales order (devis / bon de livraison).
 */
public function edit($id)
{
    $order = SalesOrder::with('lines', 'customer')->findOrFail($id);

    // Liste des clients (avec leur groupe TVA)
    $customers = Customer::with('tvaGroup')->get();

    // Pour le JS côté front (tvaRates par client) – même si peu utilisé en edit, on le garde pour cohérence
    $tvaRates = $customers->mapWithKeys(fn($c) => [$c->id => $c->tvaGroup->rate ?? 0])->toJson();

    // === NOUVEAU : Fournisseurs pour le Select2 dans les lignes (prix achat + fournisseur) ===
    $suppliers = Supplier::where('has_b2b', true) // ou 'has_b2b' selon ton besoin
                        ->orderBy('name')
                        ->get(['id', 'name', 'code']);

    $suppliersForSelect2 = $suppliers->map(function ($s) {
        return [
            'id'   => $s->id,
            'text' => $s->name . ($s->code ? ' (' . $s->code . ')' : ''),
        ];
    })->toArray();

    // === NOUVEAU : Marques TECDOC pour le modal d'ajout rapide véhicule ===
    // Si tu as déjà une méthode privée ou un service pour ça, utilise-la
    // Sinon, voici un exemple simple (à adapter selon ton implémentation TecDoc)
    $brands = $this->getTecdocBrands();

    // Alternative si tu as une méthode dédiée (recommandé)
    // $brands = $this->getTecdocBrands();

    return view('sales.edit', compact(
        'order',
        'customers',
        'tvaRates',
        'suppliersForSelect2',
        'brands' // ← indispensable pour le modal "Nouveau (TECDOC)"
    ));
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
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'order_date' => 'required|date',
        'lines' => 'required|array',
        'lines.*.ordered_quantity' => 'required|numeric|min:0.01',
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
            'vehicle_id' => $request->vehicle_id,
            
        ]);

        // Suppression des anciennes lignes
        $order->lines()->delete();

        $total = 0;

        foreach ($request->lines as $index => $line) {
            // === CORRECTIF VIRGULES → POINTS (comme dans store) ===
            $line['unit_price_ht']    = (float) str_replace(',', '.', $line['unit_price_ht'] ?? 0);
            $line['ordered_quantity'] = (float) str_replace(',', '.', $line['ordered_quantity'] ?? 0);
            $line['remise']           = (float) str_replace(',', '.', $line['remise'] ?? 0);

            $articleCode = $line['article_code'] ?? null;
            $isNewItem = !empty($line['is_new_item']);
            $item = null;

            // === GESTION ARTICLE DIVERS (is_new_item) ===
            if ($isNewItem) {
                // Validation spécifique pour les nouveaux articles divers
                $request->validate([
                    "lines.$index.item_name" => 'required|string|max:255',
                    "lines.$index.unit_price_ht" => 'required|numeric|min:0',
                ]);

                $code = trim($articleCode ?? 'DIVERS');

                $item = Item::where('code', $code)->first();

                if ($item) {
                    $item->update([
                        'name' => $line['item_name'],
                        'sale_price' => $line['unit_price_ht'],
                        'cost_price' => $line['unit_price_ht'], // ou un autre logique si besoin
                        'location' => 'Divers',
                        'is_active' => 1,
                        'store_id' => $order->store_id ?? 1,
                    ]);
                } else {
                    $item = Item::create([
                        'code' => $code,
                        'name' => $line['item_name'],
                        'sale_price' => $line['unit_price_ht'],
                        'cost_price' => $line['unit_price_ht'],
                        'is_active' => 1,
                        'store_id' => $order->store_id ?? 1,
                        'location' => 'Divers',
                    ]);
                }

                // On force le code correct pour la suite
                $line['article_code'] = $item->code;
            } else {
                // Article existant normal
                $item = Item::where('code', $articleCode)->first();
                if (!$item) {
                    throw new \Exception("Article {$articleCode} introuvable.");
                }
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
                                        // === ENREGISTREMENT DES 3 NOUVEAUX CHAMPS ===
    'supplier_id' => $line['supplier_id'] ?? null,
    'unit_coast' => $line['unit_coast'] ?? $item->cost_price ?? 0,
    'discount_coast' => $line['discount_coast'] ?? 0,
            ]);
        }

        $order->update([
            'numclient' => $customer->code,
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
                    'vehicle_id' => $request->vehicle_id,
                    'delivery_date' => now(),
                    'status' => 'en_cours',
                    'total_delivered' => 0,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'tva_rate' => $tvaRate,
                    'numdoc' => $numdoc,
                    'numclient' => $customer->code,
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
                                            // === ENREGISTREMENT DES 3 NOUVEAUX CHAMPS ===
    'supplier_id' => $line['supplier_id'] ?? null,
    'unit_coast' => $line['unit_coast'] ?? $item->cost_price ?? 0,
    'discount_coast' => $line['discount_coast'] ?? 0,
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
            // throw new \Exception('Cette commande est déjà validée.');
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
            'vehicle_id' => $order->vehicle_id,
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
                                        // === ENREGISTREMENT DES 3 NOUVEAUX CHAMPS ===
    'supplier_id' => $line['supplier_id'] ?? null,
    'unit_coast' => $line['unit_coast'] ?? $item->cost_price ?? 0,
    'discount_coast' => $line['discount_coast'] ?? 0,
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
                $q->Where('code', 'like', $searchTerm . '%')   // match "1234."
                  ->orWhere('name', 'like',$searchTerm . '%');

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

        $items = $query->take(50)->get()->map(function ($item) {
            return [
                'code' => $item->code,
                'name' => $item->name,
                'location' => $item->location,
                'description' => $item->description,
                'brand' => $item->brand->name ?? null,
                'supplier' => $item->supplier->name ?? null,
                'supplier_id'   => $item->supplier->id ?? null,        // ← LIGNE À AJOUTER
                'stock_quantity' => $item->getStockQuantityAttribute(),
                'cost_price' => $item->cost_price,
                'sale_price' => $item->sale_price,


                'discount_rate' => $item->discountGroup->discount_rate ?? 0,
            'discount_rate_jobber' => $item->discountGroup->discount_rate_jobber ?? 0,
            'discount_rate_professionnel' => $item->discountGroup->discount_rate_professionnel ?? 0,


                 // 🔹 Nouveaux champs importés depuis GOLDA
            'Poids' => $item->Poids,
            'Hauteur' => $item->Hauteur,
            'Longueur' => $item->Longueur,
            'Largeur' => $item->Largeur,
            'Ref_TecDoc' => $item->Ref_TecDoc,
            'Code_pays' => $item->Code_pays,
            'Code_douane' => $item->Code_douane,
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





// devis sans ref
     public function printSinglesansref($id)
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

        $pdf = PDF::loadView('pdf.devissansref', compact('order', 'company', 'barcode'));
        return $pdf->stream("devis_{$order->numdoc}.pdf");
    }



    

    /**
     * Print a single invoice.
     */
    public function printSingleInvoice($id)
{
    $invoice = SalesInvoice::with([
        'customer', 
        'lines.item',
        'deliveryNotes',                 // Pour afficher numdoc + date
        'salesReturns',                  // Pour afficher numdoc + date des retours
        'payments' => function ($query) {
            $query->orderBy('payment_date', 'asc');
        },
        'payments.paymentMode' // Pour avoir le libellé
    ])->findOrFail($id);

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


















  public function storedevis(Request $request)
{
    // Validation globale sans exists sur items.code
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'order_date' => 'required|date',
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'lines' => 'required|array',
        'lines.*.ordered_quantity' => 'required|numeric|min:0.01',
        'lines.*.unit_price_ht' => 'required|numeric|min:0',
        'lines.*.remise' => 'nullable|numeric|min:0|max:100',
    ]);

    return DB::transaction(function () use ($request) {
        $customer = Customer::with('tvaGroup')->findOrFail($request->customer_id);
        $tvaRate = $customer->tvaGroup->rate ?? 0;
        $status = $request->input('action') === 'validate' ? 'validée' : 'Devis';

        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            $souche = Souche::where('type', 'devis')->lockForUpdate()->first();
            if (!$souche) {
                \Log::error('Souche devis manquante');
                throw new \Exception('Souche devis vente manquante');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            \Log::info('Generating numdoc', ['numdoc' => $numdoc, 'last_number' => $souche->last_number, 'retry' => $retryCount]);

            if (!SalesOrder::where('numdoc', $numdoc)->exists()) {
                $order = SalesOrder::create([
                    'customer_id' => $request->customer_id,
                    'order_date' => $request->order_date,
                    'numclient' => $customer->code,
                    'vehicle_id' => $request->vehicle_id,
                    'status' => $status,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'notes' => $request->notes,
                    'numdoc' => $numdoc,
                    'tva_rate' => $tvaRate,
                    'store_id' => $request->store_id ?? 1,
                ]);

                $total = 0;

                foreach ($request->lines as $index => $line) {




                    // ajoute ca correctif total
                    $line['unit_price_ht']    = (float) str_replace(',', '.', $line['unit_price_ht'] ?? 0);
    $line['ordered_quantity'] = (float) str_replace(',', '.', $line['ordered_quantity'] ?? 0);
    $line['remise']           = (float) str_replace(',', '.', $line['remise'] ?? 0);

// fin correctif total



                    $articleCode = $line['article_code'] ?? null;
                    $isNewItem = !empty($line['is_new_item']);
                    $item = null;

                    // === GESTION ARTICLE DIVERS (is_new_item) ===
                  if ($isNewItem) {
    $request->validate([
        "lines.$index.item_name" => 'required|string|max:255',
        "lines.$index.unit_price_ht" => 'required|numeric|min:0',
    ]);

    $code = trim($line['article_code'] ?? 'DIVERS');

    $item = Item::where('code', $code)->first();

    if ($item) {
        $item->update([
            'name' => $line['item_name'],
            'sale_price' => $line['unit_price_ht'],
            'cost_price' => $line['unit_price_ht'],
            'location' => 'Divers',
            'is_active' => 1,
        ]);
    } else {
        $item = Item::create([
            'code' => $code,
            'name' => $line['item_name'],
            'sale_price' => $line['unit_price_ht'],
            'cost_price' => $line['unit_price_ht'],
            'is_active' => 1,
            'store_id' => $request->store_id ?? 1,
            'location' => 'Divers',
        ]);
    }

    $line['article_code'] = $item->code;
} else {
    $item = Item::where('code', $articleCode)->first();
    if (!$item) {
        throw new \Exception("Article {$articleCode} introuvable.");
    }
}

                    // Vérification stock si validée
                    if ($status === 'validée' && $item->getStockQuantityAttribute() < $line['ordered_quantity']) {
                        throw new \Exception("Stock insuffisant pour l'article {$item->code}.");
                    }

                    $remise = $line['remise'] ?? 0;
                    $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - $remise / 100);
                    $unit_price_ttc = $line['unit_price_ht'] * (1 - $remise / 100) * (1 + $tvaRate / 100);
                    $total_ligne_ttc = $ligne_total * (1 + $tvaRate / 100);
                    $total += $ligne_total;

                    SalesOrderLine::create([
                        'sales_order_id' => $order->id,
                        'article_code' => $item->code,
                        'ordered_quantity' => $line['ordered_quantity'],
                        'unit_price_ht' => $line['unit_price_ht'],
                        'unit_price_ttc' => $unit_price_ttc,
                        'remise' => $remise,
                        'total_ligne_ht' => $ligne_total,
                        'total_ligne_ttc' => $total_ligne_ttc,
                        // === LES 3 CHAMPS ===
    'supplier_id' => $line['supplier_id'] ?? null,
    'unit_coast' => $line['unit_coast'] ?? $item->cost_price ?? 0,
    'discount_coast' => $line['discount_coast'] ?? 0,
                    ]);
                }

                $order->update([
                    'total_ht' => $total,
                    'total_ttc' => $total * (1 + $tvaRate / 100),
                ]);

                $souche->last_number += 1;
                $souche->save();
                \Log::info('Souche updated', ['numdoc' => $numdoc, 'new_last_number' => $souche->last_number]);

                return redirect()->route('sales.devislist')
                    ->with('success', 'Devis ' . ($status === 'validée' ? 'validé et créé' : 'créé en brouillon'));
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











  public function storecommande(Request $request)
{
    // Validation globale sans exists sur items.code
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'order_date' => 'required|date',
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'lines' => 'required|array',
        'lines.*.ordered_quantity' => 'required|numeric|min:0.01',
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
                \Log::error('Souche Commande Vente manquante');
                throw new \Exception('Souche Commande Vente manquante');
            }

            $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

            \Log::info('Generating numdoc', ['numdoc' => $numdoc, 'last_number' => $souche->last_number, 'retry' => $retryCount]);

            if (!SalesOrder::where('numdoc', $numdoc)->exists()) {
                $order = SalesOrder::create([
                    'customer_id' => $request->customer_id,
                    'order_date' => $request->order_date,
                    'numclient' => $customer->code,
                    'vehicle_id' => $request->vehicle_id,
                    'status' => $status,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'notes' => $request->notes,
                    'numdoc' => $numdoc,
                    'tva_rate' => $tvaRate,
                    'store_id' => $request->store_id ?? 1,
                ]);

                $total = 0;

                foreach ($request->lines as $index => $line) {




                    // ajoute ca correctif total
                    $line['unit_price_ht']    = (float) str_replace(',', '.', $line['unit_price_ht'] ?? 0);
    $line['ordered_quantity'] = (float) str_replace(',', '.', $line['ordered_quantity'] ?? 0);
    $line['remise']           = (float) str_replace(',', '.', $line['remise'] ?? 0);

// fin correctif total



                    $articleCode = $line['article_code'] ?? null;
                    $isNewItem = !empty($line['is_new_item']);
                    $item = null;

                    // === GESTION ARTICLE DIVERS (is_new_item) ===
                  if ($isNewItem) {
    $request->validate([
        "lines.$index.item_name" => 'required|string|max:255',
        "lines.$index.unit_price_ht" => 'required|numeric|min:0',
    ]);

    $code = trim($line['article_code'] ?? 'DIVERS');

    $item = Item::where('code', $code)->first();

    if ($item) {
        $item->update([
            'name' => $line['item_name'],
            'sale_price' => $line['unit_price_ht'],
            'cost_price' => $line['unit_price_ht'],
            'location' => 'Divers',
            'is_active' => 1,
        ]);
    } else {
        $item = Item::create([
            'code' => $code,
            'name' => $line['item_name'],
            'sale_price' => $line['unit_price_ht'],
            'cost_price' => $line['unit_price_ht'],
            'is_active' => 1,
            'store_id' => $request->store_id ?? 1,
            'location' => 'Divers',
        ]);
    }

    $line['article_code'] = $item->code;
} else {
    $item = Item::where('code', $articleCode)->first();
    if (!$item) {
        throw new \Exception("Article {$articleCode} introuvable.");
    }
}

                    // Vérification stock si validée
                    if ($status === 'validée' && $item->getStockQuantityAttribute() < $line['ordered_quantity']) {
                        throw new \Exception("Stock insuffisant pour l'article {$item->code}.");
                    }

                    $remise = $line['remise'] ?? 0;
                    $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - $remise / 100);
                    $unit_price_ttc = $line['unit_price_ht'] * (1 - $remise / 100) * (1 + $tvaRate / 100);
                    $total_ligne_ttc = $ligne_total * (1 + $tvaRate / 100);
                    $total += $ligne_total;

                    SalesOrderLine::create([
                        'sales_order_id' => $order->id,
                        'article_code' => $item->code,
                        'ordered_quantity' => $line['ordered_quantity'],
                        'unit_price_ht' => $line['unit_price_ht'],
                        'unit_price_ttc' => $unit_price_ttc,
                        'remise' => $remise,
                        'total_ligne_ht' => $ligne_total,
                        'total_ligne_ttc' => $total_ligne_ttc,
                        // === LES 3 CHAMPS ===
    'supplier_id' => $line['supplier_id'] ?? null,
    'unit_coast' => $line['unit_coast'] ?? $item->cost_price ?? 0,
    'discount_coast' => $line['discount_coast'] ?? 0,
                    ]);
                }

                $order->update([
                    'total_ht' => $total,
                    'total_ttc' => $total * (1 + $tvaRate / 100),
                ]);

                $souche->last_number += 1;
                $souche->save();
                \Log::info('Souche updated', ['numdoc' => $numdoc, 'new_last_number' => $souche->last_number]);

                return redirect()->route('sales.list')
                    ->with('success', 'Commande ' . ($status === 'validée' ? 'validé et créé' : 'créé en brouillon'));
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








}
