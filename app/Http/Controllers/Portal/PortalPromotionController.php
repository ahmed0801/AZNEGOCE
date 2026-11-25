<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Services\PortalCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Promotions en cours pour le client connecté (prix calculés côté serveur, uniquement celles qui lui sont réellement favorables). */
class PortalPromotionController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->attributes->get('portal_user');
        $s = $request->attributes->get('portal_settings');
        $cs = $request->attributes->get('portal_customer_settings');
        $customer = $u->customer;
        $showPrices = $s->show_prices && $cs->show_prices;
        $groups = [];

        if ($showPrices) {
            $ids = [];
            try {
                // par promotion (et non globalement) : une grosse première promotion ne doit pas masquer les suivantes
                $ids = \App\Services\PortalPromoNotices::itemIds(400);
                // toutes les promotions en cours qui concernent ce client apparaissent, même si aucun article n'est achetable
                $now = now(); $gid = (int) $customer->discount_group_id;
                $ps = \DB::table('portal_promotions as p')->where('p.active', 1)->where('p.starts_at', '<=', $now)->where('p.ends_at', '>=', $now)
                    ->where(function ($q) use ($gid) {
                        $q->where('p.audience', 'all')->orWhereExists(function ($w) use ($gid) {
                            $w->select(\DB::raw(1))->from('portal_promotion_groups as g')->whereColumn('g.promotion_id', 'p.id')->where('g.discount_group_id', $gid);
                        });
                    })->orderBy('p.ends_at')->get(['p.id', 'p.name', 'p.ends_at']);
                foreach ($ps as $p0) { $groups[$p0->id] = ['id' => $p0->id, 'name' => $p0->name, 'ends' => (string) $p0->ends_at, 'rows' => [], 'hidden' => []]; }
            } catch (\Throwable $e) { }

            if ($ids) {
                $items = Item::with(['brand', 'category', 'unit', 'discountGroup'])->whereIn('id', $ids)->where('is_active', 1)
                    ->selectRaw('items.*, ' . PortalCatalogService::STOCK . ' as stock_qty')->get();
                $rows = (new PortalCatalogService($s))->forUser($customer->id, $u->id)->rows($items, $customer, $cs);
                $pricing = new \App\Services\B2bPricingService($s);
                foreach ($rows as $r) {
                    $p = $r['price'];
                    $list = (float) ($p['list_ht'] ?? 0);
                    // prix habituel du client (sans promo) : le net, ou celui que la promo gagnante a remplacé
                    $regular = !empty($p['promo']) ? (float) $p['promo']['regular_net'] : (float) ($p['net_ht'] ?? 0);
                    foreach ($pricing->allPromosFor($r['id'], $customer, $list, true) as $pm) {
                        $key = $pm['promo_id'];
                        if (!isset($groups[$key])) $groups[$key] = ['id' => $key, 'name' => $pm['name'], 'ends' => $pm['ends_at'], 'rows' => [], 'hidden' => []];
                        // pourquoi une ligne n'est pas proposée (affichée en grisé, pour que la promotion ne paraisse jamais « vide » sans explication)
                        $why = null;
                        if (!$r['can_buy']) $why = 'Indisponible (rupture de stock)';
                        elseif ($pm['skip'] === 'epuise') $why = 'Quantité promotionnelle épuisée';
                        elseif ($pm['skip'] === 'tarif') $why = 'Prix promo non inférieur au tarif';
                        elseif ($pm['price'] >= $regular) $why = 'Votre prix habituel (' . number_format($regular, 2, ',', ' ') . ' € HT) est déjà plus bas';
                        if ($why) { $groups[$key]['hidden'][] = ['code' => $r['code'], 'name' => $r['name'], 'why' => $why]; continue; }
                        $row = $r;
                        $row['pm'] = ['price' => $pm['price'], 'list' => $list, 'regular' => $regular, 'discount' => $list > 0 ? round((1 - $pm['price'] / $list) * 100, 1) : 0,
                                      'remaining' => $pm['remaining'], 'applied' => (float) $p['net_ht'], 'applied_name' => !empty($p['promo']) ? $p['promo']['name'] : null,
                                      'applied_id' => !empty($p['promo']) ? (int) $p['promo']['promo_id'] : null];
                        $groups[$key]['rows'][] = $row;
                    }
                }
                uasort($groups, function ($a, $b) { return strcmp($a['ends'], $b['ends']); });
            }
        }

        return view('portal.promotions', ['s' => $s, 'pUser' => $u, 'customer' => $customer, 'cs' => $cs, 'groups' => $groups, 'showPrices' => $showPrices]);
    }
}
