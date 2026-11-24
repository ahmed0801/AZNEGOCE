<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\PortalOrder;
use App\Services\PortalCartService;
use App\Services\PortalOrderService;
use Illuminate\Http\Request;

/** Commandes du client connecté : validation du panier, historique, détail, annulation, re-commande. */
class PortalOrderController extends Controller
{
    private function ctx(Request $r)
    {
        $u = $r->attributes->get('portal_user');
        return [$r->attributes->get('portal_settings'), $u, $u->customer, $r->attributes->get('portal_customer_settings')];
    }

    /** Une commande n'est visible que par les utilisateurs du même client. */
    private function mine(Request $r, $id)
    {
        $u = $r->attributes->get('portal_user');
        return PortalOrder::with('lines')->where('customer_id', $u->customer_id)->where('id', $id)->firstOrFail();
    }

    public function store(Request $request)
    {
        if (!config('portal.checkout_enabled', true)) return response()->json(['ok' => false, 'message' => 'La validation en ligne est momentanément indisponible.'], 403);
        $v = $request->validate(['mode' => 'nullable|in:delivery,pickup', 'po_ref' => 'nullable|string|max:60', 'note' => 'nullable|string|max:1000']);
        list($s, $u, $c, $cs) = $this->ctx($request);
        $res = (new PortalOrderService($s, $u, $c, $cs))->place($v);
        if (!$res['ok']) return response()->json(['ok' => false, 'message' => $res['message']], 422);
        $o = $res['order'];
        return response()->json([
            'ok' => true, 'number' => $o->number, 'status' => $o->status, 'auto' => $o->status === 'validated',
            'total_ttc' => (float) $o->total_ttc, 'url' => route('portal.order', ['order' => $o->id], false),
            'cart' => (new PortalCartService($s, $u, $c, $cs))->summary(),
        ]);
    }

    public function index(Request $request)
    {
        list($s, $u, $c, $cs) = $this->ctx($request);
        $orders = PortalOrder::where('customer_id', $c->id)->orderByDesc('id')->paginate(15);
        return view('portal.orders', ['s' => $s, 'pUser' => $u, 'customer' => $c, 'cs' => $cs, 'orders' => $orders]);
    }

    public function show(Request $request, $id)
    {
        list($s, $u, $c, $cs) = $this->ctx($request);
        $o = $this->mine($request, $id);
        return view('portal.order', ['s' => $s, 'pUser' => $u, 'customer' => $c, 'cs' => $cs, 'o' => $o] + $this->tracking($o, $c));
    }

    /** Rattache la requête au client (selon les colonnes présentes : customer_id et/ou numclient). */
    private function mineDoc($q, $c)
    {
        $t = $q->getModel()->getTable();
        if (\Illuminate\Support\Facades\Schema::hasColumn($t, 'customer_id')) return $q->where('customer_id', $c->id);
        if (\Illuminate\Support\Facades\Schema::hasColumn($t, 'numclient')) return $q->where('numclient', $c->code);
        return $q->whereRaw('1 = 0');
    }

    /** Bon de livraison et facture liés à la commande (créés automatiquement ou convertis par le vendeur dans l'ERP), limités à ce client. */
    private function tracking(PortalOrder $o, $c)
    {
        $bl = null; $inv = null;
        try {
            if ($o->erp_delivery_note_id) $bl = $this->mineDoc(DeliveryNote::query(), $c)->where('id', $o->erp_delivery_note_id)->first(['id', 'numdoc', 'delivery_date']);
            if (!$bl && $o->erp_document_id && $o->status === 'validated') $bl = $this->mineDoc(DeliveryNote::query(), $c)->where('sales_order_id', $o->erp_document_id)->orderBy('id')->first(['id', 'numdoc', 'delivery_date']);
            if ($o->erp_invoice_id) $inv = $this->mineDoc(Invoice::query(), $c)->where('id', $o->erp_invoice_id)->first(['id', 'numdoc', 'invoice_date']);
            if (!$inv && $bl) { $iid = InvoiceLine::where('delivery_note_id', $bl->id)->value('invoice_id'); if ($iid) $inv = $this->mineDoc(Invoice::query(), $c)->where('id', $iid)->first(['id', 'numdoc', 'invoice_date']); }
        } catch (\Throwable $e) { }
        return ['bl' => $bl, 'inv' => $inv];
    }

    public function cancel(Request $request, $id)
    {
        $o = $this->mine($request, $id);
        if ($o->status !== 'pending') return back()->with('status', "Cette commande n'est plus modifiable : contactez-nous.");
        if (!(new \App\Services\PortalErpBridge())->discardDraft($o)) return back()->with('status', 'Votre commande est déjà en cours de traitement : contactez-nous pour la modifier.');
        $o->update(['status' => 'cancelled']);
        return redirect()->route('portal.order', ['order' => $o->id])->with('status', 'Commande ' . $o->number . ' annulée.');
    }

    /** Remet les articles d'une ancienne commande dans le panier (aux prix d'aujourd'hui). */
    public function reorder(Request $request, $id)
    {
        list($s, $u, $c, $cs) = $this->ctx($request);
        $cart = new PortalCartService($s, $u, $c, $cs);
        if ($msg = $cart->orderBlock()) return response()->json(['ok' => false, 'message' => $msg], 403);
        $o = $this->mine($request, $id); $n = 0;
        $act = $o->lines->where('removed', false);
        foreach ($act as $l) { if ($cart->add($l->item_id, $l->qty)) $n++; }
        return response()->json(['ok' => true, 'added' => $n, 'missing' => $act->count() - $n, 'cart' => $cart->summary()]);
    }
}
