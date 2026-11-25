<?php

namespace App\Services;

use App\Models\B2bSetting;
use App\Models\Customer;
use App\Models\DiscountGroup;
use App\Models\Item;

/**
 * Prix affiché / facturé sur le portail B2B. Toujours calculé côté serveur.
 *
 * Règle :
 *  1. Prix de base = prix de vente de l'article (sale_price, HT).
 *  2. Article à « prix net » → aucune remise.
 *  3. Sinon remise = taux du GROUPE REMISE ARTICLE de l'article, dans la colonne choisie par le
 *     PROFIL du groupe remise CLIENT (general / jobber / professionnel).
 *     Sans groupe client, on suit le type du client dans l'ERP (comme en vente interne).
 *     Si le groupe client est en mode « taux fixe », on prend le PLUS ÉLEVÉ entre ce taux fixe garanti
 *     (même colonne) et la remise du groupe article.
 *  4. Pas de cumul : une seule remise s'applique.
 *  4b. Promotion en cours (portail) : prix le plus bas entre promo et prix habituel, sans cumul ; le garde-fou ne la bloque pas (alerte seulement).
 *  5. Garde-fou de marge : le prix ne descend jamais sous (coût d'achat net × (1 + marge minimale)).
 *     Le prix de base reste un plafond (le garde-fou ne peut pas rendre l'article plus cher que le tarif).
 */
class B2bPricingService
{
    const TIERS = ['general', 'jobber', 'professionnel'];

    private $settings;

    public function __construct(B2bSetting $settings = null)
    {
        $this->settings = $settings ?: B2bSetting::current();
    }

    /** Colonne de remise (general | jobber | professionnel) qui s'applique à ce client. */
    public function tierFor(Customer $customer)
    {
        $g = $customer->discountGroup;
        if ($g && $g->type === DiscountGroup::TYPE_CLIENT && in_array($g->price_tier, self::TIERS, true)) {
            return $g->price_tier;
        }
        $t = strtolower((string) $customer->type);
        if ($t === 'jobber') return 'jobber';
        if ($t === 'professionnel') return 'professionnel';
        return 'general';
    }

    public function tierSource(Customer $customer)
    {
        $g = $customer->discountGroup;
        return ($g && $g->type === DiscountGroup::TYPE_CLIENT && in_array($g->price_tier, self::TIERS, true))
            ? 'groupe client « ' . $g->name . ' »'
            : 'type du client (' . ($customer->type ?: 'particulier') . ')';
    }

    private function rateFromGroup($group, $tier)
    {
        if (!$group) return 0.0;
        if ($tier === 'jobber') return (float) $group->discount_rate_jobber;
        if ($tier === 'professionnel') return (float) $group->discount_rate_professionnel;
        return (float) $group->discount_rate;
    }

    /**
     * @return array  list_ht, rate, net_ht, tva_rate, net_ttc, tier, tier_source, prix_net, guard (null|'floor'),
     *                cost_net, margin_percent, steps[] (explication lisible)
     */
    public function price(Item $item, Customer $customer)
    {
        $tier   = $this->tierFor($customer);
        $list   = round((float) $item->sale_price, 2);
        $steps  = [];
        $steps[] = 'Prix tarif HT : ' . $this->fmt($list);

        $rate = 0.0;
        $isNet = (bool) $item->prix_net;
        $source = 'none';
        if ($isNet) {
            $steps[] = 'Article à prix net : aucune remise.';
        } else {
            $group = $item->discountGroup;
            $articleRate = $this->rateFromGroup($group, $tier);
            $steps[] = $group
                ? 'Groupe article « ' . $group->name . ' », colonne ' . $tier . ' (' . $this->tierSource($customer) . ') : −' . $this->fmtRate($articleRate) . ' %'
                : 'Article sans groupe remise : aucune remise de famille.';

            $rate = $articleRate;
            $source = $articleRate > 0 ? 'article' : 'none';

            $cg = $customer->discountGroup;
            if ($cg && $cg->type === DiscountGroup::TYPE_CLIENT && $cg->price_mode === 'fixed') {
                $fixedRate = $this->rateFromGroup($cg, $tier);
                $steps[] = 'Taux fixe garanti du groupe client « ' . $cg->name . ' » : −' . $this->fmtRate($fixedRate) . ' %';
                if ($fixedRate > $articleRate) {
                    $rate = $fixedRate; $source = 'client_fixed';
                    $steps[] = 'On retient le taux le plus avantageux : −' . $this->fmtRate($rate) . ' % (taux fixe du groupe client). Pas de cumul.';
                } else {
                    $steps[] = 'On retient le taux le plus avantageux : −' . $this->fmtRate($rate) . ' % (remise du groupe article). Pas de cumul.';
                }
            }
        }

        $net = round($list * (1 - $rate / 100), 2);

        // Promotion en cours : on retient le prix le plus bas entre la promo et le prix habituel (pas de cumul)
        $promo = null; $promoWins = false;
        $promoHit = $this->promoFor($item, $customer, $list);
        if ($promoHit) {
            $promo = ['name' => $promoHit['name'], 'ends_at' => $promoHit['ends_at'], 'promo_id' => $promoHit['promo_id'], 'price' => $promoHit['price'],
                      'regular_net' => $net, 'remaining' => $promoHit['remaining']];
            if ($promoHit['price'] < $net) {
                $steps[] = 'Promotion « ' . $promoHit['name'] . ' » : ' . $this->fmt($promoHit['price']) . ' (moins cher que le prix habituel ' . $this->fmt($net) . ').';
                $net = $promoHit['price'];
                $rate = $list > 0 ? round((1 - $net / $list) * 100, 2) : 0.0;
                $source = 'promo'; $promoWins = true;
            } else {
                $steps[] = 'Promotion « ' . $promoHit['name'] . ' » (' . $this->fmt($promoHit['price']) . ') non retenue : le prix habituel ' . $this->fmt($net) . ' est plus bas.';
                $promo = null;
            }
        }

        // coût d'achat net
        $cost    = (float) $item->cost_price;
        $costNet = round($cost * (1 - ((float) $item->remise_achat) / 100), 2);

        $guard = null;
        if (!$promoWins && $this->settings->margin_guard && $costNet > 0) {
            $min = round($costNet * (1 + ((float) $this->settings->min_margin_percent) / 100), 2);
            if ($net < $min) {
                $floor = min($min, $list);          // jamais au-dessus du tarif
                if ($floor > $net) {
                    $steps[] = 'Garde-fou de marge : prix relevé de ' . $this->fmt($net) . ' à ' . $this->fmt($floor)
                             . ' (coût net ' . $this->fmt($costNet) . ', marge min ' . $this->fmtRate($this->settings->min_margin_percent) . ' %).';
                    $net = $floor;
                    $guard = 'floor';
                }
            }
        }

        $effective = $list > 0 ? round((1 - $net / $list) * 100, 2) : 0.0;
        $tva = (float) (optional($customer->tvaGroup)->rate ?: 0);
        $margin = $costNet > 0 ? round(($net - $costNet) / $costNet * 100, 1) : null;

        $steps[] = 'Prix client HT : ' . $this->fmt($net) . ' (TVA ' . $this->fmtRate($tva) . ' % → TTC ' . $this->fmt(round($net * (1 + $tva / 100), 2)) . ')';

        return [
            'list_ht'        => $list,
            'rate'           => $rate,
            'rate_source'    => $source,
            'effective_rate' => $effective,
            'net_ht'         => $net,
            'tva_rate'       => $tva,
            'net_ttc'        => round($net * (1 + $tva / 100), 2),
            'tier'           => $tier,
            'tier_source'    => $this->tierSource($customer),
            'prix_net'       => $isNet,
            'guard'          => $guard,
            'promo'          => $promoWins ? $promo : null,
            'below_margin'   => $promoWins && $costNet > 0 && $this->settings->margin_guard
                                && $net < round($costNet * (1 + ((float) $this->settings->min_margin_percent) / 100), 2),
            'cost_net'       => $costNet,
            'margin_percent' => $margin,
            'steps'          => $steps,
        ];
    }

    /** @var array|null  item_id => [lignes de promotions en cours] (une seule requête par requête HTTP) */
    private static $promoCache = null;
    private static $promoCacheAt = 0;

    /** Promotions en cours, indexées par article. Silencieux si les tables n'existent pas encore. */
    private function activePromos()
    {
        if (self::$promoCache !== null && (time() - self::$promoCacheAt) < 30) return self::$promoCache;
        $map = [];
        try {
            $now = now();
            $rows = \DB::table('portal_promotion_items as pi')
                ->join('portal_promotions as p', 'p.id', '=', 'pi.promotion_id')
                ->where('p.active', 1)->where('p.starts_at', '<=', $now)->where('p.ends_at', '>=', $now)
                ->select('pi.item_id', 'pi.mode', 'pi.promo_price', 'pi.percent', 'pi.amount', 'pi.max_qty', 'pi.sold_qty', 'p.id as promo_id', 'p.name', 'p.ends_at', 'p.audience')
                ->get();
            $groups = [];
            foreach (\DB::table('portal_promotion_groups')->get() as $g) { $groups[$g->promotion_id][] = (int) $g->discount_group_id; }
            foreach ($rows as $r) {
                $r->group_ids = isset($groups[$r->promo_id]) ? $groups[$r->promo_id] : [];
                $map[$r->item_id][] = $r;
            }
        } catch (\Throwable $e) { }
        self::$promoCache = $map; self::$promoCacheAt = time();
        return $map;
    }

    /** Vide le cache (après modification d'une promotion). */
    public static function forgetPromos()
    {
        self::$promoCache = null;
    }

    /** Meilleur prix promo applicable à ce client pour cet article, ou null. @return array|null [price, name, ends_at, promo_id, remaining] */
    public function promoFor(Item $item, Customer $customer, $list)
    {
        $all = $this->activePromos();
        if (empty($all[$item->id]) || $list <= 0) return null;
        $best = null;
        foreach ($all[$item->id] as $r) {
            if ($r->audience === 'groups' && !in_array((int) $customer->discount_group_id, $r->group_ids, true)) continue;
            $remaining = $r->max_qty === null ? null : (int) $r->max_qty - (int) $r->sold_qty;
            if ($remaining !== null && $remaining <= 0) continue;
            if ($r->mode === 'percent') $price = round($list * (1 - (float) $r->percent / 100), 2);
            elseif ($r->mode === 'amount') $price = round($list - (float) $r->amount, 2);
            else $price = round((float) $r->promo_price, 2);
            if ($price < 0 || $price >= $list) continue;           // une promo doit être sous le tarif
            if ($best === null || $price < $best['price']) {
                $best = ['price' => $price, 'name' => $r->name, 'ends_at' => (string) $r->ends_at, 'promo_id' => (int) $r->promo_id, 'remaining' => $remaining];
            }
        }
        return $best;
    }

    /**
     * TOUTES les promotions applicables à ce client pour cet article (une ligne par promotion, prix propre à chacune).
     * Sert à la page « Promotions » : une promotion affiche ses articles même si une autre promotion est encore moins chère.
     * @return array[] [price, name, ends_at, promo_id, remaining]
     */
    public function allPromosFor($itemId, Customer $customer, $list, $withSkipped = false)
    {
        $all = $this->activePromos();
        $out = [];
        if (empty($all[$itemId])) return $out;
        foreach ($all[$itemId] as $r) {
            if ($r->audience === 'groups' && !in_array((int) $customer->discount_group_id, $r->group_ids, true)) continue;
            $base = ['name' => $r->name, 'ends_at' => (string) $r->ends_at, 'promo_id' => (int) $r->promo_id, 'remaining' => null, 'price' => 0.0, 'skip' => null];
            $remaining = $r->max_qty === null ? null : (int) $r->max_qty - (int) $r->sold_qty;
            $base['remaining'] = $remaining;
            if ($list <= 0) { if ($withSkipped) { $base['skip'] = 'tarif'; $out[] = $base; } continue; }
            if ($r->mode === 'percent') $price = round($list * (1 - (float) $r->percent / 100), 2);
            elseif ($r->mode === 'amount') $price = round($list - (float) $r->amount, 2);
            else $price = round((float) $r->promo_price, 2);
            $base['price'] = $price;
            if ($remaining !== null && $remaining <= 0) { if ($withSkipped) { $base['skip'] = 'epuise'; $out[] = $base; } continue; }
            if ($price < 0 || $price >= $list) { if ($withSkipped) { $base['skip'] = 'tarif'; $out[] = $base; } continue; }
            $out[] = $base;
        }
        return $out;
    }

    private function fmt($v)     { return number_format((float) $v, 2, ',', ' ') . ' €'; }
    private function fmtRate($v) { return rtrim(rtrim(number_format((float) $v, 2, ',', ''), '0'), ','); }
}
