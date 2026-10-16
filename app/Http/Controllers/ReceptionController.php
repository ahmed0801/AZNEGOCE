<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use App\Models\Reception;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\Supplier;
use App\Services\PurchaseDocumentService;
use App\Services\PurchaseException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Réceptions d'achat : liste, détail, réception d'une commande (partielle / surplus / scan),
 * rectification, annulation, facturation des réceptions.
 * Le stock et les coûts sont gérés par PurchaseDocumentService (testé séparément).
 */
class ReceptionController extends Controller
{
    /** @var PurchaseDocumentService */
    private $service;

    public function __construct(PurchaseDocumentService $service)
    {
        $this->service = $service;
    }

    /* ═══════════════════════════ LISTE ═══════════════════════════ */

    public function index(Request $request)
    {
        $q = Reception::with(['supplier', 'purchaseOrder', 'store', 'lines.item'])
            ->withCount('lines')
            ->withSum('lines as total_ht', 'total_ligne_ht')
            ->withSum('lines as total_qty', 'received_quantity');

        if ($request->filled('q')) {
            $t = trim($request->input('q'));
            $q->where(function ($w) use ($t) {
                $w->where('numdoc', 'like', "%$t%")
                  ->orWhere('supplier_ref', 'like', "%$t%")
                  ->orWhereHas('purchaseOrder', function ($o) use ($t) { $o->where('numdoc', 'like', "%$t%"); })
                  ->orWhereHas('supplier', function ($s) use ($t) { $s->where('name', 'like', "%$t%")->orWhere('code', 'like', "%$t%"); })
                  ->orWhereHas('lines', function ($l) use ($t) { $l->where('article_code', 'like', "$t%"); });
            });
        }
        if ($request->filled('supplier_id')) $q->where('supplier_id', $request->input('supplier_id'));
        if ($request->filled('from'))         $q->whereDate('reception_date', '>=', $request->input('from'));
        if ($request->filled('to'))           $q->whereDate('reception_date', '<=', $request->input('to'));
        switch ($request->input('state')) {
            case 'a_facturer': $q->where('invoiced', 0)->where('status', '!=', 'annulée')->where('numdoc', 'not like', 'REC-H%'); break;
            case 'facturee':   $q->where('invoiced', 1); break;
            case 'annulee':    $q->where('status', 'annulée'); break;
        }

        $receptions = $q->orderByDesc('reception_date')->orderByDesc('id')->paginate(25)->withQueryString();
        $suppliers  = Supplier::orderBy('name')->get(['id', 'code', 'name']);

        $toInvoice = Reception::where('invoiced', 0)->where('status', '!=', 'annulée')->where('numdoc', 'not like', 'REC-H%');
        $kpi = [
            'a_facturer'     => (clone $toInvoice)->count(),
            'a_facturer_ht'  => (float) \App\Models\ReceptionLine::whereIn('reception_id', (clone $toInvoice)->select('id'))->sum('total_ligne_ht'),
            'a_recevoir'     => PurchaseOrder::whereIn('reception_status', ['a_recevoir', 'partielle'])->where('status', '!=', 'brouillon')->count(),
        ];

        // Retours fournisseur non facturés, par fournisseur : proposés dans la facture de fin de mois (en déduction)
        $returnsBySupplier = [];
        $pendingReturns = \App\Models\PurchaseReturn::with(['lines.item', 'purchaseOrder'])
            ->where('invoiced', 0)->where(function ($w) { $w->whereNull('status')->orWhere('status', '!=', 'annulée'); })
            ->orderBy('return_date')->orderBy('id')->get();
        foreach ($pendingReturns as $rt) {
            $returnsBySupplier[$rt->supplier_id][] = [
                'id' => $rt->id, 'numdoc' => $rt->numdoc, 'date' => $rt->return_date ? \Carbon\Carbon::parse($rt->return_date)->format('d/m/Y') : '',
                'order' => optional($rt->purchaseOrder)->numdoc, 'ht' => (float) $rt->total_ht, 'ttc' => (float) $rt->total_ttc,
                'lines' => $rt->lines->map(function ($l) { return ['code' => $l->article_code, 'name' => optional($l->item)->name, 'qty' => (float) $l->returned_quantity, 'pu' => (float) $l->unit_price_ht, 'remise' => (float) $l->remise, 'ht' => (float) $l->total_ligne_ht]; })->values(),
            ];
        }
        $kpi['retours_a_facturer'] = $pendingReturns->count();
        $kpi['retours_a_facturer_ht'] = (float) $pendingReturns->sum('total_ht');

        return view('receptions.index', compact('receptions', 'suppliers', 'kpi', 'returnsBySupplier'));
    }

    /* ═══════════════════════════ RÉCEPTION D'UNE COMMANDE ═══════════════════════════ */

    public function create(Request $request)
    {
        $order = PurchaseOrder::with(['supplier.tvaGroup', 'lines'])->findOrFail($request->query('order_id'));

        if ($order->status === 'brouillon') {
            return redirect()->route('purchases.list')->with('error', 'Validez la commande ' . e($order->numdoc) . ' avant de la réceptionner.');
        }

        $items = Item::whereIn('code', $order->lines->pluck('article_code'))->get()->keyBy('code');
        $lines = $order->lines->map(function ($l) use ($items) {
            $it = $items->get($l->article_code);
            return [
                'order_line_id' => $l->id,
                'code'          => $l->article_code,
                'name'          => $it->name ?? $l->article_code,
                'barcode'       => $it->barcode ?? null,
                'ordered'       => (int) round((float) $l->ordered_quantity),
                'received'      => (int) round((float) $l->received_quantity),
                'pu'            => (float) $l->unit_price_ht,
                'remise'        => (float) $l->remise,
                'tva'           => (float) $l->tva,
            ];
        })->values();

        $stores = Store::orderBy('id')->get();

        return view('receptions.create', compact('order', 'lines', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'action'            => 'required|in:receive,receive_invoice',
            'reception_date'    => 'nullable|date',
            'store_id'          => 'nullable|integer',
            'due_date'          => 'nullable|date',
            'lines'             => 'required|array|min:1',
        ]);

        $order    = PurchaseOrder::findOrFail($request->input('purchase_order_id'));
        $supplier = Supplier::with('tvaGroup')->findOrFail($order->supplier_id);

        $lines = [];
        foreach ((array) $request->input('lines') as $l) {
            if (!is_array($l)) continue;
            $qty = (float) str_replace(',', '.', (string) ($l['received_quantity'] ?? 0));
            if ($qty <= 0) continue;                                    // seules les lignes réellement reçues sont enregistrées
            $lines[] = [
                'article_code'      => $l['article_code'] ?? '',
                'received_quantity' => $qty,
                'unit_price_ht'     => $l['unit_price_ht'] ?? 0,
                'remise'            => $l['remise'] ?? 0,
                'tva'               => $l['tva'] ?? null,
                'order_line_id'     => !empty($l['order_line_id']) ? (int) $l['order_line_id'] : null,
            ];
        }

        $date = $request->input('reception_date') ?: now()->toDateString();
        $header = [
            'date'         => $date,
            'notes'        => $request->input('notes'),
            'store_id'     => $request->input('store_id') ?: null,
            'supplier_ref' => $request->input('supplier_ref'),
            'invoice_date' => $date,
            'due_date'     => $request->input('due_date') ?: null,
        ];

        try {
            if (!$lines) {
                throw new PurchaseException('Aucune quantité reçue : saisissez ou scannez au moins un article.');
            }
            if ($request->input('action') === 'receive_invoice') {
                $res = $this->service->receiveAndInvoice($supplier, $header, $lines, $order);
                return redirect()->route('invoices.list')->with('success', "Réception {$res['reception']->numdoc} et facture {$res['invoice']->numdoc} enregistrées : stock et solde fournisseur mis à jour.");
            }
            $rec = $this->service->createReception($supplier, $header, $lines, $order);
            return redirect()->route('receptions.index')->with('success', "Réception {$rec->numdoc} enregistrée : le stock est mis à jour.");
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }

    /** Scan d'un code qui n'est pas dans la commande : l'article existe-t-il ? (code ou code-barres exact) */
    public function lookup(Request $request)
    {
        $code = trim((string) $request->query('code', ''));
        if ($code === '') return response()->json(['found' => false]);

        $supplier = $request->filled('supplier_id') ? Supplier::with('tvaGroup')->find($request->query('supplier_id')) : null;
        $item = Item::with('tvaGroup')->where('is_active', 1)
            ->where(function ($w) use ($code) { $w->where('code', $code)->orWhere('barcode', $code); })->first();
        if (!$item) return response()->json(['found' => false]);

        [$cost, $remise] = $this->service->costFor($item, $supplier);

        return response()->json([
            'found'   => true,
            'code'    => $item->code,
            'name'    => $item->name,
            'barcode' => $item->barcode,
            'pu'      => $cost,
            'remise'  => $remise,
            'tva'     => $supplier ? $this->service->defaultTva($supplier, $item) : (float) ($item->tvaGroup->rate ?? 0),
        ]);
    }

    /* ═══════════════════════════ DÉTAIL ═══════════════════════════ */

    public function show($id)
    {
        $reception = Reception::with(['supplier', 'purchaseOrder', 'store', 'lines.item', 'lines.orderLine'])->findOrFail($id);

        $movements = StockMovement::whereIn('reception_line_id', $reception->lines->pluck('id'))->orderBy('id')->get();
        $invoice   = $reception->invoiced
            ? PurchaseInvoice::whereHas('lines', function ($q) use ($reception) { $q->where('reception_id', $reception->id); })->first()
            : null;

        return view('receptions.show', [
            'reception' => $reception,
            'movements' => $movements,
            'invoice'   => $invoice,
            'editable'  => $this->isEditable($reception),
            'historic'  => $this->isHistoric($reception),
        ]);
    }

    private function isHistoric(Reception $r): bool
    {
        return strpos((string) $r->numdoc, 'REC-H') === 0;
    }

    private function isEditable(Reception $r): bool
    {
        return !$r->invoiced && $r->status !== 'annulée' && !$this->isHistoric($r) && $r->stock_applied;
    }

    /* ═══════════════════════════ RECTIFICATION / ANNULATION ═══════════════════════════ */

    public function edit($id)
    {
        $reception = Reception::with(['supplier', 'purchaseOrder', 'store', 'lines.item', 'lines.orderLine'])->findOrFail($id);
        if (!$this->isEditable($reception)) {
            return redirect()->route('receptions.show', $reception->id)->with('error', 'Cette réception ne peut plus être rectifiée (facturée, annulée ou historique).');
        }
        return view('receptions.edit', compact('reception'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['lines' => 'required|array|min:1', 'reception_date' => 'nullable|date']);

        $changes = [];
        foreach ((array) $request->input('lines') as $lineId => $l) {
            if (!is_array($l)) continue;
            $changes[(int) $lineId] = array_intersect_key($l, array_flip(['qty', 'pu', 'remise', 'tva']));
        }

        try {
            $rec = $this->service->correctReception((int) $id, $changes, [
                'supplier_ref' => $request->input('supplier_ref'),
                'notes'        => $request->input('notes'),
                'date'         => $request->input('reception_date') ?: null,
            ]);
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }

        return redirect()->route('receptions.show', $rec->id)->with('success', "Réception {$rec->numdoc} rectifiée : le stock a été ajusté.");
    }

    public function cancel(Request $request, $id)
    {
        try {
            $rec = $this->service->cancelReception((int) $id, $request->input('reason'));
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }
        return redirect()->route('receptions.index')->with('success', "Réception {$rec->numdoc} annulée : le stock a été ressorti.");
    }

    /* ═══════════════════════════ FACTURATION DES RÉCEPTIONS ═══════════════════════════ */

    public function invoice(Request $request)
    {
        $request->validate([
            'ids'          => 'required|array|min:1',
            'ids.*'        => 'integer|exists:receptions,id',
            'return_ids'   => 'nullable|array',
            'return_ids.*' => 'integer|exists:purchase_returns,id',
            'invoice_date' => 'nullable|date',
            'due_date'     => 'nullable|date',
        ], ['ids.required' => 'Sélectionnez au moins une réception à facturer.']);

        $receptions = Reception::whereIn('id', $request->input('ids'))->get();
        $supplierIds = $receptions->pluck('supplier_id')->unique();
        if ($supplierIds->count() !== 1) {
            return back()->with('error', 'Sélectionnez des réceptions du même fournisseur.');
        }
        $supplier = Supplier::findOrFail($supplierIds->first());

        try {
            $invoice = $this->service->createInvoiceForReceptions($supplier, [
                'invoice_date' => $request->input('invoice_date') ?: now()->toDateString(),
                'due_date'     => $request->input('due_date') ?: null,
                'supplier_ref' => $request->input('supplier_ref'),
                'notes'        => $request->input('notes'),
            ], $receptions->all(), 'groupée', (array) $request->input('return_ids', []));
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }

        $nbRet = count((array) $request->input('return_ids', []));
        return redirect()->route('invoices.list')->with('success', "Facture {$invoice->numdoc} créée ({$receptions->count()} réception(s)" . ($nbRet ? " − {$nbRet} retour(s)" : '') . "). Le solde du fournisseur a été mis à jour de " . number_format((float) $invoice->total_ttc, 2, ',', ' ') . ' € TTC.');
    }

    /* ═══════════════════════════ PDF ═══════════════════════════ */

    public function generatePdf($id)
    {
        $reception = Reception::with(['supplier', 'purchaseOrder', 'store', 'lines.item', 'lines.orderLine'])->findOrFail($id);
        $pdf = Pdf::loadView('receptions.pdf', ['reception' => $reception, 'supplier' => $reception->supplier]);

        return $pdf->stream('reception_' . ($reception->numdoc ?: $reception->id) . '.pdf');
    }
}
