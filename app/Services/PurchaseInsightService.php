<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Reception;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Aide à la décision d'achat (projet / commande) : pour chaque article, ce qu'on vend, ce qu'il reste,
 * ce qui est déjà en commande, et ce qu'il faudrait commander. Tout est calculé à partir des mouvements de
 * stock (ventes nettes des retours) — aucune saisie, aucun paramètre obligatoire.
 *
 * Prévision journalière = 50 % rythme des 30 derniers jours + 30 % des 90 derniers + 20 % des 365 derniers,
 * corrigée d'un facteur de saisonnalité si on a plus d'un an d'historique (même période l'an dernier).
 * Besoin = prévision × (délai fournisseur + jours à couvrir) + stock mini − stock − déjà commandé.
 */
class PurchaseInsightService
{
    const SALES_TYPES = ['vente', 'retour_vente', 'annulation_expedition', 'annulation_retour_vente'];

    /** @var PurchaseDocumentService */
    private $purchases;

    public function __construct(PurchaseDocumentService $purchases)
    {
        $this->purchases = $purchases;
    }

    /** Délai moyen réel du fournisseur (jours entre date de commande et réception), repli 7 j. */
    public function leadTime(Supplier $supplier): array
    {
        $rows = Reception::query()
            ->join('purchase_orders', 'purchase_orders.id', '=', 'receptions.purchase_order_id')
            ->where('receptions.supplier_id', $supplier->id)->where('receptions.status', '!=', 'annulée')
            ->where('receptions.reception_date', '>=', Carbon::today()->subDays(540)->toDateString())
            ->orderByDesc('receptions.id')->limit(60)->get(['purchase_orders.order_date as od', 'receptions.reception_date as rd']);
        $days = [];
        foreach ($rows as $r) {
            if (!$r->od || !$r->rd) continue;
            $d = Carbon::parse($r->od)->diffInDays(Carbon::parse($r->rd), false);
            if ($d >= 0 && $d <= 120) $days[] = $d;
        }
        if (count($days) >= 2) {
            sort($days);
            $n = count($days);
            $median = $n % 2 ? $days[intdiv($n, 2)] : ($days[$n / 2 - 1] + $days[$n / 2]) / 2;
            return ['days' => max(1, (int) round($median)), 'samples' => $n, 'source' => 'historique'];
        }
        return ['days' => 7, 'samples' => count($days), 'source' => 'défaut'];
    }

    /** Ancienneté de l'historique de ventes (jours), pour ne pas diluer les moyennes sur un système récent. */
    private function historyDays(): int
    {
        $first = DB::table('stock_movements')->whereIn('type', self::SALES_TYPES)->min('created_at');
        if (!$first) return 0;
        return (int) max(0, Carbon::parse($first)->diffInDays(Carbon::now()));
    }

    /**
     * @param  int[]  $itemIds
     * @return array<int,array>  par item_id : stock, on_order, s30, s90, s365, sly
     */
    private function raw(array $itemIds, int $horizon, ?int $excludeOrderId): array
    {
        $out = [];
        foreach ($itemIds as $id) $out[$id] = ['stock' => 0.0, 'on_order' => 0.0, 's30' => 0.0, 's90' => 0.0, 's365' => 0.0, 'sly' => 0.0];

        $now = Carbon::now();
        $d30 = $now->copy()->subDays(30)->toDateTimeString();
        $d90 = $now->copy()->subDays(90)->toDateTimeString();
        $d365 = $now->copy()->subDays(365)->toDateTimeString();
        $lyFrom = $d365;
        $lyTo = $now->copy()->subDays(365)->addDays($horizon)->toDateTimeString();
        $types = self::SALES_TYPES;
        $in = implode(',', array_fill(0, count($types), '?'));

        foreach (array_chunk($itemIds, 300) as $chunk) {
            foreach (DB::table('stocks')->whereIn('item_id', $chunk)->groupBy('item_id')->selectRaw('item_id, SUM(quantity) q')->get() as $r) {
                $out[(int) $r->item_id]['stock'] = (float) $r->q;
            }

            $sql = "SELECT item_id,
                        SUM(CASE WHEN created_at >= ? THEN -quantity ELSE 0 END) s30,
                        SUM(CASE WHEN created_at >= ? THEN -quantity ELSE 0 END) s90,
                        SUM(-quantity) s365,
                        SUM(CASE WHEN created_at >= ? AND created_at < ? THEN -quantity ELSE 0 END) sly
                    FROM stock_movements
                    WHERE type IN ($in) AND created_at >= ? AND item_id IN (" . implode(',', array_fill(0, count($chunk), '?')) . ")
                    GROUP BY item_id";
            // la fenêtre « même période l'an dernier » démarre il y a 365 j : on lit donc 365 + horizon jours
            $since = $now->copy()->subDays(365 + $horizon)->toDateTimeString();
            $bind = array_merge([$d30, $d90, $lyFrom, $lyTo], $types, [$since], $chunk);
            foreach (DB::select($sql, $bind) as $r) {
                $row = &$out[(int) $r->item_id];
                $row['s30'] = max(0.0, (float) $r->s30);
                $row['s90'] = max(0.0, (float) $r->s90);
                $row['sly'] = max(0.0, (float) $r->sly);
                unset($row);
            }
            // s365 réel (les 365 derniers jours seulement)
            $sql365 = "SELECT item_id, SUM(-quantity) s FROM stock_movements WHERE type IN ($in) AND created_at >= ? AND item_id IN (" . implode(',', array_fill(0, count($chunk), '?')) . ") GROUP BY item_id";
            foreach (DB::select($sql365, array_merge($types, [$d365], $chunk)) as $r) {
                $out[(int) $r->item_id]['s365'] = max(0.0, (float) $r->s);
            }

            $q = DB::table('purchase_order_lines as l')->join('purchase_orders as o', 'o.id', '=', 'l.purchase_order_id')
                ->where('o.status', 'validée')->whereIn('o.reception_status', ['a_recevoir', 'partielle'])
                ->whereIn('l.article_code', DB::table('items')->whereIn('id', $chunk)->select('code'));
            if ($excludeOrderId) $q->where('o.id', '!=', $excludeOrderId);
            $codeToId = DB::table('items')->whereIn('id', $chunk)->pluck('id', 'code')->all();
            foreach ($q->selectRaw('l.article_code c, SUM(CASE WHEN l.ordered_quantity > l.received_quantity THEN l.ordered_quantity - l.received_quantity ELSE 0 END) q')->groupBy('l.article_code')->get() as $r) {
                if (isset($codeToId[$r->c])) $out[(int) $codeToId[$r->c]]['on_order'] = (float) $r->q;
            }
        }
        return $out;
    }

    /**
     * Analyse d'une liste d'articles.
     *
     * @param  array  $opt  ['cover'=>jours à couvrir (45), 'lead'=>délai en jours (auto), 'exclude_order_id'=>commande en cours de modification]
     * @return array{lead:array,cover:int,history_days:int,rows:array<string,array>}  rows indexées par code article
     */
    public function metrics(Supplier $supplier, array $codes, array $opt = []): array
    {
        $cover = max(7, min(365, (int) ($opt['cover'] ?? 45)));
        $leadInfo = $this->leadTime($supplier);
        $lead = isset($opt['lead']) && $opt['lead'] !== '' ? max(0, min(180, (int) $opt['lead'])) : $leadInfo['days'];
        $leadInfo['used'] = $lead;
        $horizon = $lead + $cover;

        $items = Item::with('tvaGroup')->whereIn('code', array_values(array_unique($codes)))->get();
        $history = $this->historyDays();
        $raw = $this->raw($items->pluck('id')->map(function ($i) { return (int) $i; })->all(), $horizon, isset($opt['exclude_order_id']) ? (int) $opt['exclude_order_id'] : null);

        $rows = [];
        foreach ($items as $item) {
            $rows[$item->code] = $this->build($item, $raw[(int) $item->id], $history, $lead, $cover, $horizon, $supplier);
        }
        return ['lead' => $leadInfo, 'cover' => $cover, 'history_days' => $history, 'rows' => $rows];
    }

    private function build(Item $item, array $r, int $history, int $lead, int $cover, int $horizon, Supplier $supplier): array
    {
        $w30 = max(7, min(30, $history ?: 30)); $w90 = max(14, min(90, $history ?: 90)); $w365 = max(30, min(365, $history ?: 365));
        $d = 0.5 * $r['s30'] / $w30 + 0.3 * $r['s90'] / $w90 + 0.2 * $r['s365'] / $w365;

        // saisonnalité : ce qui s'est vendu l'an dernier sur la période à venir, comparé au rythme moyen de l'an dernier
        $season = 1.0;
        if ($history >= 365 + $horizon && $r['s365'] >= 12) {
            $base = $r['s365'] / 365 * $horizon;
            if ($base > 0) $season = max(0.6, min(1.8, $r['sly'] / $base));
        }
        $forecast = $d * $season;

        $stock = $r['stock']; $onOrder = $r['on_order'];
        $stockMin = (float) ($item->stock_min ?? 0);
        $need = $forecast * $horizon + $stockMin - $stock - $onOrder;
        $suggested = $forecast > 0 || $stockMin > $stock + $onOrder ? max(0, (int) ceil(round($need, 6))) : 0;

        $coverNow = $forecast > 0 ? round(max(0, $stock) / $forecast, 1) : null;
        $coverTotal = $forecast > 0 ? round(max(0, $stock + $onOrder) / $forecast, 1) : null;

        if ($r['s365'] <= 0 && $stock > 0)                       $status = 'dormant';
        elseif ($r['s365'] <= 0)                                 $status = 'sans_ventes';
        elseif ($stock <= 0 && $onOrder < $forecast * $lead)     $status = 'rupture';
        elseif ($coverNow !== null && $coverNow < $lead && $onOrder <= 0) $status = 'critique';
        elseif ($coverTotal !== null && $coverTotal < $cover)    $status = 'a_commander';
        elseif ($coverTotal !== null && $coverTotal > max(2 * $cover, 180)) $status = 'surstock';
        else                                                     $status = 'ok';

        $trend = 'flat';
        if ($r['s90'] > 0 || $r['s30'] > 0) {
            $r30 = $r['s30'] / $w30; $r90 = $r['s90'] / $w90;
            if ($r90 > 0 && $r30 > $r90 * 1.25) $trend = 'up'; elseif ($r90 > 0 && $r30 < $r90 * 0.75) $trend = 'down';
            elseif ($r90 <= 0 && $r30 > 0) $trend = 'up';
        }

        // fournisseur le moins cher parmi les 3 renseignés sur la fiche
        [$myCost, $myRem] = $this->purchases->costFor($item, $supplier);
        $myNet = (float) $myCost * (1 - (float) $myRem / 100);
        $alt = null;
        foreach ([['codefournisseur', 'cost_price', 'remise_achat'], ['codefournisseur_2', 'cost_price_2', 'remise_achat_2'], ['codefournisseur_3', 'cost_price_3', 'remise_achat_3']] as [$cf, $cp, $ra]) {
            $code = $item->{$cf} ?? null;
            if (!$code || $code === $supplier->code || !(float) ($item->{$cp} ?? 0)) continue;
            $net = (float) $item->{$cp} * (1 - (float) ($item->{$ra} ?? 0) / 100);
            if ($myNet > 0 && $net < $myNet * 0.97 && ($alt === null || $net < $alt['net'])) {
                $alt = ['supplier_code' => $code, 'net' => round($net, 2), 'gain_pct' => round(($myNet - $net) / $myNet * 100, 1)];
            }
        }

        $pv = (float) $item->sale_price;
        return [
            'code' => $item->code, 'name' => $item->name, 'stock' => round($stock, 2), 'on_order' => round($onOrder, 2),
            's30' => round($r['s30'], 2), 's90' => round($r['s90'], 2), 's365' => round($r['s365'], 2),
            'daily' => round($forecast, 3), 'monthly' => round($forecast * 30, 1), 'trend' => $trend, 'season' => round($season, 2),
            'cover_now' => $coverNow, 'cover_total' => $coverTotal, 'stock_min' => $stockMin,
            'suggested' => $suggested, 'status' => $status,
            'cost_net' => round($myNet, 2), 'sale_price' => $pv,
            'margin_unit' => ($pv > 0 && $myNet > 0) ? round($pv - $myNet, 2) : null,
            'alt' => $alt,
        ];
    }

    /**
     * Articles du fournisseur à réapprovisionner qui ne sont pas déjà dans le document.
     * @return array<int,array>
     */
    public function replenishment(Supplier $supplier, array $excludeCodes, array $opt = []): array
    {
        $code = $supplier->code;
        $candidates = Item::where('is_active', 1)->where(function ($w) use ($code) {
            $w->where('codefournisseur', $code)->orWhere('codefournisseur_2', $code)->orWhere('codefournisseur_3', $code);
        })->whereNotIn('code', $excludeCodes ?: [''])->limit(600)->pluck('code')->all();
        if (!$candidates) return ['lead' => $this->leadTime($supplier), 'rows' => []];

        $m = $this->metrics($supplier, $candidates, $opt);
        $rank = ['rupture' => 0, 'critique' => 1, 'a_commander' => 2];
        $rows = array_values(array_filter($m['rows'], function ($r) use ($rank) { return isset($rank[$r['status']]) && $r['suggested'] > 0; }));
        usort($rows, function ($a, $b) use ($rank) {
            return [$rank[$a['status']], $a['cover_now'] === null ? -1 : $a['cover_now'], $a['code']] <=> [$rank[$b['status']], $b['cover_now'] === null ? -1 : $b['cover_now'], $b['code']];
        });
        $items = Item::with('brand')->whereIn('code', array_column($rows, 'code'))->get()->keyBy('code');
        foreach ($rows as &$r) {
            $it = $items[$r['code']] ?? null;
            [$cost, $rem, $mine] = $it ? $this->purchases->costFor($it, $supplier) : [0, 0, true];
            $r['cost_price'] = (float) $cost; $r['remise_achat'] = (float) $rem; $r['brand'] = $it && $it->brand ? $it->brand->name : null;
            $r['tva'] = $it ? $this->purchases->defaultTva($supplier, $it) : 0;
            $r['own_supplier'] = (bool) $mine;
        }
        unset($r);
        return ['lead' => $m['lead'], 'cover' => $m['cover'], 'rows' => array_slice($rows, 0, 80), 'total' => count($rows)];
    }
}
