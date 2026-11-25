<?php

namespace App\Services;

use App\Models\B2bSetting;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Promotions à signaler au client (pastille du menu + bandeau et bouton du tableau de bord) :
 * promotions en cours qui le concernent (tous les clients, ou son groupe remise). Ne lève jamais d'exception.
 */
class PortalPromoNotices
{
    private static $memo = [];

    /** @return array ['count' => int, 'list' => [ {id,name,ends,items,is_new} ] ] */
    public static function for(Customer $customer, B2bSetting $s = null)
    {
        if (isset(self::$memo[$customer->id])) return self::$memo[$customer->id];
        $out = ['count' => 0, 'list' => []];
        try {
            $s = $s ?: B2bSetting::current();
            if ($s->quotesOnly() || !Schema::hasTable('portal_promotions')) return self::$memo[$customer->id] = $out;
            $now = now();
            $gid = (int) $customer->discount_group_id;
            $rows = DB::table('portal_promotions as p')
                ->where('p.active', 1)->where('p.starts_at', '<=', $now)->where('p.ends_at', '>=', $now)
                ->whereExists(function ($q) { $q->select(DB::raw(1))->from('portal_promotion_items as i')->whereColumn('i.promotion_id', 'p.id'); })
                ->where(function ($q) use ($gid) {
                    $q->where('p.audience', 'all')->orWhereExists(function ($w) use ($gid) {
                        $w->select(DB::raw(1))->from('portal_promotion_groups as g')->whereColumn('g.promotion_id', 'p.id')->where('g.discount_group_id', $gid);
                    });
                })
                ->orderBy('p.ends_at')->limit(5)
                ->select('p.id', 'p.name', 'p.starts_at', 'p.ends_at', DB::raw('(select count(*) from portal_promotion_items i where i.promotion_id = p.id) as n_items'))->get();
            foreach ($rows as $r) {
                $out['list'][] = ['id' => $r->id, 'name' => $r->name, 'ends' => date('d/m/Y', strtotime($r->ends_at)), 'items' => (int) $r->n_items,
                                  'is_new' => strtotime($r->starts_at) >= time() - 7 * 86400];
            }
            $out['count'] = count($out['list']);
        } catch (\Throwable $e) { }
        return self::$memo[$customer->id] = $out;
    }

    /**
     * Identifiants d'articles des promotions en cours, pris PROMOTION PAR PROMOTION (au plus $perPromo chacune),
     * pour qu'une grosse promotion ne masque jamais les autres. @return int[] identifiants d'articles
     */
    public static function itemIds($perPromo = 100)
    {
        $out = [];
        $now = now();
        $pids = DB::table('portal_promotions')->where('active', 1)->where('starts_at', '<=', $now)->where('ends_at', '>=', $now)->orderBy('ends_at')->pluck('id');
        foreach ($pids as $pid) {
            foreach (DB::table('portal_promotion_items')->where('promotion_id', $pid)->limit($perPromo)->pluck('item_id') as $iid) { $out[$iid] = $iid; }
        }
        return array_values($out);
    }

    private static $hl = [];

    /**
     * Articles en promotion les plus intéressants pour CE client (pour le tableau de bord) : prix calculés côté serveur,
     * uniquement ceux où la promo bat réellement son prix habituel. Ne lève jamais d'exception.
     * @return array[] [code,name,brand,net,list,discount,ends]
     */
    public static function highlights($portalUser, B2bSetting $s, $cs, $limit = 6)
    {
        $key = $portalUser->id;
        if (isset(self::$hl[$key])) return self::$hl[$key];
        $out = [];
        try {
            if ($s->quotesOnly() || !$s->show_prices || ($cs && !$cs->show_prices) || !Schema::hasTable('portal_promotions')) return self::$hl[$key] = [];
            $now = now();
            $ids = self::itemIds(80);
            if ($ids) {
                $customer = $portalUser->customer;
                $items = \App\Models\Item::with(['brand', 'category', 'unit', 'discountGroup'])->whereIn('id', $ids)->where('is_active', 1)
                    ->selectRaw('items.*, ' . PortalCatalogService::STOCK . ' as stock_qty')->get();
                $rows = (new PortalCatalogService($s))->forUser($customer->id, $portalUser->id)->rows($items, $customer, $cs);
                $pricing = new B2bPricingService($s);
                foreach ($rows as $r) {
                    if (empty($r['can_buy']) || empty($r['price']['list_ht'])) continue;
                    $p = $r['price'];
                    $list = (float) $p['list_ht'];
                    $regular = !empty($p['promo']) ? (float) $p['promo']['regular_net'] : (float) $p['net_ht'];
                    foreach ($pricing->allPromosFor($r['id'], $customer, $list) as $pm) {
                        if ($pm['price'] >= $regular) continue;
                        $out[] = ['code' => $r['code'], 'name' => $r['name'], 'brand' => $r['brand'], 'net' => (float) $pm['price'], 'list' => $list,
                                  'discount' => round((1 - $pm['price'] / $list) * 100, 1), 'ends' => date('d/m', strtotime($pm['ends_at'])),
                                  'promo_id' => (int) $pm['promo_id'], 'promo' => (string) $pm['name']];
                    }
                }
                usort($out, function ($a, $b) { return $b['discount'] <=> $a['discount']; });
                // un tour de table entre promotions : le meilleur article de chacune d'abord
                $by = [];
                foreach ($out as $o) { $by[$o['promo_id']][] = $o; }
                $mix = []; $seen = [];
                while ($by && count($mix) < $limit) {
                    foreach ($by as $k => $list) {
                        while ($by[$k] && isset($seen[$by[$k][0]['code']])) array_shift($by[$k]);   // même article déjà montré
                        if ($by[$k]) { $o = array_shift($by[$k]); $seen[$o['code']] = 1; $mix[] = $o; }
                        if (!$by[$k]) unset($by[$k]);
                        if (count($mix) >= $limit) break;
                    }
                }
                $out = $mix;
            }
        } catch (\Throwable $e) { }
        return self::$hl[$key] = $out;
    }
}
