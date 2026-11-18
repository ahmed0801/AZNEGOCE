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
    // ═══════════════════════════════════════════════════════════
    public function build()
    {
        $cur  = $this->kpis($this->from, $this->to);
        $prevTo   = $this->from->copy()->subDay();
        $prevFrom = $prevTo->copy()->subDays($this->days - 1);
        $prev = $this->kpis($prevFrom, $prevTo);

        $daily    = $this->daily();
        $articles = $this->articlesAll();
        $abc      = $this->abc($articles);
        $groups   = [
            'category'    => $this->group('category'),
            'subcategory' => $this->group('subcategory'),
            'brand'       => $this->group('brand'),
            'supplier'    => $this->group('supplier'),
        ];
        $dorm     = $this->dormants();
        $clients  = $this->clients();
        $vendeurs = $this->vendeurs();

        $data = [
            'filters'  => $this->f,
            'period'   => [
                'from' => $this->from->format('Y-m-d'), 'to' => $this->to->format('Y-m-d'), 'days' => $this->days,
                'prev_from' => $prevFrom->format('Y-m-d'), 'prev_to' => $prevTo->format('Y-m-d'),
            ],
            'kpis'     => ['cur' => $cur, 'prev' => $prev, 'delta' => $this->deltas($cur, $prev)],
            'series'   => $this->series($daily),
            'weekday'  => $this->weekday($daily),
            'articles' => $articles['rows'],
            'abc'      => $abc,
            'alerts'   => $this->alerts($articles['rows']),
            'groups'   => $groups,
            'dormants' => $dorm,
            'clients'  => $clients,
            'vendeurs' => $vendeurs,
        ];
        $data['insights'] = $this->insights($data);

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
        return array_map(function ($r) { return (array) $r; }, DB::select($sql, $bind));
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
    protected function daily()
    {
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $sql = 'SELECT DATE(d.delivery_date) AS day, SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty,'
             . ' SUM(' . self::COST . ') AS cost, SUM(' . self::AMT_K . ') AS ca_k, COUNT(DISTINCT d.id) AS docs'
             . $this->salesFrom($needCu) . $this->salesWhere($this->from, $this->to, $bind, $needCu)
             . ' GROUP BY DATE(d.delivery_date) ORDER BY day';
        return $this->rows($sql, $bind);
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
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $sql = 'SELECT l.article_code AS code, COALESCE(i.name, l.article_code) AS name, i.id AS item_id,'
             . ' COALESCE(bn.name, \'—\') AS brand, COALESCE(cn.name, \'Non classé\') AS category,'
             . ' SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty, SUM(' . self::COST . ') AS cost,'
             . ' SUM(' . self::AMT_K . ') AS ca_k, COUNT(DISTINCT d.id) AS docs, COUNT(DISTINCT d.numclient) AS clients,'
             . ' MAX(d.delivery_date) AS last_sale, COALESCE(' . self::STOCK . ', 0) AS stock'
             . $this->salesFrom($needCu)
             . ' LEFT JOIN ' . $this->tbl(Brand::class) . ' bn ON bn.id = i.brand_id'
             . ' LEFT JOIN ' . $this->tbl(ItemCategory::class) . ' cn ON cn.id = i.category_id'
             . $this->salesWhere($this->from, $this->to, $bind, $needCu)
             . ' GROUP BY l.article_code, i.id, i.name, bn.name, cn.name'
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
                'code' => $r['code'], 'name' => $r['name'], 'brand' => $r['brand'], 'category' => $r['category'],
                'qty' => $this->num($qty), 'ca' => $this->num($ca), 'avg_price' => $qty > 0 ? $this->num($ca / $qty) : 0,
                'margin' => $this->num($caK - $cost),
                'margin_pc' => $caK > 0 ? $this->num(($caK - $cost) / $caK * 100, 1) : null,
                'docs' => (int) $r['docs'], 'clients' => (int) $r['clients'],
                'last_sale' => $r['last_sale'] ? substr($r['last_sale'], 0, 10) : null,
                'stock' => $this->num($stock),
                'coverage' => ($perDay > 0 && $stock > 0) ? (int) round($stock / $perDay) : null,
                'rotation' => ($stock > 0 && $qty > 0) ? $this->num($qty * 365 / $this->days / $stock, 1) : null,
                'share' => $this->num($share, 1), 'cum' => $this->num($total > 0 ? $cum / $total * 100 : 0, 1),
                'class' => $class,
                'per_day' => $perDay,
            ];
        }
        return ['rows' => $rows, 'total' => $total];
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

    protected function group($dim)
    {
        $c = $this->dimConfig($dim);
        $bind = [];
        $needCu = (bool) $this->f['client'];
        $sql = 'SELECT ' . $c['key'] . ' AS k, ' . $c['label'] . ' AS label,'
             . ' SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty, SUM(' . self::COST . ') AS cost,'
             . ' SUM(' . self::AMT_K . ') AS ca_k, COUNT(DISTINCT l.article_code) AS sold, COUNT(DISTINCT d.id) AS docs'
             . $this->salesFrom($needCu) . $c['join']
             . $this->salesWhere($this->from, $this->to, $bind, $needCu)
             . ' GROUP BY ' . $c['gb'] . ' ORDER BY ca DESC';
        $rows = $this->rows($sql, $bind);

        // Catalogue actif + stock par groupe (taux d'articles vendus, valeur de stock, couverture)
        $b2 = [];
        // Stock via table dérivée (MySQL ONLY_FULL_GROUP_BY interdit la sous-requête corrélée dans un agrégat groupé)
        $sql2 = 'SELECT ' . $c['key'] . ' AS k, COUNT(*) AS catalog, COALESCE(SUM(COALESCE(st.q,0)),0) AS stock_qty,'
              . ' COALESCE(SUM(COALESCE(st.q,0) * ' . self::NET_COST . '),0) AS stock_val'
              . ' FROM items i LEFT JOIN (SELECT item_id, SUM(quantity) AS q FROM stocks GROUP BY item_id) st ON st.item_id = i.id'
              . $c['join'] . ' WHERE i.is_active = 1' . $this->itemFilters($b2, false)
              . ' GROUP BY ' . $c['gb'];
        $cat = [];
        foreach ($this->rows($sql2, $b2) as $r) $cat[(string) $r['k']] = $r;

        $total = 0.0;
        foreach ($rows as $r) $total += (float) $r['ca'];

        $out = [];
        foreach ($rows as $r) {
            $ca = (float) $r['ca']; $qty = (float) $r['qty'];
            $caK = (float) $r['ca_k']; $cost = (float) $r['cost'];
            $k = (string) $r['k'];
            $ct = isset($cat[$k]) ? $cat[$k] : null;
            $catalog = $ct ? (int) $ct['catalog'] : 0;
            $stockQty = $ct ? (float) $ct['stock_qty'] : 0;
            $perDay = $qty / $this->days;
            $out[] = [
                'label' => $r['label'], 'ca' => $this->num($ca), 'share' => $total > 0 ? $this->num($ca / $total * 100, 1) : 0,
                'qty' => $this->num($qty), 'docs' => (int) $r['docs'], 'sold' => (int) $r['sold'],
                'catalog' => $catalog, 'sold_pc' => $catalog > 0 ? $this->num(min(100, $r['sold'] / $catalog * 100), 1) : null,
                'margin' => $this->num($caK - $cost),
                'margin_pc' => $caK > 0 ? $this->num(($caK - $cost) / $caK * 100, 1) : null,
                'stock_qty' => $this->num($stockQty), 'stock_val' => $ct ? $this->num($ct['stock_val']) : 0,
                'coverage' => ($perDay > 0 && $stockQty > 0) ? (int) round($stockQty / $perDay) : null,
            ];
        }
        return $out;
    }

    // ═══════════════════════════════════════════════════════════
    //  DORMANTS : en stock mais aucune vente sur la période
    // ═══════════════════════════════════════════════════════════
    protected function dormants()
    {
        $b = [];
        $where = ' FROM items i'
               . ' LEFT JOIN ' . $this->tbl(Brand::class) . ' bn ON bn.id = i.brand_id'
               . ' LEFT JOIN ' . $this->tbl(ItemCategory::class) . ' cn ON cn.id = i.category_id'
               . ' WHERE i.is_active = 1 AND ' . self::STOCK . ' > 0'
               . ' AND NOT EXISTS (SELECT 1 FROM delivery_note_lines l JOIN delivery_notes d ON d.id = l.delivery_note_id'
               . ' WHERE l.article_code = i.code AND l.delivered_quantity > 0 AND d.status NOT IN (' . self::EXCLUDED_STATUS . ')'
               . ' AND d.delivery_date >= ? AND d.delivery_date < ?)';
        $b[] = $this->from->format('Y-m-d');
        $b[] = $this->to->copy()->addDay()->format('Y-m-d');
        $where .= $this->itemFilters($b, false);

        $sum = $this->rows('SELECT COUNT(*) AS n, COALESCE(SUM(' . self::STOCK . ' * ' . self::NET_COST . '),0) AS val' . $where, $b);
        $sum = $sum ? $sum[0] : ['n' => 0, 'val' => 0];

        $rows = $this->rows(
            'SELECT i.code AS code, i.name AS name, COALESCE(bn.name, \'—\') AS brand, COALESCE(cn.name, \'Non classé\') AS category,'
            . ' ' . self::STOCK . ' AS stock, ' . self::NET_COST . ' AS net_cost, ' . self::STOCK . ' * ' . self::NET_COST . ' AS val'
            . $where . ' ORDER BY val DESC LIMIT 300', $b);

        // dernière vente (tout l'historique) pour ces articles
        $last = [];
        if ($rows) {
            $codes = array_map(function ($r) { return $r['code']; }, $rows);
            $ph = implode(',', array_fill(0, count($codes), '?'));
            $lr = $this->rows('SELECT l.article_code AS code, MAX(d.delivery_date) AS last_sale FROM delivery_note_lines l'
                . ' JOIN delivery_notes d ON d.id = l.delivery_note_id'
                . ' WHERE l.article_code IN (' . $ph . ') AND l.delivered_quantity > 0 AND d.status NOT IN (' . self::EXCLUDED_STATUS . ')'
                . ' GROUP BY l.article_code', $codes);
            foreach ($lr as $r) $last[$r['code']] = substr($r['last_sale'], 0, 10);
        }
        $list = [];
        foreach ($rows as $r) {
            $ls = isset($last[$r['code']]) ? $last[$r['code']] : null;
            $list[] = [
                'code' => $r['code'], 'name' => $r['name'], 'brand' => $r['brand'], 'category' => $r['category'],
                'stock' => $this->num($r['stock']), 'net_cost' => $this->num($r['net_cost'], 4), 'val' => $this->num($r['val']),
                'last_sale' => $ls,
                'idle_days' => $ls ? (int) Carbon::parse($ls)->diffInDays(Carbon::today()) : null,
            ];
        }
        return ['count' => (int) $sum['n'], 'value' => $this->num($sum['val']), 'rows' => $list];
    }

    // ═══════════════════════════════════════════════════════════
    //  CLIENTS / VENDEURS
    // ═══════════════════════════════════════════════════════════
    protected function clients()
    {
        $col = $this->custNameCol();
        $label = $col ? "COALESCE(NULLIF(cu.$col, ''), d.numclient)" : 'd.numclient';
        $bind = [];
        $sql = 'SELECT d.numclient AS code, ' . $label . ' AS name,'
             . ' SUM(' . self::AMT . ') AS ca, SUM(l.delivered_quantity) AS qty, SUM(' . self::COST . ') AS cost, SUM(' . self::AMT_K . ') AS ca_k,'
             . ' COUNT(DISTINCT d.id) AS docs, COUNT(DISTINCT l.article_code) AS articles, MAX(d.delivery_date) AS last_buy'
             . $this->salesFrom(true) . $this->salesWhere($this->from, $this->to, $bind, true)
             . ' AND d.numclient IS NOT NULL'
             . ' GROUP BY d.numclient' . ($col ? ', cu.' . $col : '') . ' ORDER BY ca DESC LIMIT 100';
        $rows = $this->rows($sql, $bind);
        $total = 0.0;
        foreach ($rows as $r) $total += (float) $r['ca'];
        // part sur le CA global de la période (pas seulement le top 100)
        $glob = $this->kpis($this->from, $this->to);
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
        return $out;
    }

    protected function vendeurs()
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

    // ═══════════════════════════════════════════════════════════
    //  INSIGHTS (phrases automatiques)
    // ═══════════════════════════════════════════════════════════
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
        // meilleure marge (groupes significatifs : ≥ 3 % du CA)
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
        if ($d['dormants']['count'] > 0) {
            $out[] = ['type' => 'warn', 'text' => $d['dormants']['count'] . ' article(s) en stock sans aucune vente sur la période immobilisent ' . $m($d['dormants']['value']) . ' (au coût net).'];
        }
        if ($d['clients'] && $d['clients'][0]['share'] >= 10) {
            $out[] = ['type' => 'warn', 'text' => 'Dépendance client : « ' . $d['clients'][0]['name'] . ' » représente ' . str_replace('.', ',', $d['clients'][0]['share']) . ' % du CA.'];
        }
        return $out;
    }
}
