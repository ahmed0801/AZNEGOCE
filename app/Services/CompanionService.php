<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * « Compléter la réparation » : à partir des ventes passées (lignes de bons de livraison uniquement : chaque facture est issue d'un BL, inutile de compter deux fois),
 * trouve les articles que les clients prennent d'habitude AVEC les articles déjà présents sur le document.
 *
 * Pour chaque article du document (ancre) :
 *   confiance = nb de ventes contenant l'ancre ET l'article proposé / nb de ventes contenant l'ancre.
 * On garde, pour chaque article proposé, sa meilleure confiance parmi les ancres.
 * Compatible PHP 7.4.
 */
class CompanionService
{
    const SOURCES = ['bl'];
    const MAX_ANCHORS = 6;      // on n'analyse que les derniers articles ajoutés
    const MAX_DOCS = 600;       // ventes récentes analysées par article
    const MIN_SUPPORT = 2;      // au moins 2 ventes communes
    const MIN_CONFIDENCE = 0.15;

    /** @var object|null dépôt de données (permet de tester sans base) */
    private $repo;

    public function __construct($repo = null)
    {
        $this->repo = $repo ?: new LaravelCompanionRepo();
    }

    /**
     * @param string[] $codes codes déjà présents sur le document
     * @return array[] [['code','support','confidence','anchor','anchor_sales'], ...] triés par confiance
     */
    public function suggest(array $codes, $limit = 12)
    {
        $codes = array_values(array_unique(array_filter(array_map('strval', $codes), function ($c) { return $c !== ''; })));
        if (!$codes) return [];
        $anchors = array_slice($codes, -self::MAX_ANCHORS);
        $present = array_flip($codes);
        $best = [];

        foreach ($anchors as $a) {
            $anchorSales = 0;
            $counts = [];
            foreach (self::SOURCES as $src) {
                $docs = $this->repo->docIds($src, $a, self::MAX_DOCS);
                if (!$docs) continue;
                $anchorSales += count($docs);
                foreach ($this->repo->companions($src, $docs, $a) as $code => $n) {
                    $counts[$code] = ($counts[$code] ?? 0) + (int) $n;
                }
            }
            if ($anchorSales < self::MIN_SUPPORT) continue;
            foreach ($counts as $code => $n) {
                $code = (string) $code;
                if (isset($present[$code]) || $n < self::MIN_SUPPORT) continue;
                $conf = $n / $anchorSales;
                if ($conf < self::MIN_CONFIDENCE) continue;
                if (!isset($best[$code]) || $conf > $best[$code]['confidence']) {
                    $best[$code] = ['code' => $code, 'support' => $n, 'confidence' => round($conf, 4), 'anchor' => $a, 'anchor_sales' => $anchorSales];
                }
            }
        }
        $out = array_values($best);
        usort($out, function ($x, $y) {
            if ($x['confidence'] == $y['confidence']) return $y['support'] <=> $x['support'];
            return $y['confidence'] <=> $x['confidence'];
        });
        return array_slice($out, 0, $limit);
    }
}

/** Accès base de données réel. */
class LaravelCompanionRepo
{
    private function def($src)
    {
        // une vente = un bon de livraison
        return ['delivery_note_lines', 'delivery_note_id'];
    }

    public function docIds($src, $code, $limit)
    {
        list($table, $key) = $this->def($src);
        return DB::table($table)->where('article_code', $code)->whereNotNull($key)
            ->orderByDesc($key)->distinct()->limit($limit)->pluck($key)->all();
    }

    public function companions($src, array $docIds, $code)
    {
        list($table, $key) = $this->def($src);
        $rows = DB::table($table)->whereIn($key, $docIds)->where('article_code', '<>', $code)
            ->whereNotNull('article_code')
            ->selectRaw('article_code, COUNT(DISTINCT ' . $key . ') as n')->groupBy('article_code')->get();
        $out = [];
        foreach ($rows as $r) $out[(string) $r->article_code] = (int) $r->n;
        return $out;
    }
}
