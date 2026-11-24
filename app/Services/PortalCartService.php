<?php

namespace App\Services;

use App\Models\B2bSetting;
use App\Models\B2bUser;
use App\Models\Customer;
use App\Models\CustomerB2bSetting;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

/** Panier du portail : toujours recalculé côté serveur (le navigateur n'envoie jamais un prix). */
class PortalCartService
{
    const MAX_QTY = 9999;

    private $s;
    private $user;
    private $customer;
    private $cs;
    private $pricing;

    public function __construct(B2bSetting $s, B2bUser $user, Customer $customer, CustomerB2bSetting $cs)
    {
        $this->s = $s; $this->user = $user; $this->customer = $customer; $this->cs = $cs;
        $this->pricing = new B2bPricingService($s);
    }

    /** Peut-on ajouter des articles ? (message d'erreur sinon) */
    public function orderBlock()
    {
        if (!$this->cs->can_order) return 'Votre compte est en consultation seule.';
        if ($this->customer->blocked) return 'Votre compte est bloqué : contactez-nous.';
        return null;
    }

    private function clamp($q)
    {
        return max(1, min(self::MAX_QTY, (int) $q));
    }

    public $error = null;

    private function zeroMode() { return $this->s->zero_price_mode ?: 'both'; }

    /** Article à prix 0 que ce client ne peut pas commander (réglage du portail). */
    private function zeroBlocked(Item $item)
    {
        if (!in_array($this->zeroMode(), ['block', 'both'], true)) return false;
        $p = $this->pricing->price($item, $this->customer);
        return $p && $p['net_ht'] !== null && (float) $p['net_ht'] <= 0;
    }

    public function add($itemId, $qty)
    {
        $this->error = null;
        $item = Item::where('is_active', 1)->find($itemId);
        if (!$item) return false;
        if ($this->zeroBlocked($item)) { $this->error = 'Article sur demande : contactez-nous pour obtenir un prix.'; return false; }
        $qty = $this->clamp($qty);
        $row = DB::table('portal_cart_items')->where('b2b_user_id', $this->user->id)->where('item_id', $item->id)->first();
        if ($row) {
            DB::table('portal_cart_items')->where('id', $row->id)->update(['quantity' => $this->clamp($row->quantity + $qty), 'updated_at' => now()]);
        } else {
            DB::table('portal_cart_items')->insert(['b2b_user_id' => $this->user->id, 'item_id' => $item->id, 'quantity' => $qty, 'created_at' => now(), 'updated_at' => now()]);
        }
        return true;
    }

    public function setQty($itemId, $qty)
    {
        DB::table('portal_cart_items')->where('b2b_user_id', $this->user->id)->where('item_id', $itemId)->update(['quantity' => $this->clamp($qty), 'updated_at' => now()]);
    }

    public function remove($itemId)
    {
        DB::table('portal_cart_items')->where('b2b_user_id', $this->user->id)->where('item_id', $itemId)->delete();
    }

    public function clear()
    {
        DB::table('portal_cart_items')->where('b2b_user_id', $this->user->id)->delete();
    }

    /** Contenu du panier avec prix et totaux. */
    public function summary()
    {
        $rows = DB::table('portal_cart_items')->where('b2b_user_id', $this->user->id)->orderBy('id')->get();
        $items = $rows->isEmpty() ? collect() : Item::with(['brand', 'discountGroup'])
            ->select('items.*', DB::raw('COALESCE((SELECT SUM(quantity) FROM stocks WHERE stocks.item_id = items.id),0) AS stock_qty'))
            ->whereIn('items.id', $rows->pluck('item_id')->all())->where('items.is_active', 1)->get()->keyBy('id');

        $showPrices = $this->s->show_prices && $this->cs->show_prices;
        $checkStock = $this->s->show_stock && $this->s->stock_display !== 'hidden';
        $lines = []; $ht = 0.0; $tva = 0.0; $count = 0; $units = 0;

        foreach ($rows as $r) {
            $it = $items->get($r->item_id);
            if (!$it || $this->zeroBlocked($it)) { DB::table('portal_cart_items')->where('id', $r->id)->delete(); continue; }   // article retiré du catalogue (ou devenu « sur demande »)
            $p = $showPrices ? $this->pricing->price($it, $this->customer) : null;
            if ($p && $p['net_ht'] !== null && (float) $p['net_ht'] <= 0 && in_array($this->zeroMode(), ['hide', 'both'], true)) $p = null;   // prix 0 masqué
            $lineHt = $p ? round($p['net_ht'] * $r->quantity, 2) : null;
            if ($p) { $ht += $lineHt; $tva += round($lineHt * $p['tva_rate'] / 100, 2); }
            $lines[] = [
                'id' => $it->id, 'code' => $it->code, 'name' => $it->name, 'brand' => optional($it->brand)->name,
                'qty' => (int) $r->quantity,
                'unit_ht' => $p ? $p['net_ht'] : null,
                'line_ht' => $lineHt,
                'exceeds' => $checkStock && (float) $it->stock_qty < $r->quantity,
            ];
            $count++; $units += (int) $r->quantity;
        }

        $min = (float) $this->s->min_order_amount;
        return [
            'lines'     => $lines,
            'count'     => $count,
            'units'     => $units,
            'prices'    => $showPrices,
            'total_ht'  => $showPrices ? round($ht, 2) : null,
            'total_tva' => $showPrices ? round($tva, 2) : null,
            'total_ttc' => $showPrices ? round($ht + $tva, 2) : null,
            'min_order' => $min,
            'missing'   => ($showPrices && $min > 0 && $ht < $min) ? round($min - $ht, 2) : 0,
            'block'     => $this->orderBlock(),
            'checkout'  => (bool) config('portal.checkout_enabled', false),
            'fulfillment' => $this->cs->fulfillment ?: 'pickup',      // toujours le réglage actuel du compte (modifiable par le vendeur à tout moment)
        ];
    }
}
