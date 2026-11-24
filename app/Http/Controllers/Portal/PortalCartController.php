<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Services\PortalCartService;
use App\Services\PortalCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Panier, favoris et liste de références du portail (réponses JSON). */
class PortalCartController extends Controller
{
    private function cart(Request $r)
    {
        $u = $r->attributes->get('portal_user');
        return new PortalCartService($r->attributes->get('portal_settings'), $u, $u->customer, $r->attributes->get('portal_customer_settings'));
    }

    public function summary(Request $request)
    {
        return response()->json(['ok' => true, 'cart' => $this->cart($request)->summary()]);
    }

    public function add(Request $request)
    {
        $cart = $this->cart($request);
        if ($msg = $cart->orderBlock()) return response()->json(['ok' => false, 'message' => $msg], 403);
        $v = $request->validate(['item_id' => 'required|integer', 'qty' => 'required|integer|min:1|max:9999']);
        if (!$cart->add($v['item_id'], $v['qty'])) return response()->json(['ok' => false, 'message' => $cart->error ?: 'Article introuvable.'], $cart->error ? 403 : 404);
        return response()->json(['ok' => true, 'cart' => $cart->summary()]);
    }

    public function update(Request $request)
    {
        $cart = $this->cart($request);
        if ($msg = $cart->orderBlock()) return response()->json(['ok' => false, 'message' => $msg], 403);
        $v = $request->validate(['item_id' => 'required|integer', 'qty' => 'required|integer|min:0|max:9999']);
        if ((int) $v['qty'] === 0) $cart->remove($v['item_id']); else $cart->setQty($v['item_id'], $v['qty']);
        return response()->json(['ok' => true, 'cart' => $cart->summary()]);
    }

    public function clear(Request $request)
    {
        $cart = $this->cart($request);
        $cart->clear();
        return response()->json(['ok' => true, 'cart' => $cart->summary()]);
    }

    public function favorite(Request $request, $itemId)
    {
        $u = $request->attributes->get('portal_user');
        $cid = $u->customer_id;
        if (!Item::where('is_active', 1)->where('id', $itemId)->exists()) return response()->json(['ok' => false], 404);
        $q = DB::table('portal_favorites')->where('customer_id', $cid)->where('item_id', $itemId);
        if ($q->exists()) { $q->delete(); $fav = false; }
        else { DB::table('portal_favorites')->insert(['customer_id' => $cid, 'item_id' => $itemId, 'created_at' => now()]); $fav = true; }
        return response()->json(['ok' => true, 'fav' => $fav, 'count' => DB::table('portal_favorites')->where('customer_id', $cid)->count()]);
    }

    /** Liste collée : une ligne = « référence [quantité] ». mode=check (aperçu) ou add (ajoute les articles trouvés). */
    public function bulk(Request $request)
    {
        $v = $request->validate(['list' => 'required|string|max:8000', 'mode' => 'nullable|in:check,add']);
        $lines = [];
        foreach (preg_split('/\r\n|\r|\n/', $v['list']) as $raw) {
            $raw = trim($raw);
            if ($raw === '') continue;
            // « REF 4 », « REF;4 », « REF x4 », « REF\t4 »
            if (preg_match('/^(.*?)[\s;,:\t]+x?\s*(\d{1,4})$/iu', $raw, $m) && PortalCatalogService::normalizeRef($m[1]) !== '') {
                $ref = trim($m[1]); $qty = (int) $m[2];
            } else { $ref = $raw; $qty = 1; }
            $lines[] = ['ref' => mb_substr($ref, 0, 60), 'qty' => max(1, min(9999, $qty))];
        }
        if (!$lines) return response()->json(['ok' => false, 'message' => 'Aucune référence détectée.'], 422);

        $u = $request->attributes->get('portal_user');
        $s = $request->attributes->get('portal_settings');
        $cs = $request->attributes->get('portal_customer_settings');
        $svc = (new PortalCatalogService($s))->forUser($u->customer_id, $u->id);
        $res = $svc->bulk($lines, $u->customer, $cs);

        $added = 0;
        if (($v['mode'] ?? 'check') === 'add') {
            $cart = $this->cart($request);
            if ($msg = $cart->orderBlock()) return response()->json(['ok' => false, 'message' => $msg], 403);
            foreach ($res as $r) { if ($r['row'] && $cart->add($r['row']['id'], $r['qty'])) $added++; }
        }
        $cartSummary = $this->cart($request)->summary();

        return response()->json([
            'ok' => true, 'added' => $added, 'cart' => $cartSummary,
            'lines' => array_map(function ($r) {
                return ['input' => $r['input'], 'qty' => $r['qty'], 'multi' => $r['multi'], 'found' => (bool) $r['row'],
                    'row' => $r['row'] ? [
                        'id' => $r['row']['id'], 'code' => $r['row']['code'], 'name' => $r['row']['name'], 'brand' => $r['row']['brand'],
                        'stock' => $r['row']['stock'], 'price' => $r['row']['price'],
                    ] : null];
            }, $res),
        ]);
    }
}
