<?php

namespace App\Http\Controllers;

use App\Models\B2bSetting;
use App\Models\PortalOrder;
use App\Services\PortalErpBridge;
use App\Services\PortalOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/** Boîte de réception « Commandes portail » côté ERP (vendeurs et administrateurs). */
class PortalOrderAdminController extends Controller
{
    public function index()
    {
        return view('b2b_orders', ['pending' => PortalOrder::where('status', 'pending')->count()]);
    }

    private function row($o)
    {
        return [
            'id' => $o->id, 'number' => $o->number, 'status' => $o->status, 'status_label' => $o->status_label,
            'customer' => optional($o->customer)->name, 'customer_code' => optional($o->customer)->code,
            'user' => optional($o->user)->name, 'lines' => $o->lines_count, 'units' => $o->units,
            'total_ht' => (float) $o->total_ht, 'total_ttc' => (float) $o->total_ttc,
            'mode' => $o->delivery_mode, 'auto' => (bool) $o->auto_validated,
            'created' => $o->created_at->format('d/m/Y H:i'), 'ago' => $o->created_at->diffForHumans(),
            'credit_warning' => $this->creditWarning($o),
        ];
    }

    /** Alerte (jamais un blocage) : le solde du compte + cette commande dépasse le plafond autorisé. */
    private function creditWarning($o)
    {
        if ($o->customer_plafond === null || (float) $o->customer_plafond <= 0) return false;
        return ((float) $o->customer_solde + (float) $o->total_ttc) > (float) $o->customer_plafond;
    }

    public function data(Request $request)
    {
        $status = $request->query('status', 'pending');
        $q = DB::table('portal_orders')->selectRaw('status, COUNT(*) as n')->groupBy('status')->pluck('n', 'status')->all();
        $qb = PortalOrder::with(['customer:id,name,code', 'user:id,name'])->orderByDesc('id');
        if (in_array($status, ['pending', 'validated', 'refused', 'cancelled'], true)) $qb->where('status', $status);
        if ($s = trim((string) $request->query('q', ''))) {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $s) . '%';
            $qb->where(function ($w) use ($like) {
                $w->where('number', 'like', $like)->orWhere('po_ref', 'like', $like)
                  ->orWhereHas('customer', function ($c) use ($like) { $c->where('name', 'like', $like)->orWhere('code', 'like', $like); });
            });
        }
        $p = $qb->paginate(20);
        return response()->json([
            'rows' => collect($p->items())->map(function ($o) { return $this->row($o); })->all(),
            'page' => $p->currentPage(), 'last' => $p->lastPage(), 'total' => $p->total(),
            'counts' => ['pending' => (int) ($q['pending'] ?? 0), 'validated' => (int) ($q['validated'] ?? 0), 'refused' => (int) ($q['refused'] ?? 0), 'cancelled' => (int) ($q['cancelled'] ?? 0)],
        ]);
    }

    public function show($id)
    {
        $o = PortalOrder::with(['lines', 'customer', 'user'])->findOrFail($id);
        $stock = DB::table('stocks')->whereIn('item_id', $o->lines->pluck('item_id')->all())->select('item_id', DB::raw('SUM(quantity) as q'))->groupBy('item_id')->pluck('q', 'item_id')->all();
        $c = $o->customer;
        return response()->json($this->row($o) + [
            'po_ref' => $o->po_ref, 'note' => $o->note, 'internal_note' => $o->internal_note, 'refuse_reason' => $o->refuse_reason,
            'customer_phone' => $c ? ($c->phone1 ?: $c->phone2) : null, 'customer_city' => $c ? $c->city : null,
            'customer_blocked' => $c ? (bool) $c->blocked : false,
            'solde' => $o->customer_solde, 'plafond' => $o->customer_plafond,
            'total_tva' => (float) $o->total_tva, 'customer_id' => $o->customer_id,
            'validated_at' => $o->validated_at ? $o->validated_at->format('d/m/Y H:i') : null,
            'erp_document_id' => $o->erp_document_id, 'erp_numdoc' => $o->erp_numdoc, 'erp_error' => $o->erp_error,
            'erp_url' => $o->erp_document_id ? url('/sales/' . $o->erp_document_id . '/edit') : null,
            'erp_invoice_numdoc' => $o->erp_invoice_numdoc, 'erp_invoice_id' => $o->erp_invoice_id,
            'modified' => (bool) $o->modified, 'vendor_message' => $o->vendor_message, 'orig_total_ttc' => $o->orig_total_ttc !== null ? (float) $o->orig_total_ttc : null,
            'items' => $o->lines->map(function ($l) use ($stock) {
                $st = (float) ($stock[$l->item_id] ?? 0);
                return ['id' => $l->id, 'removed' => (bool) $l->removed, 'orig_qty' => $l->orig_qty, 'orig_unit_ht' => $l->orig_unit_ht !== null ? (float) $l->orig_unit_ht : null, 'tva_rate' => (float) $l->tva_rate, 'code' => $l->code, 'name' => $l->name, 'brand' => $l->brand, 'qty' => $l->qty, 'unit_ht' => (float) $l->unit_ht, 'list_ht' => (float) $l->list_ht,
                        'discount' => (float) $l->discount, 'line_ht' => (float) $l->line_ht, 'stock' => $st, 'short' => $st < $l->qty];
            })->all(),
        ]);
    }

    public function validateOrder(Request $request, $id)
    {
        $v = $request->validate(['internal_note' => 'nullable|string|max:1000']);
        $o = PortalOrder::findOrFail($id);
        if ($o->status !== 'pending') return response()->json(['ok' => false, 'message' => 'Cette commande a déjà été traitée.'], 409);
        if (!$o->erp_document_id) {
            $r = (new PortalErpBridge())->sync($o);
            if (!$r['ok']) return response()->json(['ok' => false, 'message' => 'Création de la commande vente impossible : ' . $r['message']], 422);
        }
        $o->update(['status' => 'validated', 'validated_by' => Auth::id(), 'validated_at' => now(), 'internal_note' => $v['internal_note'] ?? $o->internal_note]);
        return response()->json(['ok' => true]);
    }

    /**
     * Le vendeur ajuste une commande EN ATTENTE avant de l'accepter : quantités, prix unitaires, lignes retirées, message au client.
     * Les valeurs d'origine sont conservées pour que le client voie exactement ce qui a changé.
     */
    public function update(Request $request, $id)
    {
        $v = $request->validate([
            'lines' => 'required|array|min:1', 'lines.*.id' => 'required|integer',
            'lines.*.qty' => 'required|integer|min:1|max:100000', 'lines.*.unit_ht' => 'required|numeric|min:0|max:10000000', 'lines.*.removed' => 'nullable|boolean',
            'vendor_message' => 'nullable|string|max:500',
        ]);
        try {
            $res = DB::transaction(function () use ($v, $id) {
                $o = PortalOrder::lockForUpdate()->findOrFail($id);
                if ($o->status !== 'pending') return ['ok' => false, 'message' => 'Cette commande a déjà été traitée.', 'code' => 409];
                $o->load('lines');
                $in = collect($v['lines'])->keyBy('id');
                if ($o->orig_total_ttc === null) $o->orig_total_ttc = $o->total_ttc;
                $ht = 0.0; $tva = 0.0; $units = 0; $count = 0; $changed = false;
                foreach ($o->lines as $l) {
                    $n = $in->get($l->id);
                    if ($n) {
                        if ($l->orig_qty === null) $l->orig_qty = $l->qty;
                        if ($l->orig_unit_ht === null) $l->orig_unit_ht = $l->unit_ht;
                        $l->removed = !empty($n['removed']);
                        $l->qty = (int) $n['qty'];
                        $l->unit_ht = round((float) $n['unit_ht'], 4);
                        $l->line_ht = round($l->qty * $l->unit_ht, 2);
                        $l->save();
                    }
                    if ($l->removed || ($l->orig_qty !== null && $l->qty != $l->orig_qty) || ($l->orig_unit_ht !== null && abs((float) $l->unit_ht - (float) $l->orig_unit_ht) > 0.00005)) $changed = true;
                    if ($l->removed) continue;
                    $ht += (float) $l->line_ht; $tva += (float) $l->line_ht * (float) $l->tva_rate / 100; $units += $l->qty; $count++;
                }
                if ($count === 0) return ['ok' => false, 'message' => 'Gardez au moins une ligne (sinon refusez la commande).', 'code' => 422];
                $msg = trim((string) ($v['vendor_message'] ?? ''));
                $o->total_ht = round($ht, 2); $o->total_tva = round($tva, 2); $o->total_ttc = round($ht + $tva, 2);
                $o->units = $units; $o->lines_count = $count;
                $o->modified = $changed; $o->vendor_message = $msg !== '' ? $msg : null;
                $o->save();
                (new PortalErpBridge())->resync($o);
                return ['ok' => true];
            });
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
        $code = $res['code'] ?? 200; unset($res['code']);
        return response()->json($res, $code);
    }

    public function refuse(Request $request, $id)
    {
        $v = $request->validate(['reason' => 'required|string|max:255']);
        $o = PortalOrder::findOrFail($id);
        if ($o->status !== 'pending') return response()->json(['ok' => false, 'message' => 'Cette commande a déjà été traitée.'], 409);
        if (!(new PortalErpBridge())->discardDraft($o)) return response()->json(['ok' => false, 'message' => 'La commande vente ' . $o->erp_numdoc . ' a déjà été traitée dans l\'ERP : supprimez-la ou annulez-la là-bas.'], 409);
        $o->update(['status' => 'refused', 'refuse_reason' => $v['reason'], 'validated_by' => Auth::id(), 'validated_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function retryErp($id)
    {
        $o = PortalOrder::findOrFail($id);
        $r = (new PortalErpBridge())->sync($o);
        return response()->json(['ok' => $r['ok'], 'message' => $r['ok'] ? 'Commande vente créée.' : $r['message']], $r['ok'] ? 200 : 422);
    }

    public function note(Request $request, $id)
    {
        $v = $request->validate(['internal_note' => 'nullable|string|max:1000']);
        PortalOrder::findOrFail($id)->update(['internal_note' => $v['internal_note'] ?? null]);
        return response()->json(['ok' => true]);
    }

    public function reprint($id)
    {
        $o = PortalOrder::with('customer')->findOrFail($id);
        $s = B2bSetting::current();
        $svc = new PortalOrderService($s, new \App\Models\B2bUser(), $o->customer, new \App\Models\CustomerB2bSetting());
        $ok = $svc->enqueueTicket($o, true);
        return response()->json(['ok' => $ok, 'message' => $ok ? 'Ticket envoyé à l\'imprimante.' : "Impression impossible : configurez l'imprimante et l'agent dans Portail B2B > Paramètres."]);
    }

    /** Flux des alertes : commandes en attente + nouvelles commandes depuis l'id `since` (tous statuts). */
    public function count(Request $request)
    {
        $map = function ($o) {
            return ['id' => $o->id, 'number' => $o->number, 'customer' => optional($o->customer)->name, 'total_ttc' => (float) $o->total_ttc,
                    'status_label' => $o->status_label, 'ago' => $o->created_at->diffForHumans(), 'erp_error' => (bool) $o->erp_error];
        };
        $latest = PortalOrder::with('customer:id,name')->where('status', 'pending')->orderByDesc('id')->limit(6)->get()->map($map)->all();
        $lastId = (int) PortalOrder::max('id');
        $since = (int) $request->query('since', -1);
        $fresh = $since >= 0 && $lastId > $since
            ? PortalOrder::with('customer:id,name')->where('id', '>', $since)->orderBy('id')->limit(5)->get()->map($map)->all()
            : [];
        return response()->json([
            'pending' => PortalOrder::where('status', 'pending')->count(),
            'last_id' => $lastId, 'latest' => $latest, 'fresh' => $fresh,
            'url'     => route('b2b.orders'),
        ]);
    }
}
