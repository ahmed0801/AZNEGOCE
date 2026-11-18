<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Brand;
use App\Models\ItemCategory;
use App\Models\ItemSubcategory;
use App\Models\Supplier;

/**
 * Statistiques de vente (page + export Excel).
 *
 * Source des ventes : lignes de bons de livraison (delivery_note_lines), comme les
 * suggestions « Souvent vendus avec » : chaque facture a son BL, on ne compte donc
 * jamais deux fois. Les BL annulés / brouillons sont exclus.
 *
 *  CA        = quantité livrée × prix unitaire HT × (1 − remise %)
 *  Marge     = CA − quantité × coût d'achat NET actuel de l'article (cost_price × (1 − remise_achat %))
 *              calculée uniquement sur les lignes dont l'article a un coût > 0 (sinon marge faussée).
 *  Rotation  = quantité vendue annualisée / stock actuel ; couverture = stock / vente moyenne par jour.
 *  ABC       = classement de Pareto du CA par article : A = 80 % du CA, B = 15 % suivants, C = le reste.
 *
 * Compatible PHP 7.4 / MySQL. Aucune fonction de date propre à MySQL (agrégation par jour,
 * regroupement mois/semaine fait en PHP).
 */
class SalesStatsService
{
    const EXCLUDED_STATUS = "'cancelled','canceled','annule','annulé','draft','brouillon'";

    // Fragments SQL
    const AMT   = '(l.delivered_quantity * l.unit_price_ht * (1 - COALESCE(l.remise,0) / 100))';
    const COST  = '(CASE WHEN COALESCE(i.cost_price,0) > 0 THEN l.delivered_quantity * i.cost_price * (1 - COALESCE(i.remise_achat,0) / 100) ELSE 0 END)';
    const AMT_K = '(CASE WHEN COALESCE(i.cost_price,0) > 0 THEN (l.delivered_quantity * l.unit_price_ht * (1 - COALESCE(l.remise,0) / 100)) ELSE 0 END)';
    const STOCK = '(SELECT COALESCE(SUM(s.quantity),0) FROM stocks s WHERE s.item_id = i.id)';
    const NET_COST = '(COALESCE(i.cost_price,0) * (1 - COALESCE(i.remise_achat,0) / 100))';

    protected $f;          // filtres normalisés
    protected $from;       // Carbon début
    protected $to;         // Carbon fin (inclus)
    protected $days;       // nb jours de la période
    protected $custCol;    // colonne nom client
    protected $timings = [];   // [libellé, ms, nb lignes] de chaque requête (diagnostic ?debug=1)
    protected $clientsMeta = ['count' => 0, 'ca' => 0];
    protected $catalogAgg;     // agrégats catalogue (une seule lecture de la table items)

    public static function normalize(array $in)
    {
        $parse = function ($v) {
            if (!$v) return null;
            try { return Carbon::parse($v)->startOfDay(); } catch (\Exception $e) { return null; }
        };
        $to   = $parse(isset($in['date_to']) ? $in['date_to'] : null) ?: Carbon::today();
        $from = $parse(isset($in['date_from']) ? $in['date_from'] : null) ?: $to->copy()->subDays(89);
        if ($from->gt($to)) { $tmp = $from; $from = $to; $to = $tmp; }
        if ($from->diffInDays($to) > 1830) $from = $to->copy()->subDays(1830); // 5 ans max

        $g = function ($k) use ($in) {
            return isset($in[$k]) && $in[$k] !== '' && $in[$k] !== null ? $in[$k] : null;
        };

        return [
            'date_from'       => $from->format('Y-m-d'),
            'date_to'         => $to->format('Y-m-d'),
            'brand_id'        => $g('brand_id') ? (int) $g('brand_id') : null,
            'category_id'     => $g('category_id') ? (int) $g('category_id') : null,
            'subcategory_id'  => $g('subcategory_id') ? (int) $g('subcategory_id') : null,
            'codefournisseur' => $g('codefournisseur'),
            'client'          => $g('client') ? trim($g('client')) : null,
            'vendeur'         => $g('vendeur') ? trim($g('vendeur')) : null,
            'search'          => $g('search') ? trim($g('search')) : null,
        ];
    }

    public function __construct(array $filters)
    {
        $this->f    = self::normalize($filters);
        $this->from = Carbon::parse($this->f['date_from'])->startOfDay();
        $this->to   = Carbon::parse($this->f['date_to'])->startOfDay();
        $this->days = max(1, $this->from->diffInDays($this->to) + 1);
    }

    public function filters() { return $this->f; }

    // ═══════════════════════════════════════════════════════════
    //  POINT D'ENTRÉE
    //  La page charge d'abord core() (rapide : une seule passe sur les lignes de vente),
    //  puis dormants / clients / vendeurs à la demande (AJAX) car plus coûteux.
    // ═══════════════════════════════════════════════════════════
    public function core()
    {
        $prevTo   = $this->from->copy()->subDay();
        $prevFrom = $prevTo->copy()->subDays($this->days - 1);

        // UNE seule passe sur les lignes pour la période courante ET la précédente (ventes par jour)
        $all = $this->dailyRange($prevFrom, $this->to);
        $daily = []; $prevRows = [];
        $cut = $this->from->format('Y-m-d');
        foreach ($all as $r) { if (substr($r['day'], 0, 10) >= $cut) $daily[] = $r; else $prevRows[] = $r; }
        $cur  = $this->kpisLight($daily, $this->days);
        $prev = $this->kpisLight($prevRows, $this->days);

        $articles = $this->articlesAll();      // 2e et dernière requête lourde ; groupes / ABC / alertes en sont dérivés en PHP
        $rows     = $articles['rows'];
        $cur['articles'] = count($rows);
        $abc      = $this->abc($articles);
        $groups   = [
            'category'    => $this->group('category', $rows),
            'subcategory' => $this->group('subcategory', $rows),
            'brand'       => $this->group('brand', $rows),
            'supplier'    => $this->group('supplier', $rows),
        ];
        $this->addDetail($rows, 300);

        $data = [
            'filters'  => $this->f,
            'period'   => [
                'from' => $this->from->format('Y-m-d'), 'to' => $this->to->format('Y-m-d'), 'days' => $this->days,
                'prev_from' => $prevFrom->format('Y-m-d'), 'prev_to' => $prevTo->format('Y-m-d'),
            ],
            'generated_at' => date('d/m/Y H:i'),
            'timings'  => [],
            'kpis'     => ['cur' => $cur, 'prev' => $prev, 'delta' => $this->deltas($cur, $prev)],
            'series'   => $this->series($daily),
            'weekday'  => $this->weekday($daily),
            'articles' => $rows,
            'abc'      => $abc,
            'alerts'   => $this->alerts($rows),
            'groups'   => $groups,
        ];
        $data['insights'] = $this->insights($data);
        $data['timings'] = $this->timings;
        return $data;
    }

    /** Version complète (export Excel) : core + sections lourdes */
    public function build()
    {
        $data = $this->core();
        $data['dormants'] = $this->dormants();
        $cf = $this->clientsFull(); $data['clients'] = $cf['rows'];
        $data['kpis']['cur']['clients'] = $cf['count'];
        $data['vendeurs'] = $this->vendeurs();
        $data['insights'] = array_merge($data['insights'], $this->lateInsights($data['dormants'], $data['clients']));
        return $data;
    }

    // ═══════════════════════════════════════════════════════════
    //  BRIQUES SQL
    // ═══════════════════════════════════════════════════════════
    protected function custNameCol()
    {
        if ($this->custCol !== null) return $this->custCol ?: null;
        $this->custCol = '';
        try {
            $cols = Schema::getColumnListing('customers');
            foreach (['name', 'raison_sociale', 'company_name', 'nom', 'full_name'] as $c) {
                if (in_array($c, $cols, true)) { $this->custCol = $c; break; }
            }
        } catch (\Exception $e) {
        }
        return $this->custCol ?: null;
    }

    /** FROM … JOIN … commun aux requêtes de ventes */
    protected function salesFrom($withCustomer = false)
    {
        $sql = ' FROM delivery_note_lines l'
             . ' JOIN delivery_notes d ON d.id = l.delivery_note_id'
             . ' LEFT JOIN items i ON i.code = l.article_code';
        if ($withCustomer) $sql .= ' LEFT JOIN customers cu ON cu.code = d.numclient';
        return $sql;
    }

    /** WHERE commun aux requêtes de ventes ; remplit $bind */
    protected function salesWhere(Carbon $from, Carbon $to, array &$bind, $withCustomer = false, $useClientFilters = true)
    {
        $sql = ' WHERE l.delivered_quantity > 0 AND d.status NOT IN (' . self::EXCLUDED_STATUS . ')'
             . ' AND d.delivery_date >= ? AND d.delivery_date < ?';
        $bind[] = $from->format('Y-m-d');
        $bind[] = $to->copy()->addDay()->format('Y-m-d');
        $sql .= $this->itemFilters($bind, true);

        if ($useClientFilters) {
            if ($this->f['vendeur']) { $sql .= ' AND d.vendeur = ?'; $bind[] = $this->f['vendeur']; }
            if ($this->f['client']) {
                $like = '%' . $this->f['client'] . '%';
                $sql .= ' AND (d.numclient LIKE ?';
                $bind[] = $like;
                if ($withCustomer && $this->custNameCol()) {
                    $sql .= ' OR cu.' . $this->custNameCol() . ' LIKE ?';
                    $bind[] = $like;
                }
                $sql .= ')';
            }
        }
        return $sql;
    }

    /** Filtres article (marque, famille, sous-famille, fournisseur, recherche) */
    protected function itemFilters(array &$bind, $onLines)
    {
        $sql = '';
        if ($this->f['brand_id'])        { $sql .= ' AND i.brand_id = ?';        $bind[] = $this->f['brand_id']; }
        if ($this->f['category_id'])     { $sql .= ' AND i.category_id = ?';     $bind[] = $this->f['category_id']; }
        if ($this->f['subcategory_id'])  { $sql .= ' AND i.subcategory_id = ?';  $bind[] = $this->f['subcategory_id']; }
        if ($this->f['codefournisseur']) { $sql .= ' AND i.codefournisseur = ?'; $bind[] = $this->f['codefournisseur']; }
        if ($this->f['search']) {
            $like = '%' . $this->f['search'] . '%';
            $sql .= ' AND (i.code LIKE ? OR i.name LIKE ?' . ($onLines ? ' OR l.article_code LIKE ?' : '') . ')';
            $bind[] = $like; $bind[] = $like;
            if ($onLines) $bind[] = $like;
        }
        return $sql;
    }

    protected function rows($sql, array $bind)
    {
        $t = microtime(true);
        $r = array_map(function ($x) { return (array) $x; }, DB::select($sql, $bind));
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $this->timings[] = [isset($bt[1]['function']) ? $bt[1]['function'] : 'rows', (int) round((microtime(true) - $t) * 1000), count($r)];
        return $r;
    }

    public function timings() { return $this->timings; }

    /** Diagnostic (?debug=1) : volumétrie, collations des colonnes de jointure, index présents */
    public function diagnostics()
    {
        $out = ['counts' => [], 'collations' => [], 'indexes' => []];
        try {
            foreach (['delivery_note_lines', 'delivery_notes', 'items', 'stocks'] as $t) {
                $r = DB::select('SELECT TABLE_ROWS AS n FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?', [$t]);
                $out['counts'][$t] = $r ? (int) $r[0]->n : null;
            }
            foreach ([['items', 'code'], ['delivery_note_lines', 'article_code'], ['delivery_notes', 'numclient'], ['customers', 'code']] as $c) {
                $r = DB::select('SELECT COLLATION_NAME AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?', [$c[0], $c[1]]);
                $out['collations'][$c[0] . '.' . $c[1]] = $r ? $r[0]->c : null;
            }
            foreach (['delivery_note_lines', 'delivery_notes', 'stocks', 'items'] as $t) {
                $names = [];
                foreach (DB::select('SHOW INDEX FROM `' . $t . '`') as $i) $names[$i->Key_name][] = $i->Column_name;
                $out['indexes'][$t] = array_map(function ($c) { return implode(',', $c); }, $names);
            }
        } catch (\Exception $e) { $out['error'] = $e->getMessage(); }
        return $out;
    }

    protected function num($v, $d = 2) { return round((float) $v, $d); }

    // ═══════════════════════════════════════════════════════════
    //  KPI
    // ═══════════════════════════════════════════════════════════
    public function kpis(Carbon $from, Carbon $to)
    {
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $sql = 'SELECT COALESCE(SUM(' . self::AMT . '),0) AS ca, COALESCE(SUM(l.delivered_quantity),0) AS qty,'
             . ' COALESCE(SUM(' . self::COST . '),0) AS cost, COALESCE(SUM(' . self::AMT_K . '),0) AS ca_k,'
             . ' COUNT(DISTINCT d.id) AS docs, COUNT(DISTINCT d.numclient) AS clients, COUNT(DISTINCT l.article_code) AS articles'
             . $this->salesFrom($needCu) . $this->salesWhere($from, $to, $bind, $needCu);
        $r = $this->rows($sql, $bind);
        $r = $r ? $r[0] : [];

        $ca = $this->num(isset($r['ca']) ? $r['ca'] : 0);
        $caK = $this->num(isset($r['ca_k']) ? $r['ca_k'] : 0);
        $cost = $this->num(isset($r['cost']) ? $r['cost'] : 0);
        $docs = (int) (isset($r['docs']) ? $r['docs'] : 0);
        $margin = $caK - $cost;

        return [
            'ca'        => $ca,
            'qty'       => $this->num(isset($r['qty']) ? $r['qty'] : 0, 2),
            'docs'      => $docs,
            'clients'   => (int) (isset($r['clients']) ? $r['clients'] : 0),
            'articles'  => (int) (isset($r['articles']) ? $r['articles'] : 0),
            'basket'    => $docs ? $this->num($ca / $docs) : 0,
            'margin'    => $this->num($margin),
            'margin_pc' => $caK > 0 ? $this->num($margin / $caK * 100, 1) : null,
            'per_day'   => $this->num($ca / max(1, $from->diffInDays($to) + 1)),
        ];
    }

    protected function deltas(array $cur, array $prev)
    {
        $d = [];
        foreach (['ca', 'qty', 'docs', 'clients', 'articles', 'basket', 'margin', 'per_day'] as $k) {
            $p = (float) $prev[$k];
            $d[$k] = $p > 0 ? $this->num(((float) $cur[$k] - $p) / $p * 100, 1) : null;
        }
        $d['margin_pc'] = ($cur['margin_pc'] !== null && $prev['margin_pc'] !== null)
            ? $this->num($cur['margin_pc'] - $prev['margin_pc'], 1) : null; // en points
        return $d;
    }

    // ═══════════════════════════════════════════════════════════
    //  ÉVOLUTION
    // ═══════════════════════════════════════════════════════════
    protected function dailyRange(Carbon $from, Carbon $to)
    {
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $sql = 'SELECT DATE(d.delivery_date) AS day, SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty,'
             . ' SUM(' . self::COST . ') AS cost, SUM(' . self::AMT_K . ') AS ca_k, COUNT(DISTINCT d.id) AS docs'
             . $this->salesFrom($needCu) . $this->salesWhere($from, $to, $bind, $needCu)
             . ' GROUP BY DATE(d.delivery_date) ORDER BY day';
        return $this->rows($sql, $bind);
    }

    /** KPI calculés à partir des lignes journalières (les BL ont une seule date : les « docs » s'additionnent).
     *  clients actifs : chargés ensuite avec la section clients ; références vendues : déduites du classement articles. */
    protected function kpisLight(array $rows, $days)
    {
        $ca = $qty = $cost = $caK = 0.0; $docs = 0;
        foreach ($rows as $r) { $ca += (float) $r['ca']; $qty += (float) $r['qty']; $cost += (float) $r['cost']; $caK += (float) $r['ca_k']; $docs += (int) $r['docs']; }
        $margin = $caK - $cost;
        return [
            'ca' => $this->num($ca), 'qty' => $this->num($qty), 'docs' => $docs, 'clients' => null, 'articles' => null,
            'basket' => $docs ? $this->num($ca / $docs) : 0,
            'margin' => $this->num($margin), 'margin_pc' => $caK > 0 ? $this->num($margin / $caK * 100, 1) : null,
            'per_day' => $this->num($ca / max(1, $days)),
        ];
    }

    protected function series(array $daily)
    {
        $gran = $this->days <= 62 ? 'day' : ($this->days <= 200 ? 'week' : 'month');
        $buckets = [];
        // squelette complet (pas de trous)
        $c = $this->from->copy();
        while ($c->lte($this->to)) {
            $k = $this->bucketKey($c, $gran);
            if (!isset($buckets[$k])) $buckets[$k] = ['label' => $this->bucketLabel($c, $gran), 'ca' => 0, 'qty' => 0, 'margin' => 0, 'docs' => 0];
            $c->addDay();
        }
        foreach ($daily as $r) {
            $dt = Carbon::parse($r['day']);
            $k = $this->bucketKey($dt, $gran);
            if (!isset($buckets[$k])) continue;
            $buckets[$k]['ca']     += (float) $r['ca'];
            $buckets[$k]['qty']    += (float) $r['qty'];
            $buckets[$k]['margin'] += (float) $r['ca_k'] - (float) $r['cost'];
            $buckets[$k]['docs']   += (int) $r['docs'];
        }
        $out = ['granularity' => $gran, 'labels' => [], 'ca' => [], 'margin' => [], 'qty' => [], 'docs' => []];
        foreach ($buckets as $b) {
            $out['labels'][] = $b['label'];
            $out['ca'][]     = round($b['ca'], 2);
            $out['margin'][] = round($b['margin'], 2);
            $out['qty'][]    = round($b['qty'], 2);
            $out['docs'][]   = $b['docs'];
        }
        return $out;
    }

    protected function bucketKey(Carbon $d, $gran)
    {
        if ($gran === 'day') return $d->format('Y-m-d');
        if ($gran === 'week') return $d->copy()->startOfWeek()->format('Y-m-d');
        return $d->format('Y-m');
    }

    protected function bucketLabel(Carbon $d, $gran)
    {
        if ($gran === 'day') return $d->format('d/m');
        if ($gran === 'week') return 'S' . $d->format('W') . ' · ' . $d->copy()->startOfWeek()->format('d/m');
        $m = ['', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
        return $m[(int) $d->format('n')] . ' ' . $d->format('y');
    }

    protected function weekday(array $daily)
    {
        $names = [1 => 'Lun', 2 => 'Mar', 3 => 'Mer', 4 => 'Jeu', 5 => 'Ven', 6 => 'Sam', 7 => 'Dim'];
        $ca = array_fill(1, 7, 0.0);
        foreach ($daily as $r) {
            $n = (int) Carbon::parse($r['day'])->format('N');
            $ca[$n] += (float) $r['ca'];
        }
        $labels = []; $vals = [];
        foreach ($names as $n => $lab) { $labels[] = $lab; $vals[] = round($ca[$n], 2); }
        return ['labels' => $labels, 'ca' => $vals];
    }

    // ═══════════════════════════════════════════════════════════
    //  ARTICLES + ABC + ALERTES
    // ═══════════════════════════════════════════════════════════
    protected function articlesAll()
    {
        // Une seule passe, SANS COUNT(DISTINCT) (coûteux) : clients / BL par article sont ajoutés ensuite pour le top (addDetail).
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $sql = 'SELECT l.article_code AS code, COALESCE(i.name, l.article_code) AS name, i.id AS item_id,'
             . ' i.category_id AS cat_id, i.subcategory_id AS sub_id, i.brand_id AS brand_id, i.codefournisseur AS sup_code,'
             . ' bn.name AS g_brand, cn.name AS g_cat, sn.name AS g_sub, su.name AS g_sup,'
             . ' SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty, SUM(' . self::COST . ') AS cost,'
             . ' SUM(' . self::AMT_K . ') AS ca_k, MAX(d.delivery_date) AS last_sale, COALESCE(MAX(stk.q), 0) AS stock'
             . $this->salesFrom($needCu)
             . ' LEFT JOIN (SELECT item_id, SUM(quantity) AS q FROM stocks GROUP BY item_id) stk ON stk.item_id = i.id'
             . ' LEFT JOIN ' . $this->tbl(Brand::class) . ' bn ON bn.id = i.brand_id'
             . ' LEFT JOIN ' . $this->tbl(ItemCategory::class) . ' cn ON cn.id = i.category_id'
             . ' LEFT JOIN ' . $this->tbl(ItemSubcategory::class) . ' sn ON sn.id = i.subcategory_id'
             . ' LEFT JOIN ' . $this->tbl(Supplier::class) . ' su ON su.code = i.codefournisseur'
             . $this->salesWhere($this->from, $this->to, $bind, $needCu)
             . ' GROUP BY l.article_code, i.id, i.name, i.category_id, i.subcategory_id, i.brand_id, i.codefournisseur,'
             . ' bn.name, cn.name, sn.name, su.name'
             . ' ORDER BY ca DESC';
        $raw = $this->rows($sql, $bind);

        $total = 0.0;
        foreach ($raw as $r) $total += (float) $r['ca'];

        $rows = []; $cum = 0.0;
        foreach ($raw as $r) {
            $ca = (float) $r['ca']; $qty = (float) $r['qty'];
            $caK = (float) $r['ca_k']; $cost = (float) $r['cost'];
            $stock = (float) $r['stock'];
            $perDay = $qty / $this->days;
            $share = $total > 0 ? $ca / $total * 100 : 0;
            $before = $total > 0 ? $cum / $total * 100 : 0;
            $class = $before < 80 ? 'A' : ($before < 95 ? 'B' : 'C');
            $cum += $ca;
            $rows[] = [
                'code' => $r['code'], 'name' => $r['name'],
                'brand' => $r['g_brand'] !== null ? $r['g_brand'] : '—', 'category' => $r['g_cat'] !== null ? $r['g_cat'] : 'Non classé',
                'qty' => $this->num($qty), 'ca' => $this->num($ca), 'avg_price' => $qty > 0 ? $this->num($ca / $qty) : 0,
                'margin' => $this->num($caK - $cost),
                'margin_pc' => $caK > 0 ? $this->num(($caK - $cost) / $caK * 100, 1) : null,
                'docs' => null, 'clients' => null,
                'last_sale' => $r['last_sale'] ? substr($r['last_sale'], 0, 10) : null,
                'stock' => $this->num($stock),
                'coverage' => ($perDay > 0 && $stock > 0) ? (int) round($stock / $perDay) : null,
                'rotation' => ($stock > 0 && $qty > 0) ? $this->num($qty * 365 / $this->days / $stock, 1) : null,
                'share' => $this->num($share, 1), 'cum' => $this->num($total > 0 ? $cum / $total * 100 : 0, 1),
                'class' => $class,
                'per_day' => $perDay,
                // données brutes pour dériver les groupes
                '_ca' => $ca, '_qty' => $qty, '_ca_k' => $caK, '_cost' => $cost,
                '_cat' => [$r['cat_id'], $r['g_cat']], '_sub' => [$r['sub_id'], $r['g_sub']],
                '_brand' => [$r['brand_id'], $r['g_brand']], '_sup' => [$r['sup_code'], $r['g_sup']],
            ];
        }
        return ['rows' => $rows, 'total' => $total];
    }

    /** Nb de BL et de clients distincts pour les $limit premiers articles (requête ciblée, bien plus légère que sur tout le catalogue) */
    protected function addDetail(array &$rows, $limit)
    {
        $codes = [];
        foreach (array_slice($rows, 0, $limit) as $r) $codes[] = $r['code'];
        if (!$codes) return;
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $ph = implode(',', array_fill(0, count($codes), '?'));
        $sql = 'SELECT l.article_code AS code, COUNT(DISTINCT d.id) AS docs, COUNT(DISTINCT d.numclient) AS clients'
             . $this->salesFrom($needCu) . $this->salesWhere($this->from, $this->to, $bind, $needCu)
             . ' AND l.article_code IN (' . $ph . ') GROUP BY l.article_code';
        $map = [];
        foreach ($this->rows($sql, array_merge($bind, $codes)) as $r) $map[$r['code']] = $r;
        foreach ($rows as $i => $r) {
            if (isset($map[$r['code']])) {
                $rows[$i]['docs'] = (int) $map[$r['code']]['docs'];
                $rows[$i]['clients'] = (int) $map[$r['code']]['clients'];
            }
        }
    }


    protected function abc(array $articles)
    {
        $out = ['A' => ['n' => 0, 'ca' => 0.0], 'B' => ['n' => 0, 'ca' => 0.0], 'C' => ['n' => 0, 'ca' => 0.0]];
        foreach ($articles['rows'] as $r) {
            $out[$r['class']]['n']++;
            $out[$r['class']]['ca'] += $r['ca'];
        }
        $n = count($articles['rows']);
        foreach ($out as $k => $v) {
            $out[$k]['ca']       = round($v['ca'], 2);
            $out[$k]['ca_pc']    = $articles['total'] > 0 ? round($v['ca'] / $articles['total'] * 100, 1) : 0;
            $out[$k]['n_pc']     = $n > 0 ? round($v['n'] / $n * 100, 1) : 0;
        }
        return $out;
    }

    /** Articles A/B en rupture ou presque : à réapprovisionner en priorité */
    protected function alerts(array $rows)
    {
        $out = [];
        foreach ($rows as $r) {
            if ($r['class'] === 'C') continue;
            $low = $r['stock'] <= 0 || ($r['coverage'] !== null && $r['coverage'] < 15);
            if (!$low) continue;
            $need = max(0, (int) ceil($r['per_day'] * 30 - max(0, $r['stock'])));
            $out[] = [
                'code' => $r['code'], 'name' => $r['name'], 'brand' => $r['brand'], 'class' => $r['class'],
                'qty' => $r['qty'], 'ca' => $r['ca'], 'stock' => $r['stock'], 'coverage' => $r['coverage'],
                'suggest' => $need,
            ];
            if (count($out) >= 60) break;
        }
        return $out;
    }

    // ═══════════════════════════════════════════════════════════
    //  GROUPES : famille, sous-famille, marque, fournisseur
    // ═══════════════════════════════════════════════════════════
    protected function tbl($class)
    {
        return (new $class)->getTable();
    }

    protected function dimConfig($dim)
    {
        switch ($dim) {
            case 'category':
                return ['key' => 'i.category_id', 'label' => "COALESCE(g.name, 'Non classé')",
                        'join' => ' LEFT JOIN ' . $this->tbl(ItemCategory::class) . ' g ON g.id = i.category_id', 'gb' => 'i.category_id, g.name'];
            case 'subcategory':
                return ['key' => 'i.subcategory_id', 'label' => "COALESCE(g.name, 'Non classé')",
                        'join' => ' LEFT JOIN ' . $this->tbl(ItemSubcategory::class) . ' g ON g.id = i.subcategory_id', 'gb' => 'i.subcategory_id, g.name'];
            case 'brand':
                return ['key' => 'i.brand_id', 'label' => "COALESCE(g.name, 'Sans marque')",
                        'join' => ' LEFT JOIN ' . $this->tbl(Brand::class) . ' g ON g.id = i.brand_id', 'gb' => 'i.brand_id, g.name'];
            default: // supplier
                return ['key' => 'i.codefournisseur', 'label' => "COALESCE(g.name, i.codefournisseur, 'Sans fournisseur')",
                        'join' => ' LEFT JOIN ' . $this->tbl(Supplier::class) . ' g ON g.code = i.codefournisseur', 'gb' => 'i.codefournisseur, g.name'];
        }
    }

    /** Groupes dérivés des lignes article (aucune requête de vente supplémentaire) + stats de catalogue */
    protected function group($dim, array $rows)
    {
        $field = ['category' => '_cat', 'subcategory' => '_sub', 'brand' => '_brand', 'supplier' => '_sup'][$dim];
        $none  = ['category' => 'Non classé', 'subcategory' => 'Non classé', 'brand' => 'Sans marque', 'supplier' => 'Sans fournisseur'][$dim];

        $g = [];
        foreach ($rows as $r) {
            list($key, $label) = $r[$field];
            $k = (string) $key;
            if (!isset($g[$k])) $g[$k] = ['label' => $label !== null && $label !== '' ? $label : ($dim === 'supplier' && $key ? $key : $none),
                                          'ca' => 0.0, 'qty' => 0.0, 'cost' => 0.0, 'ca_k' => 0.0, 'sold' => 0];
            $g[$k]['ca']   += $r['_ca'];
            $g[$k]['qty']  += $r['_qty'];
            $g[$k]['cost'] += $r['_cost'];
            $g[$k]['ca_k'] += $r['_ca_k'];
            $g[$k]['sold']++;
        }
        uasort($g, function ($a, $b) { return $b['ca'] <=> $a['ca']; });

        $cat = $this->catalog($dim);
        $total = 0.0;
        foreach ($g as $x) $total += $x['ca'];

        $out = [];
        foreach ($g as $k => $x) {
            $ct = isset($cat[$k]) ? $cat[$k] : null;
            $catalog = $ct ? (int) $ct['catalog'] : 0;
            $stockQty = $ct ? (float) $ct['stock_qty'] : 0;
            $perDay = $x['qty'] / $this->days;
            $out[] = [
                'label' => $x['label'], 'ca' => $this->num($x['ca']), 'share' => $total > 0 ? $this->num($x['ca'] / $total * 100, 1) : 0,
                'qty' => $this->num($x['qty']), 'sold' => $x['sold'],
                'catalog' => $catalog, 'sold_pc' => $catalog > 0 ? $this->num(min(100, $x['sold'] / $catalog * 100), 1) : null,
                'margin' => $this->num($x['ca_k'] - $x['cost']),
                'margin_pc' => $x['ca_k'] > 0 ? $this->num(($x['ca_k'] - $x['cost']) / $x['ca_k'] * 100, 1) : null,
                'stock_qty' => $this->num($stockQty), 'stock_val' => $ct ? $this->num($ct['stock_val']) : 0,
                'coverage' => ($perDay > 0 && $stockQty > 0) ? (int) round($stockQty / $perDay) : null,
            ];
        }
        return $out;
    }

    /** Catalogue actif + stock par groupe : UNE lecture de la table items (curseur), agrégée pour les 4 dimensions */
    protected function catalog($dim)
    {
        if ($this->catalogAgg === null) {
            $agg = ['category' => [], 'subcategory' => [], 'brand' => [], 'supplier' => []];
            $b = [];
            $sql = 'SELECT i.category_id AS category, i.subcategory_id AS subcategory, i.brand_id AS brand, i.codefournisseur AS supplier,'
                 . ' COALESCE(st.q, 0) AS stock, ' . self::NET_COST . ' AS net_cost'
                 . ' FROM items i LEFT JOIN (SELECT item_id, SUM(quantity) AS q FROM stocks GROUP BY item_id) st ON st.item_id = i.id'
                 . ' WHERE i.is_active = 1' . $this->itemFilters($b, false);
            $t = microtime(true); $n = 0;
            foreach (DB::cursor($sql, $b) as $r) {
                $n++;
                $stock = (float) $r->stock; $val = $stock * (float) $r->net_cost;
                foreach ($agg as $d => $_) {
                    $k = (string) $r->{$d};
                    if (!isset($agg[$d][$k])) $agg[$d][$k] = ['catalog' => 0, 'stock_qty' => 0.0, 'stock_val' => 0.0];
                    $agg[$d][$k]['catalog']++;
                    $agg[$d][$k]['stock_qty'] += $stock;
                    $agg[$d][$k]['stock_val'] += $val;
                }
            }
            $this->timings[] = ['catalog (items)', (int) round((microtime(true) - $t) * 1000), $n];
            $this->catalogAgg = $agg;
        }
        return $this->catalogAgg[$dim];
    }

    // ═══════════════════════════════════════════════════════════
    //  DORMANTS : en stock mais aucune vente sur la période
    // ═══════════════════════════════════════════════════════════
    public function dormants()
    {
        // 1) codes vendus sur la période (une seule requête DISTINCT, sans jointure article ni filtre client/vendeur)
        $bs = [$this->from->format('Y-m-d'), $this->to->copy()->addDay()->format('Y-m-d')];
        $sold = [];
        foreach ($this->rows('SELECT DISTINCT l.article_code AS code FROM delivery_note_lines l JOIN delivery_notes d ON d.id = l.delivery_note_id'
            . ' WHERE l.delivered_quantity > 0 AND d.status NOT IN (' . self::EXCLUDED_STATUS . ')'
            . ' AND d.delivery_date >= ? AND d.delivery_date < ?', $bs) as $r) {
            $sold[strtolower(trim($r['code']))] = true;
        }

        // 2) articles actifs en stock (curseur), moins ceux vendus : différence faite en PHP
        $b = [];
        $sql = 'SELECT i.code AS code, i.name AS name, bn.name AS brand, cn.name AS category, st.q AS stock, ' . self::NET_COST . ' AS net_cost'
             . ' FROM items i JOIN (SELECT item_id, SUM(quantity) AS q FROM stocks GROUP BY item_id HAVING SUM(quantity) > 0) st ON st.item_id = i.id'
             . ' LEFT JOIN ' . $this->tbl(Brand::class) . ' bn ON bn.id = i.brand_id'
             . ' LEFT JOIN ' . $this->tbl(ItemCategory::class) . ' cn ON cn.id = i.category_id'
             . ' WHERE i.is_active = 1' . $this->itemFilters($b, false);
        $t = microtime(true); $n = 0; $count = 0; $value = 0.0; $cand = [];
        foreach (DB::cursor($sql, $b) as $r) {
            $n++;
            if (isset($sold[strtolower(trim($r->code))])) continue;
            $val = (float) $r->stock * (float) $r->net_cost;
            $count++; $value += $val;
            $cand[] = [$val, $r->code, $r->name, $r->brand, $r->category, (float) $r->stock, (float) $r->net_cost];
        }
        $this->timings[] = ['dormants (items en stock)', (int) round((microtime(true) - $t) * 1000), $n];
        usort($cand, function ($x, $y) { return $y[0] <=> $x[0]; });
        $cand = array_slice($cand, 0, 300);

        // 3) dernière vente (tout l'historique) pour ces 300 articles seulement
        $last = [];
        if ($cand) {
            $codes = array_map(function ($c) { return $c[1]; }, $cand);
            $ph = implode(',', array_fill(0, count($codes), '?'));
            foreach ($this->rows('SELECT l.article_code AS code, MAX(d.delivery_date) AS last_sale FROM delivery_note_lines l'
                . ' JOIN delivery_notes d ON d.id = l.delivery_note_id'
                . ' WHERE l.article_code IN (' . $ph . ') AND l.delivered_quantity > 0 AND d.status NOT IN (' . self::EXCLUDED_STATUS . ')'
                . ' GROUP BY l.article_code', $codes) as $r) $last[$r['code']] = substr($r['last_sale'], 0, 10);
        }
        $list = [];
        foreach ($cand as $c) {
            $ls = isset($last[$c[1]]) ? $last[$c[1]] : null;
            $list[] = [
                'code' => $c[1], 'name' => $c[2], 'brand' => $c[3] !== null ? $c[3] : '—', 'category' => $c[4] !== null ? $c[4] : 'Non classé',
                'stock' => $this->num($c[5]), 'net_cost' => $this->num($c[6], 4), 'val' => $this->num($c[0]),
                'last_sale' => $ls,
                'idle_days' => $ls ? (int) Carbon::parse($ls)->diffInDays(Carbon::today()) : null,
            ];
        }
        return ['count' => $count, 'value' => $this->num($value), 'rows' => $list, 'timings' => $this->timings];
    }

    // ═══════════════════════════════════════════════════════════
    //  CLIENTS / VENDEURS
    // ═══════════════════════════════════════════════════════════
    public function clients()
    {
        $col = $this->custNameCol();
        $label = $col ? "COALESCE(NULLIF(cu.$col, ''), d.numclient)" : 'd.numclient';
        $bind = [];
        $sql = 'SELECT d.numclient AS code, ' . $label . ' AS name,'
             . ' SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty, SUM(' . self::COST . ') AS cost, SUM(' . self::AMT_K . ') AS ca_k,'
             . ' COUNT(DISTINCT d.id) AS docs, COUNT(DISTINCT l.article_code) AS articles, MAX(d.delivery_date) AS last_buy'
             . $this->salesFrom(true) . $this->salesWhere($this->from, $this->to, $bind, true)
             . ' AND d.numclient IS NOT NULL'
             . ' GROUP BY d.numclient' . ($col ? ', cu.' . $col : '') . ' ORDER BY ca DESC';
        $all = $this->rows($sql, $bind);
        $nb = count($all); $totCa = 0.0;
        foreach ($all as $r) $totCa += (float) $r['ca'];
        $rows = array_slice($all, 0, 100);
        $glob = ['ca' => $totCa];
        $out = [];
        foreach ($rows as $r) {
            $ca = (float) $r['ca']; $caK = (float) $r['ca_k']; $cost = (float) $r['cost'];
            $out[] = [
                'code' => $r['code'], 'name' => $r['name'], 'ca' => $this->num($ca),
                'share' => $glob['ca'] > 0 ? $this->num($ca / $glob['ca'] * 100, 1) : 0,
                'qty' => $this->num($r['qty']), 'docs' => (int) $r['docs'], 'articles' => (int) $r['articles'],
                'basket' => $r['docs'] ? $this->num($ca / $r['docs']) : 0,
                'margin' => $this->num($caK - $cost),
                'margin_pc' => $caK > 0 ? $this->num(($caK - $cost) / $caK * 100, 1) : null,
                'last_buy' => $r['last_buy'] ? substr($r['last_buy'], 0, 10) : null,
            ];
        }
        $this->clientsMeta = ['count' => $nb, 'ca' => $this->num($totCa)];
        return $out;
    }

    /** Clients : top 100 + nombre de clients actifs et CA total (pour le KPI chargé en différé) */
    public function clientsFull()
    {
        $rows = $this->clients();
        return ['rows' => $rows, 'count' => $this->clientsMeta['count'], 'ca' => $this->clientsMeta['ca']];
    }

    public function vendeurs()
    {
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $sql = 'SELECT COALESCE(NULLIF(d.vendeur, \'\'), \'—\') AS vend_label,'
             . ' SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty, SUM(' . self::COST . ') AS cost, SUM(' . self::AMT_K . ') AS ca_k,'
             . ' COUNT(DISTINCT d.id) AS docs, COUNT(DISTINCT d.numclient) AS clients'
             . $this->salesFrom($needCu) . $this->salesWhere($this->from, $this->to, $bind, $needCu)
             . ' GROUP BY vend_label ORDER BY ca DESC';
        $rows = $this->rows($sql, $bind);
        $total = 0.0;
        foreach ($rows as $r) $total += (float) $r['ca'];
        $out = [];
        foreach ($rows as $r) {
            $ca = (float) $r['ca']; $caK = (float) $r['ca_k']; $cost = (float) $r['cost'];
            $out[] = [
                'name' => $r['vend_label'], 'ca' => $this->num($ca), 'share' => $total > 0 ? $this->num($ca / $total * 100, 1) : 0,
                'qty' => $this->num($r['qty']), 'docs' => (int) $r['docs'], 'clients' => (int) $r['clients'],
                'basket' => $r['docs'] ? $this->num($ca / $r['docs']) : 0,
                'margin' => $this->num($caK - $cost),
                'margin_pc' => $caK > 0 ? $this->num(($caK - $cost) / $caK * 100, 1) : null,
            ];
        }
        return $out;
    }

    /** Listes pour les filtres de la page */
    public static function vendeursList()
    {
        try {
            return DB::table('delivery_notes')->whereNotNull('vendeur')->where('vendeur', '<>', '')
                ->distinct()->orderBy('vendeur')->pluck('vendeur')->all();
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function insights(array $d)
    {
        $m = function ($v) { return number_format($v, 0, ',', ' ') . ' €'; };
        $out = [];
        $k = $d['kpis'];

        if ($k['cur']['ca'] <= 0) {
            return [['type' => 'info', 'text' => 'Aucune vente sur la période et les filtres choisis.']];
        }
        if ($k['delta']['ca'] !== null) {
            $up = $k['delta']['ca'] >= 0;
            $out[] = ['type' => $up ? 'good' : 'bad',
                'text' => 'CA ' . $m($k['cur']['ca']) . ' : ' . ($up ? '+' : '') . str_replace('.', ',', $k['delta']['ca']) . ' % par rapport à la période précédente (' . $m($k['prev']['ca']) . ').'];
        }
        foreach ([['category', 'La famille'], ['brand', 'La marque'], ['supplier', 'Le fournisseur']] as $p) {
            $g = $d['groups'][$p[0]];
            if ($g && $g[0]['share'] >= 1) {
                $out[] = ['type' => 'info', 'text' => $p[1] . ' « ' . $g[0]['label'] . ' » réalise ' . str_replace('.', ',', $g[0]['share']) . ' % du CA (' . $m($g[0]['ca']) . ').'];
            }
        }
        $best = null;
        foreach ($d['groups']['brand'] as $g) {
            if ($g['margin_pc'] !== null && $g['share'] >= 3 && ($best === null || $g['margin_pc'] > $best['margin_pc'])) $best = $g;
        }
        if ($best) $out[] = ['type' => 'good', 'text' => 'Marque la plus rentable : « ' . $best['label'] . ' » avec ' . str_replace('.', ',', $best['margin_pc']) . ' % de marge.'];

        $abc = $d['abc'];
        if ($abc['A']['n'] > 0) {
            $out[] = ['type' => 'info', 'text' => $abc['A']['n'] . ' articles (classe A, ' . str_replace('.', ',', $abc['A']['n_pc']) . ' % des références vendues) font ' . str_replace('.', ',', $abc['A']['ca_pc']) . ' % du CA.'];
        }
        if ($d['alerts']) {
            $out[] = ['type' => 'bad', 'text' => count($d['alerts']) . ' article(s) A/B en rupture ou à moins de 15 jours de couverture : à réapprovisionner (onglet Rotation & ABC).'];
        }
        return $out;
    }

    /** Enseignements issus des sections chargées après coup */
    public function lateInsights($dormants, $clients)
    {
        $m = function ($v) { return number_format($v, 0, ',', ' ') . ' €'; };
        $out = [];
        if ($dormants && $dormants['count'] > 0) {
            $out[] = ['type' => 'warn', 'text' => $dormants['count'] . ' article(s) en stock sans aucune vente sur la période immobilisent ' . $m($dormants['value']) . ' (au coût net).'];
        }
        if ($clients && $clients[0]['share'] >= 10) {
            $out[] = ['type' => 'warn', 'text' => 'Dépendance client : « ' . $clients[0]['name'] . ' » représente ' . str_replace('.', ',', $clients[0]['share']) . ' % du CA.'];
        }
        return $out;
    }
}
