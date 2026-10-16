<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemPriceChange;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

/**
 * Assistant « prix de vente » de l'achat : à partir du coût net saisi sur une ligne, propose un prix de vente
 * (jamais appliqué automatiquement). Marge = (PV − coût net) / coût net, comme sur la page d'achat.
 *
 * Stratégies :
 *  - keep     : garder le taux de marge actuel de l'article (sur l'ancien coût)
 *  - target   : taux de marge cible fixe (param « margin »)
 *  - category : marge médiane des articles de la même catégorie (repli sur « keep » si trop peu de données)
 */
class PriceAdvisorService
{
    /** @var PurchaseDocumentService */
    private $purchases;

    public function __construct(PurchaseDocumentService $purchases)
    {
        $this->purchases = $purchases;
    }

    public static function roundUp(float $price, string $mode): float
    {
        switch ($mode) {
            case '0.05': return round(ceil(round($price / 0.05, 6)) * 0.05, 2);
            case '0.10': return round(ceil(round($price / 0.10, 6)) * 0.10, 2);
            case '1':    return (float) ceil(round($price, 6));
            case '0.90': return max(0.90, round(ceil(round($price + 0.10, 6)) - 0.10, 2));   // …,90
            default:     return round($price, 2);
        }
    }

    /** Marge médiane (en %) des articles de la catégorie, ou null. */
    public function categoryMargin(?int $categoryId): ?float
    {
        if (!$categoryId) return null;
        $rows = Item::where('category_id', $categoryId)->where('cost_price', '>', 0)->where('sale_price', '>', 0)->limit(600)->get(['cost_price', 'remise_achat', 'sale_price']);
        $m = [];
        foreach ($rows as $r) {
            $net = (float) $r->cost_price * (1 - (float) $r->remise_achat / 100);
            if ($net > 0 && $r->sale_price >= $net) $m[] = ((float) $r->sale_price - $net) / $net * 100;
        }
        if (count($m) < 5) return null;
        sort($m);
        $n = count($m);
        return round($n % 2 ? $m[intdiv($n, 2)] : ($m[$n / 2 - 1] + $m[$n / 2]) / 2, 2);
    }

    /**
     * @param  array  $lines  [['code'=>, 'net'=>coût net HT de la ligne]]
     * @param  array  $opt    ['strategy'=>keep|target|category, 'margin'=>%, 'round'=>none|0.05|0.10|1|0.90, 'min_margin'=>%]
     */
    public function review(Supplier $supplier, array $lines, array $opt): array
    {
        $strategy = in_array($opt['strategy'] ?? 'keep', ['keep', 'target', 'category'], true) ? ($opt['strategy'] ?? 'keep') : 'keep';
        $target   = max(0.0, (float) ($opt['margin'] ?? 25));
        $minMargin = max(0.0, (float) ($opt['min_margin'] ?? 10));
        $round    = (string) ($opt['round'] ?? 'none');

        $codes = array_values(array_unique(array_map(function ($l) { return (string) ($l['code'] ?? ''); }, $lines)));
        $items = Item::with('tvaGroup')->whereIn('code', $codes)->get()->keyBy('code');
        $catCache = [];

        $out = [];
        foreach ($lines as $l) {
            $code = (string) ($l['code'] ?? '');
            $item = $items[$code] ?? null;
            if (!$item) continue;                                  // nouvel article : son PV est saisi sur la ligne
            $newNet = round((float) ($l['net'] ?? 0), 4);
            [$oldCost, $oldRem] = $this->purchases->costFor($item, $supplier);
            $oldNet = round((float) $oldCost * (1 - (float) $oldRem / 100), 4);
            $pv = (float) $item->sale_price;

            $marginNow   = ($newNet > 0 && $pv > 0) ? round(($pv - $newNet) / $newNet * 100, 2) : null;   // marge si on ne touche à rien
            $marginBefore = ($oldNet > 0 && $pv > 0) ? round(($pv - $oldNet) / $oldNet * 100, 2) : null;
            $delta = ($oldNet > 0) ? round(($newNet - $oldNet) / $oldNet * 100, 2) : null;

            $aim = null; $used = $strategy;
            if ($strategy === 'target') { $aim = $target; }
            elseif ($strategy === 'category') {
                $cid = $item->category_id ? (int) $item->category_id : 0;
                if (!array_key_exists($cid, $catCache)) $catCache[$cid] = $this->categoryMargin($cid ?: null);
                $aim = $catCache[$cid];
                if ($aim === null) { $used = 'keep'; }
            }
            if ($used === 'keep') { $aim = $marginBefore !== null ? $marginBefore : $target; if ($marginBefore === null) $used = 'target'; }

            $suggested = $newNet > 0 ? self::roundUp($newNet * (1 + $aim / 100), $round) : $pv;
            $flag = null;
            if ($newNet > 0 && $pv > 0 && $pv < $newNet) $flag = 'perte';
            elseif ($marginNow !== null && $marginNow < $minMargin) $flag = 'marge_faible';
            elseif ($delta !== null && abs($delta) >= 5) $flag = $delta > 0 ? 'hausse' : 'baisse';

            $out[] = [
                'code' => $item->code, 'name' => $item->name, 'old_net' => $oldNet, 'new_net' => $newNet, 'delta_pct' => $delta,
                'pv' => $pv, 'margin_now' => $marginNow, 'margin_before' => $marginBefore, 'aim_margin' => $aim === null ? null : round($aim, 2),
                'strategy_used' => $used, 'suggested' => $suggested, 'changed' => abs($suggested - $pv) >= 0.01, 'flag' => $flag,
            ];
        }
        // les lignes qui demandent une décision d'abord
        $rank = ['perte' => 0, 'marge_faible' => 1, 'hausse' => 2, 'baisse' => 3, null => 4];
        usort($out, function ($a, $b) use ($rank) { return [$rank[$a['flag']], $a['code']] <=> [$rank[$b['flag']], $b['code']]; });
        return $out;
    }

    /** Applique des prix de vente validés par l'utilisateur ; chaque changement est historisé. */
    public function apply(array $changes, ?int $userId, string $strategy = 'manual', string $source = 'achat'): array
    {
        return DB::transaction(function () use ($changes, $userId, $strategy, $source) {
            $done = [];
            foreach ($changes as $c) {
                $new = round((float) ($c['new_pv'] ?? 0), 2);
                if ($new <= 0) continue;
                $item = Item::where('code', (string) ($c['code'] ?? ''))->lockForUpdate()->first();
                if (!$item) continue;
                $old = round((float) $item->sale_price, 2);
                if (abs($old - $new) < 0.005) continue;
                $item->sale_price = $new;
                $item->save();
                ItemPriceChange::create([
                    'item_id' => $item->id, 'article_code' => $item->code, 'old_sale_price' => $old, 'new_sale_price' => $new,
                    'net_cost' => isset($c['net']) ? round((float) $c['net'], 4) : null, 'strategy' => $strategy, 'source' => $source, 'user_id' => $userId,
                ]);
                $done[] = ['code' => $item->code, 'old' => $old, 'new' => $new];
            }
            return $done;
        });
    }
}
