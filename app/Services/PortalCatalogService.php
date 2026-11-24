<?php

namespace App\Services;

use App\Models\B2bSetting;
use App\Models\Customer;
use App\Models\CustomerB2bSetting;
use App\Models\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Catalogue du portail B2B : recherche « métier » (références, codes-barres, désignation), filtres à compteurs,
 * suggestions instantanées, équivalences, stock affiché selon les réglages, prix du service de prix.
 *
 * SÉCURITÉ : ce service ne renvoie QUE des champs publics (référence, désignation, marque, catégorie, unité, TecDoc,
 * stock autorisé, prix client). Jamais de prix d'achat, remise d'achat, code fournisseur, marge ou emplacement.
 */
class PortalCatalogService
{
    const PER_PAGE = 24;
    /** Stock total d'un article : sous-requête corrélée (évalue seulement les articles trouvés, au lieu d'agréger tout le stock à chaque recherche). */
    const STOCK = '(SELECT COALESCE(SUM(sx.quantity), 0) FROM stocks sx WHERE sx.item_id = items.id)';

    private $s;
    private $pricing;
    private $customerId = 0;
    private $userId = 0;
    private static $hasNorm = null;

    public function __construct(B2bSetting $s = null)
    {
        $this->s = $s ?: B2bSetting::current();
        $this->pricing = new B2bPricingService($this->s);
    }

    /** Client et utilisateur courants (favoris, panier). */
    public function forUser($customerId, $userId)
    {
        $this->customerId = (int) $customerId;
        $this->userId = (int) $userId;
        return $this;
    }

    // ───────────────────────── utilitaires ─────────────────────────

    private function hasNormColumns()
    {
        if (self::$hasNorm === null) {
            self::$hasNorm = Cache::remember('portal_has_norm_cols', 3600, function () {
                return Schema::hasColumn('items', 'search_refs') && Schema::hasColumn('items', 'code_norm');
            });
        }
        return self::$hasNorm;
    }

    private function likeEscape($v)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $v);
    }

    public static function normalizeRef($q)
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $q));
    }

    public static function tokens($q)
    {
        $t = array_values(array_filter(preg_split('/\s+/u', mb_strtolower(trim((string) $q))), function ($x) { return $x !== ''; }));
        return array_slice($t, 0, 6);
    }

    /** ref | barcode | text : pour indiquer au client ce que le moteur a compris. */
    public static function kindOf($q)
    {
        $n = self::normalizeRef($q);
        if ($n !== '' && ctype_digit($n) && strlen($n) >= 8 && strlen($n) <= 14 && !preg_match('/[A-Za-z]/', $q)) return 'barcode';
        if (strlen($n) >= 4 && preg_match('/[0-9]/', $n) && !preg_match('/\s{2,}/', $q)) return 'ref';
        return 'text';
    }

    private function cleanIds($v)
    {
        $out = [];
        foreach ((array) $v as $x) {
            if (is_scalar($x) && $x !== '' && strlen((string) $x) <= 40) $out[] = (string) $x;
        }
        return array_slice(array_values(array_unique($out)), 0, 30);
    }

    // ───────────────────────── requêtes ─────────────────────────

    /** Condition « texte » (références + désignation) appliquée à un builder Eloquent ou Query. */
    private function applyQuery($qb, $q, $approx = false)
    {
        $q = trim((string) $q);
        if ($q === '') return;
        $norm = self::normalizeRef($q);
        $tokens = self::tokens($q);

        $qb->where(function ($w) use ($norm, $tokens, $q, $approx) {
            if ($norm !== '') {
                if ($this->hasNormColumns()) {
                    $w->orWhere('items.search_refs', 'LIKE', '%' . $this->likeEscape($norm) . '%');
                } else {
                    $w->orWhere('items.code', 'LIKE', '%' . $this->likeEscape($q) . '%')
                      ->orWhere('items.Ref_TecDoc', 'LIKE', '%' . $this->likeEscape($q) . '%')
                      ->orWhere('items.barcode', 'LIKE', '%' . $this->likeEscape($q) . '%');
                }
            }
            if ($approx) {
                // résultats approchants : au moins un des mots
                $w->orWhere(function ($n) use ($tokens) {
                    foreach ($tokens as $t) {
                        if (mb_strlen($t) >= 3) $n->orWhere('items.name', 'LIKE', '%' . $this->likeEscape($t) . '%');
                    }
                });
            } else {
                // désignation : tous les mots, dans n'importe quel ordre
                $w->orWhere(function ($n) use ($tokens) {
                    foreach ($tokens as $t) {
                        $n->where('items.name', 'LIKE', '%' . $this->likeEscape($t) . '%');
                    }
                });
            }
        });
    }

    private function applyFilters($qb, array $f, $skip = null)
    {
        if ($skip !== 'brand' && !empty($f['brand']))       $qb->whereIn('items.brand_id', $f['brand']);
        if ($skip !== 'category' && !empty($f['category'])) $qb->whereIn('items.category_id', $f['category']);
        if ($skip !== 'sub' && !empty($f['sub']))           $qb->whereIn('items.subcategory_id', $f['sub']);
        if (!empty($f['fav'])) {
            $qb->whereIn('items.id', function ($s) {
                $s->select('item_id')->from('portal_favorites')->where('customer_id', $this->customerId);
            });
        }
    }

    private function baseQuery()
    {
        return Item::query()
            ->select('items.*', DB::raw(self::STOCK . ' AS stock_qty'))
            ->where('items.is_active', 1)
            ->with(['brand', 'category', 'unit', 'discountGroup']);
    }

    private function relevanceOrder($qb, $q, $approx)
    {
        $norm = self::normalizeRef($q);
        if ($norm !== '' && $this->hasNormColumns()) {
            $qb->orderByRaw('CASE WHEN items.code_norm = ? THEN 0 WHEN items.code_norm LIKE ? THEN 1 WHEN items.search_refs LIKE ? THEN 2 ELSE 3 END',
                [$norm, $this->likeEscape($norm) . '%', '%' . $this->likeEscape($norm) . '%']);
        }
        if ($approx) {
            $tokens = array_filter(self::tokens($q), function ($t) { return mb_strlen($t) >= 3; });
            if ($tokens) {
                $parts = []; $bind = [];
                foreach ($tokens as $t) { $parts[] = '(items.name LIKE ?)'; $bind[] = '%' . $this->likeEscape($t) . '%'; }
                $qb->orderByRaw('(' . implode(' + ', $parts) . ') DESC', $bind);
            }
        }
    }

    /** Requête de recherche complète (sans pagination). */
    private function searchQuery(array $f, $approx)
    {
        $qb = $this->baseQuery();
        $this->applyQuery($qb, $f['q'] ?? '', $approx);
        $this->applyFilters($qb, $f);
        if (!empty($f['stock'])) $qb->whereRaw(self::STOCK . ' > 0');
        return $qb;
    }

    /** @return array ['pager', 'equiv', 'facets', 'approx'] */
    public function search(Customer $customer, CustomerB2bSetting $cs, array $f, $page = 1)
    {
        $q = trim((string) ($f['q'] ?? ''));
        $approx = false;

        // la pagination fait déjà le comptage : on ne compte plus une seconde fois « pour voir »
        $tryApprox = $q !== '' && count(self::tokens($q)) >= 2;
        $qb = $this->searchQuery($f, false);
        if ($tryApprox && !(clone $qb)->limit(1)->exists()) {
            $approx = true;
            $qb = $this->searchQuery($f, true);
        }

        switch ($f['sort'] ?? 'relevance') {
            case 'price_asc':  $qb->orderBy('items.sale_price'); break;
            case 'price_desc': $qb->orderByDesc('items.sale_price'); break;
            case 'stock':      $qb->orderByDesc('stock_qty'); break;
            case 'ref':        $qb->orderBy('items.code'); break;
            default:
                if ($q !== '') $this->relevanceOrder($qb, $q, $approx);
                $qb->orderByRaw('(' . self::STOCK . ' > 0) DESC');   // ce qu'on peut livrer d'abord
        }
        $qb->orderBy('items.code');

        $pager = $qb->paginate(self::PER_PAGE, ['*'], 'page', $page);
        $items = $pager->getCollection();

        // équivalents des meilleurs résultats (page 1, recherche texte)
        $equivRows = [];
        if ($q !== '' && $page == 1 && $items->count()) {
            $ids = $this->equivalentIds($items->take(3)->pluck('id')->all(), $items->pluck('id')->all());
            if ($ids) {
                $eq = $this->baseQuery()->whereIn('items.id', $ids)
                    ->orderByRaw('(' . self::STOCK . ' > 0) DESC')->orderBy('items.code')->limit(8)->get();
                $equivRows = $this->rows($eq, $customer, $cs);
            }
        }

        $pager->setCollection(collect($this->rows($items, $customer, $cs)));
        return ['pager' => $pager, 'equiv' => $equivRows, 'facets' => $this->facets($f, $approx), 'approx' => $approx];
    }

    /** Compteurs des filtres (marques, catégories) pour la recherche en cours, chaque filtre ignorant sa propre sélection. */
    public function facets(array $f, $approx = false)
    {
        $opts = $this->filterOptions();

        // UN SEUL balayage des articles qui correspondent au texte (au lieu d'un par filtre), puis comptage en mémoire.
        $key = 'portal_facets_' . md5(json_encode([$f['q'] ?? '', !empty($f['stock']), $approx]));
        $rows = Cache::remember($key, 120, function () use ($f, $approx) {
            $qb = DB::table('items')->where('items.is_active', 1);
            if (!empty($f['stock'])) $qb->whereRaw(self::STOCK . ' > 0');
            $this->applyQuery($qb, $f['q'] ?? '', $approx);
            return $qb->limit(30000)->get(['items.id', 'items.brand_id', 'items.category_id', 'items.subcategory_id'])
                ->map(function ($r) { return [(int) $r->id, $r->brand_id, $r->category_id, $r->subcategory_id]; })->all();
        });
        $favSet = !empty($f['fav']) ? array_flip(DB::table('portal_favorites')->where('customer_id', $this->customerId)->pluck('item_id')->all()) : null;
        $sel = ['brand' => array_map('strval', $f['brand'] ?? []), 'category' => array_map('strval', $f['category'] ?? []), 'sub' => array_map('strval', $f['sub'] ?? [])];
        $count = function ($skip, $col) use ($rows, $favSet, $sel) {
            $out = [];
            foreach ($rows as $r) {
                if ($favSet !== null && !isset($favSet[$r[0]])) continue;
                if ($skip !== 'brand' && $sel['brand'] && !in_array((string) $r[1], $sel['brand'], true)) continue;
                if ($skip !== 'category' && $sel['category'] && !in_array((string) $r[2], $sel['category'], true)) continue;
                if ($skip !== 'sub' && $sel['sub'] && !in_array((string) $r[3], $sel['sub'], true)) continue;
                $k = $r[$col]; if ($k === null) continue;
                $out[$k] = ($out[$k] ?? 0) + 1;
            }
            return $out;
        };

        $bn = $count('brand', 1);
        $cn = $count('category', 2);

        $subs = [];
        if (!empty($f['category'])) {
            $sn = $count('sub', 3);
            $selSub = array_map('strval', $f['sub'] ?? []);
            $selCat = array_map('strval', $f['category']);
            foreach ($opts['subMap'] as $sid => $m) {
                if (!in_array((string) $m['parent'], $selCat, true)) continue;
                $n = (int) ($sn[$sid] ?? 0); $on = in_array((string) $sid, $selSub, true);
                if ($n > 0 || $on) $subs[] = ['id' => (string) $sid, 'name' => $m['name'], 'n' => $n, 'on' => $on];
            }
            usort($subs, function ($a, $b) { return ($b['on'] <=> $a['on']) ?: ($b['n'] <=> $a['n']) ?: strcasecmp($a['name'], $b['name']); });
        }

        $build = function ($list, $counts, $selected) {
            $out = [];
            foreach ($list as $x) {
                $n = (int) ($counts[$x['id']] ?? 0);
                $on = in_array((string) $x['id'], $selected, true);
                if ($n > 0 || $on) $out[] = ['id' => (string) $x['id'], 'name' => $x['name'], 'n' => $n, 'on' => $on];
            }
            usort($out, function ($a, $b) { return ($b['on'] <=> $a['on']) ?: ($b['n'] <=> $a['n']) ?: strcasecmp($a['name'], $b['name']); });
            return array_slice($out, 0, 80);
        };

        return [
            'brands'     => $build($opts['brands'], $bn, array_map('strval', $f['brand'] ?? [])),
            'categories' => $build($opts['categories'], $cn, array_map('strval', $f['category'] ?? [])),
            'subs'       => array_slice($subs, 0, 80),
        ];
    }

    /** Suggestions instantanées de la barre de recherche. */
    public function suggest($q, Customer $customer, CustomerB2bSetting $cs)
    {
        $q = trim((string) $q);
        $out = ['kind' => self::kindOf($q), 'total' => 0, 'articles' => [], 'brands' => [], 'categories' => []];
        if (mb_strlen($q) < 2) return $out;

        $f = ['q' => $q];
        $run = function ($approx) use ($f, $q) {
            $qb = $this->searchQuery($f, $approx);
            $this->relevanceOrder($qb, $q, $approx);
            return $qb->orderByRaw('(' . self::STOCK . ' > 0) DESC')->orderBy('items.code')->limit(7)->get();
        };
        $items = $run(false);
        if ($items->isEmpty() && count(self::tokens($q)) >= 2) { $items = $run(true); $out['approx'] = true; }
        $out['more'] = $items->count() > 6;                 // « 6+ résultats » : inutile de tout compter pour une suggestion
        $items = $items->take(6);
        $out['total'] = $items->count();
        $out['articles'] = $this->rows($items, $customer, $cs);

        $needle = mb_strtolower($q);
        $opts = $this->filterOptions();
        foreach ($opts['brands'] as $b) {
            if (mb_stripos($b['name'], $needle) !== false) $out['brands'][] = $b;
            if (count($out['brands']) >= 4) break;
        }
        foreach ($opts['categories'] as $c) {
            if (mb_stripos($c['name'], $needle) !== false) $out['categories'][] = $c;
            if (count($out['categories']) >= 4) break;
        }
        return $out;
    }

    // ───────────────────────── équivalences / détail ─────────────────────────

    /** Équivalents (dans les deux sens) d'une liste d'articles, hors ceux déjà affichés. */
    public function equivalentIds(array $ofIds, array $exclude = [])
    {
        if (!$ofIds) return [];
        $a = DB::table('item_equivalents')->whereIn('item_id', $ofIds)->pluck('equivalent_item_id')->all();
        $b = DB::table('item_equivalents')->whereIn('equivalent_item_id', $ofIds)->pluck('item_id')->all();
        return array_values(array_diff(array_unique(array_merge($a, $b)), $exclude, $ofIds));
    }

    public function equivalentsOf($itemId, Customer $customer, CustomerB2bSetting $cs, $limit = 30)
    {
        $ids = $this->equivalentIds([(int) $itemId]);
        if (!$ids) return [];
        $eq = $this->baseQuery()->whereIn('items.id', $ids)
            ->orderByRaw('(' . self::STOCK . ' > 0) DESC')->orderBy('items.code')->limit($limit)->get();
        return $this->rows($eq, $customer, $cs);
    }

    /** Fiche détaillée (tiroir). Champs publics uniquement. */
    public function detail($itemId, Customer $customer, CustomerB2bSetting $cs)
    {
        $it = $this->baseQuery()->where('items.id', $itemId)->first();
        if (!$it) return null;
        $row = $this->rows([$it], $customer, $cs)[0];
        $row['description'] = trim((string) $it->description);
        $row['barcode']     = $it->barcode;
        $row['tecdoc']      = $it->Ref_TecDoc;
        $dims = array_filter([$it->Longueur ? (float) $it->Longueur : null, $it->Largeur ? (float) $it->Largeur : null, $it->Hauteur ? (float) $it->Hauteur : null]);
        $row['dimensions']  = count($dims) === 3 ? implode(' × ', $dims) . ' cm' : null;
        $row['weight']      = $it->Poids ? (float) $it->Poids : null;
        $row['origin']      = $it->Code_pays ?: null;
        $row['equivalents_rows'] = $this->equivalentsOf($itemId, $customer, $cs, 12);
        return $row;
    }

    // ───────────────────────── lignes d'affichage ─────────────────────────

    public function rows($items, Customer $customer, CustomerB2bSetting $cs)
    {
        $items = collect($items);
        if ($items->isEmpty()) return [];
        $ids = $items->pluck('id')->all();

        $a = DB::table('item_equivalents')->whereIn('item_id', $ids)->select('item_id as id', 'equivalent_item_id as other')->get();
        $b = DB::table('item_equivalents')->whereIn('equivalent_item_id', $ids)->select('equivalent_item_id as id', 'item_id as other')->get();
        $eqCount = [];
        foreach ($a->concat($b) as $r) { $eqCount[$r->id][$r->other] = true; }

        $favs = $this->customerId
            ? array_flip(DB::table('portal_favorites')->where('customer_id', $this->customerId)->whereIn('item_id', $ids)->pluck('item_id')->all())
            : [];
        $cart = $this->userId
            ? DB::table('portal_cart_items')->where('b2b_user_id', $this->userId)->whereIn('item_id', $ids)->pluck('quantity', 'item_id')->all()
            : [];

        $showPrices = $this->s->show_prices && $cs->show_prices;
        $zm = $this->s->zero_price_mode ?: 'both';
        $zHide = in_array($zm, ['hide', 'both'], true); $zBlock = in_array($zm, ['block', 'both'], true);
        $out = [];
        foreach ($items as $it) {
            $price = $showPrices ? $this->pricing->price($it, $customer) : null;
            $zero = $price && $price['net_ht'] !== null && (float) $price['net_ht'] <= 0;   // article à prix 0 : « sur demande »
            if ($zero && $zHide) $price = null;
            $out[] = [
                'id'       => $it->id,
                'can_buy'  => !($zero && $zBlock),
                'code'     => $it->code,
                'name'     => $it->name,
                'brand'    => optional($it->brand)->name,
                'category' => optional($it->category)->name,
                'unit'     => optional($it->unit)->name,
                // jamais le code fournisseur (identifie vos sources d'achat) : uniquement la référence TecDoc
                'alt_refs' => array_values(array_filter([$it->Ref_TecDoc])),
                'stock'    => $this->stockInfo((float) $it->stock_qty),
                'price'    => $price ? [
                    'net_ht'   => $price['net_ht'],
                    'net_ttc'  => $price['net_ttc'],
                    'list_ht'  => $price['list_ht'],
                    'discount' => $price['effective_rate'],
                    'prix_net' => $price['prix_net'],
                ] : null,
                'equivalents' => isset($eqCount[$it->id]) ? count($eqCount[$it->id]) : 0,
                'fav'      => isset($favs[$it->id]),
                'in_cart'  => isset($cart[$it->id]) ? (int) $cart[$it->id] : 0,
            ];
        }
        return $out;
    }

    /** Stock tel que le client doit le voir (jamais plus que ce que les réglages autorisent). */
    public function stockInfo($qty)
    {
        if (!$this->s->show_stock || $this->s->stock_display === 'hidden') {
            return ['state' => $qty > 0 ? 'in' : 'out', 'label' => null, 'qty' => null, 'hidden' => true];
        }
        if ($qty <= 0) return ['state' => 'out', 'label' => 'Rupture', 'qty' => null, 'hidden' => false];
        $low = $qty <= (int) $this->s->stock_low_threshold;
        if ($this->s->stock_display === 'exact') {
            return ['state' => $low ? 'low' : 'in', 'label' => (floor($qty) == $qty ? (int) $qty : $qty) . ' en stock', 'qty' => $qty, 'hidden' => false];
        }
        return ['state' => $low ? 'low' : 'in', 'label' => $low ? 'Stock limité' : 'En stock', 'qty' => null, 'hidden' => false];
    }

    // ───────────────────────── listes de filtres / accueil ─────────────────────────

    /**
     * Marques, familles et sous-familles ayant des articles actifs, avec leur effectif.
     * UNE seule requête groupée, mise en cache 1 h (l'accueil ne refait aucun comptage à chaque visite).
     */
    public function filterOptions()
    {
        return Cache::remember('portal_filter_options_v3', 3600, function () {
            $rows = DB::table('items')->where('is_active', 1)
                ->select('brand_id', 'category_id', 'subcategory_id', DB::raw('COUNT(*) as n'))
                ->groupBy('brand_id', 'category_id', 'subcategory_id')->get();
            $bc = []; $cc = []; $sc = [];
            foreach ($rows as $r) {
                if ($r->brand_id !== null)    $bc[$r->brand_id] = ($bc[$r->brand_id] ?? 0) + $r->n;
                if ($r->category_id !== null) {
                    $cc[$r->category_id] = ($cc[$r->category_id] ?? 0) + $r->n;
                    if ($r->subcategory_id !== null) $sc[$r->category_id][$r->subcategory_id] = ($sc[$r->category_id][$r->subcategory_id] ?? 0) + $r->n;
                }
            }
            $subNames = [];
            try {
                $tbl = class_exists('\\App\\Models\\ItemSubcategory') ? (new \App\Models\ItemSubcategory)->getTable() : 'item_subcategories';
                $subNames = DB::table($tbl)->pluck('name', 'id')->all();
            } catch (\Throwable $e) { $subNames = []; }

            $brands = DB::table('brands')->orderBy('name')->get(['id', 'name'])->filter(function ($b) use ($bc) { return isset($bc[$b->id]); })
                ->map(function ($b) use ($bc) { return ['id' => (string) $b->id, 'name' => $b->name, 'n' => (int) $bc[$b->id]]; })->values()->all();

            $subMap = [];
            $cats = DB::table('item_categories')->orderBy('name')->get(['id', 'name'])->filter(function ($c) use ($cc) { return isset($cc[$c->id]); })
                ->map(function ($c) use ($cc, $sc, $subNames, &$subMap) {
                    $subs = [];
                    foreach (($sc[$c->id] ?? []) as $sid => $n) {
                        $name = $subNames[$sid] ?? null; if ($name === null) continue;
                        $subs[] = ['id' => (string) $sid, 'name' => $name, 'n' => (int) $n];
                        $subMap[$sid] = ['name' => $name, 'parent' => (string) $c->id];
                    }
                    usort($subs, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });
                    return ['id' => (string) $c->id, 'name' => $c->name, 'n' => (int) $cc[$c->id], 'subs' => $subs];
                })->values()->all();

            return ['brands' => $brands, 'categories' => $cats, 'subMap' => $subMap, 'total' => (int) array_sum($cc)];
        });
    }

    /** Lignes de la liste de références collée par le client : « référence [quantité] » par ligne. */
    public function bulk(array $lines, Customer $customer, CustomerB2bSetting $cs)
    {
        $out = [];
        $norms = [];
        foreach (array_slice($lines, 0, 100) as $ln) {
            $norms[] = self::normalizeRef($ln['ref']);
        }
        foreach (array_slice($lines, 0, 100) as $i => $ln) {
            $n = $norms[$i];
            $found = null; $multi = false;
            if (strlen($n) >= 3) {
                $qb = $this->baseQuery();
                if ($this->hasNormColumns()) {
                    $exact = (clone $qb)->where('items.code_norm', $n)->orderByRaw('(' . self::STOCK . ' > 0) DESC')->limit(2)->get();
                    if ($exact->count()) { $found = $exact->first(); $multi = $exact->count() > 1; }
                    else {
                        $like = (clone $qb)->where('items.search_refs', 'LIKE', '%' . $this->likeEscape($n) . '%')->orderByRaw('(' . self::STOCK . ' > 0) DESC')->limit(2)->get();
                        if ($like->count()) { $found = $like->first(); $multi = $like->count() > 1; }
                    }
                } else {
                    $like = $qb->where('items.code', 'LIKE', '%' . $this->likeEscape($ln['ref']) . '%')->limit(2)->get();
                    if ($like->count()) { $found = $like->first(); $multi = $like->count() > 1; }
                }
            }
            $row = $found ? $this->rows([$found], $customer, $cs)[0] : null;
            $out[] = ['input' => $ln['ref'], 'qty' => $ln['qty'], 'row' => $row, 'multi' => $multi];
        }
        return $out;
    }
}
