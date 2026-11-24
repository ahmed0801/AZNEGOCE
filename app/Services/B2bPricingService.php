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

        // coût d'achat net
        $cost    = (float) $item->cost_price;
        $costNet = round($cost * (1 - ((float) $item->remise_achat) / 100), 2);

        $guard = null;
        if ($this->settings->margin_guard && $costNet > 0) {
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
            'cost_net'       => $costNet,
            'margin_percent' => $margin,
            'steps'          => $steps,
        ];
    }

    private function fmt($v)     { return number_format((float) $v, 2, ',', ' ') . ' €'; }
    private function fmtRate($v) { return rtrim(rtrim(number_format((float) $v, 2, ',', ''), '0'), ','); }
}
