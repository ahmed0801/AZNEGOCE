<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Classeur Excel « Statistiques de vente » : une feuille de synthèse + une feuille par état.
 * Reçoit le tableau produit par App\Services\SalesStatsService::build().
 */
class SalesStatsExport implements WithMultipleSheets
{
    protected $d;

    public function __construct(array $data)
    {
        $this->d = $data;
    }

    public function sheets(): array
    {
        $d = $this->d;
        $sheets = [new StatsSummarySheet($d)];

        // ── Top articles ─────────────────────────────────────
        $rows = [];
        $rank = 0;
        foreach (array_slice($d['articles'], 0, 3000) as $r) {
            $rank++;
            $rows[] = [$rank, $r['code'], $r['name'], $r['brand'], $r['category'], $r['class'], $r['qty'], $r['ca'], $r['share'], $r['cum'],
                $r['avg_price'], $r['margin'], $r['margin_pc'], $r['docs'], $r['clients'], $r['last_sale'], $r['stock'], $r['coverage'], $r['rotation']];
        }
        $sheets[] = new StatsTableSheet('Top articles',
            ['#', 'Code', 'Désignation', 'Marque', 'Famille', 'ABC', 'Qté vendue', 'CA HT', 'Part CA %', 'Cumul %', 'Prix moyen HT', 'Marge', 'Marge %', 'Nb BL (top 300)', 'Nb clients (top 300)', 'Dernière vente', 'Stock', 'Couverture (j)', 'Rotation (x/an)'],
            $rows, ['G' => 'qty', 'H' => 'money', 'I' => 'pc', 'J' => 'pc', 'K' => 'money', 'L' => 'money', 'M' => 'pc', 'Q' => 'qty', 'S' => 'dec'], 5);

        // ── Groupes ──────────────────────────────────────────
        $groups = [['category', 'Familles', 'Famille'], ['subcategory', 'Sous-familles', 'Sous-famille'], ['brand', 'Marques', 'Marque'], ['supplier', 'Fournisseurs', 'Fournisseur']];
        foreach ($groups as $g) {
            $rows = [];
            foreach ($d['groups'][$g[0]] as $r) {
                $rows[] = [$r['label'], $r['ca'], $r['share'], $r['qty'], $r['sold'], $r['catalog'], $r['sold_pc'], $r['margin'], $r['margin_pc'], $r['stock_qty'], $r['stock_val'], $r['coverage']];
            }
            $sheets[] = new StatsTableSheet($g[1],
                [$g[2], 'CA HT', 'Part CA %', 'Qté vendue', 'Réf. vendues', 'Réf. catalogue actif', '% réf. vendues', 'Marge', 'Marge %', 'Stock (qté)', 'Valeur stock (coût net)', 'Couverture (j)'],
                $rows, ['B' => 'money', 'C' => 'pc', 'D' => 'qty', 'G' => 'pc', 'H' => 'money', 'I' => 'pc', 'J' => 'qty', 'K' => 'money'], 0);
        }

        // ── ABC ──────────────────────────────────────────────
        $rows = [];
        foreach (['A' => 'A — 80 % du CA', 'B' => 'B — 15 % suivants', 'C' => 'C — 5 % restants'] as $k => $lab) {
            $a = $d['abc'][$k];
            $rows[] = [$lab, $a['n'], $a['n_pc'], $a['ca'], $a['ca_pc']];
        }
        $sheets[] = new StatsTableSheet('ABC', ['Classe', 'Nb articles', '% des références vendues', 'CA HT', '% du CA'], $rows, ['C' => 'pc', 'D' => 'money', 'E' => 'pc'], 0);

        // ── À réapprovisionner ───────────────────────────────
        $rows = [];
        foreach ($d['alerts'] as $r) {
            $rows[] = [$r['code'], $r['name'], $r['brand'], $r['class'], $r['qty'], $r['ca'], $r['stock'], $r['coverage'], $r['suggest']];
        }
        $sheets[] = new StatsTableSheet('À réapprovisionner',
            ['Code', 'Désignation', 'Marque', 'ABC', 'Qté vendue', 'CA HT', 'Stock', 'Couverture (j)', 'Qté suggérée (30 j)'],
            $rows, ['E' => 'qty', 'F' => 'money', 'G' => 'qty'], 3);

        // ── Dormants ─────────────────────────────────────────
        $rows = [];
        foreach ($d['dormants']['rows'] as $r) {
            $rows[] = [$r['code'], $r['name'], $r['brand'], $r['category'], $r['stock'], $r['net_cost'], $r['val'], $r['last_sale'] ?: 'Jamais vendu', $r['idle_days']];
        }
        $sheets[] = new StatsTableSheet('Dormants',
            ['Code', 'Désignation', 'Marque', 'Famille', 'Stock', 'Coût net unitaire', 'Valeur stock', 'Dernière vente', 'Jours sans vente'],
            $rows, ['E' => 'qty', 'F' => 'money', 'G' => 'money'], 0);

        // ── Clients ──────────────────────────────────────────
        $rows = [];
        foreach ($d['clients'] as $r) {
            $rows[] = [$r['code'], $r['name'], $r['ca'], $r['share'], $r['qty'], $r['docs'], $r['articles'], $r['basket'], $r['margin'], $r['margin_pc'], $r['last_buy']];
        }
        $sheets[] = new StatsTableSheet('Clients',
            ['Code', 'Client', 'CA HT', 'Part CA %', 'Qté', 'Nb BL', 'Réf. achetées', 'Panier moyen', 'Marge', 'Marge %', 'Dernier achat'],
            $rows, ['C' => 'money', 'D' => 'pc', 'E' => 'qty', 'H' => 'money', 'I' => 'money', 'J' => 'pc'], 0);

        // ── Vendeurs ─────────────────────────────────────────
        $rows = [];
        foreach ($d['vendeurs'] as $r) {
            $rows[] = [$r['name'], $r['ca'], $r['share'], $r['qty'], $r['docs'], $r['clients'], $r['basket'], $r['margin'], $r['margin_pc']];
        }
        $sheets[] = new StatsTableSheet('Vendeurs',
            ['Vendeur', 'CA HT', 'Part CA %', 'Qté', 'Nb BL', 'Nb clients', 'Panier moyen', 'Marge', 'Marge %'],
            $rows, ['B' => 'money', 'C' => 'pc', 'D' => 'qty', 'G' => 'money', 'H' => 'money', 'I' => 'pc'], 0);

        // ── Évolution ────────────────────────────────────────
        $s = $d['series'];
        $rows = [];
        foreach ($s['labels'] as $i => $lab) {
            $rows[] = [$lab, $s['ca'][$i], $s['margin'][$i], $s['qty'][$i], $s['docs'][$i]];
        }
        $gl = ['day' => 'Jour', 'week' => 'Semaine', 'month' => 'Mois'];
        $sheets[] = new StatsTableSheet('Évolution', [$gl[$s['granularity']], 'CA HT', 'Marge', 'Qté', 'Nb BL'], $rows, ['B' => 'money', 'C' => 'money', 'D' => 'qty'], 0);

        return $sheets;
    }
}

/** Mise en forme commune */
trait StatsSheetStyle
{
    protected static $FORMATS = [
        'money' => '#,##0.00',
        'qty'   => '#,##0.##',
        'pc'    => '0.0',
        'dec'   => '0.0',
    ];

    protected static function headerStyle($ws, $range)
    {
        $ws->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E2D4A']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
    }
}

class StatsTableSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    use StatsSheetStyle;

    protected $title;
    protected $headings;
    protected $rows;
    protected $formats;
    protected $classCol; // index (0-based) de la colonne ABC à colorer, ou null
    protected $freezeCols;

    /** $abcCol : index de la colonne « ABC » (colorée A/B/C) ou 0 si aucune ; 5 = colonne F sur Top articles, 3 = D sur « À réapprovisionner » */
    public function __construct($title, array $headings, array $rows, array $formats, $abcCol)
    {
        $this->title = $title;
        $this->headings = $headings;
        $this->rows = $rows;
        $this->formats = $formats;
        $this->classCol = $abcCol ?: null;
    }

    public function title(): string { return $this->title; }
    public function headings(): array { return $this->headings; }
    public function array(): array { return $this->rows; }

    public function columnFormats(): array
    {
        $out = [];
        foreach ($this->formats as $col => $f) $out[$col] = self::$FORMATS[$f];
        return $out;
    }

    public function registerEvents(): array
    {
        $self = $this;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($self) {
                $ws = $event->sheet->getDelegate();
                $n = count($self->headings);
                $last = Coordinate::stringFromColumnIndex($n);
                $lastRow = max(1, count($self->rows) + 1);
                self::headerStyle($ws, 'A1:' . $last . '1');
                $ws->getRowDimension(1)->setRowHeight(30);
                $ws->freezePane('A2');
                $ws->setAutoFilter('A1:' . $last . $lastRow);
                if ($self->classCol !== null) {
                    $col = Coordinate::stringFromColumnIndex($self->classCol + 1);
                    $colors = ['A' => 'C6EFCE', 'B' => 'FFEB9C', 'C' => 'E7E6E6'];
                    for ($r = 2; $r <= $lastRow; $r++) {
                        $v = (string) $ws->getCell($col . $r)->getValue();
                        if (isset($colors[$v])) {
                            $ws->getStyle($col . $r)->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $colors[$v]]],
                                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                                'font' => ['bold' => true],
                            ]);
                        }
                    }
                }
            },
        ];
    }
}

class StatsSummarySheet implements FromArray, WithTitle, ShouldAutoSize, WithEvents
{
    use StatsSheetStyle;

    protected $d;
    protected $rows = [];
    protected $headerRows = [];
    protected $sectionRows = [];

    public function __construct(array $d)
    {
        $this->d = $d;
        $this->compose();
    }

    protected function eur($v) { return $v === null ? '' : number_format((float) $v, 2, ',', ' ') . ' €'; }
    protected function fr($s) { return $s ? date('d/m/Y', strtotime($s)) : ''; }

    protected function compose()
    {
        $d = $this->d; $f = $d['filters']; $p = $d['period']; $k = $d['kpis'];
        $R = &$this->rows;

        $R[] = ['STATISTIQUES DE VENTE', '', '', ''];
        $this->sectionRows[] = 1;
        $R[] = ['Période', $this->fr($p['from']) . ' → ' . $this->fr($p['to']) . ' (' . $p['days'] . ' jours)', '', ''];
        $R[] = ['Comparée à', $this->fr($p['prev_from']) . ' → ' . $this->fr($p['prev_to']), '', ''];
        $flt = [];
        foreach (['brand_id' => 'Marque (id)', 'category_id' => 'Famille (id)', 'subcategory_id' => 'Sous-famille (id)', 'codefournisseur' => 'Fournisseur', 'client' => 'Client', 'vendeur' => 'Vendeur', 'search' => 'Recherche'] as $key => $lab) {
            if (!empty($f[$key])) $flt[] = $lab . ' : ' . $f[$key];
        }
        $R[] = ['Filtres', $flt ? implode(' · ', $flt) : 'Aucun', '', ''];
        $R[] = ['Généré le', date('d/m/Y H:i'), '', ''];
        $R[] = ['', '', '', ''];

        $R[] = ['Indicateur', 'Période', 'Période précédente', 'Évolution'];
        $this->headerRows[] = count($R);
        $ev = function ($v, $suffix) { return $v === null ? '—' : (($v > 0 ? '+' : '') . str_replace('.', ',', $v) . $suffix); };
        $R[] = ['Chiffre d\'affaires HT', $this->eur($k['cur']['ca']), $this->eur($k['prev']['ca']), $ev($k['delta']['ca'], ' %')];
        $R[] = ['Marge estimée', $this->eur($k['cur']['margin']), $this->eur($k['prev']['margin']), $ev($k['delta']['margin'], ' %')];
        $R[] = ['Taux de marge', $k['cur']['margin_pc'] === null ? '—' : str_replace('.', ',', $k['cur']['margin_pc']) . ' %', $k['prev']['margin_pc'] === null ? '—' : str_replace('.', ',', $k['prev']['margin_pc']) . ' %', $ev($k['delta']['margin_pc'], ' pts')];
        $R[] = ['Quantités vendues', $k['cur']['qty'], $k['prev']['qty'], $ev($k['delta']['qty'], ' %')];
        $R[] = ['Nombre de BL', $k['cur']['docs'], $k['prev']['docs'], $ev($k['delta']['docs'], ' %')];
        $R[] = ['Panier moyen (CA / BL)', $this->eur($k['cur']['basket']), $this->eur($k['prev']['basket']), $ev($k['delta']['basket'], ' %')];
        $R[] = ['Clients actifs', $k['cur']['clients'], $k['prev']['clients'], $ev($k['delta']['clients'], ' %')];
        $R[] = ['Références vendues', $k['cur']['articles'], $k['prev']['articles'], $ev($k['delta']['articles'], ' %')];
        $R[] = ['CA moyen par jour', $this->eur($k['cur']['per_day']), $this->eur($k['prev']['per_day']), $ev($k['delta']['per_day'], ' %')];
        $R[] = ['', '', '', ''];

        $R[] = ['Points d\'attention & enseignements', '', '', ''];
        $this->headerRows[] = count($R);
        foreach ($d['insights'] as $i) {
            $tag = ['good' => '✔ ', 'bad' => '✖ ', 'warn' => '⚠ ', 'info' => '• '][$i['type']];
            $R[] = [$tag . $i['text'], '', '', ''];
        }
        $R[] = ['', '', '', ''];
        $R[] = ['Stock dormant (aucune vente sur la période)', $d['dormants']['count'] . ' articles', $this->eur($d['dormants']['value']), ''];
        $R[] = ['', '', '', ''];
        $R[] = ['Méthode', 'CA = qté livrée × prix HT × (1 − remise). Source : lignes de bons de livraison (annulés/brouillons exclus). Marge = CA − coût d\'achat net actuel de l\'article (articles sans coût exclus du calcul). ABC : A = 80 % du CA, B = 15 %, C = 5 %.', '', ''];
    }

    public function title(): string { return 'Synthèse'; }
    public function array(): array { return $this->rows; }

    public function registerEvents(): array
    {
        $self = $this;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($self) {
                $ws = $event->sheet->getDelegate();
                $ws->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1E2D4A']]]);
                $ws->getStyle('A2:A5')->applyFromArray(['font' => ['bold' => true]]);
                foreach ($self->headerRows as $r) {
                    self::headerStyle($ws, 'A' . $r . ':D' . $r);
                }
                $ws->getStyle('B8:D16')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $ws->getColumnDimension('A')->setAutoSize(false);
                $ws->getColumnDimension('A')->setWidth(48);
                $ws->getColumnDimension('B')->setAutoSize(false);
                $ws->getColumnDimension('B')->setWidth(32);
                $ws->getColumnDimension('C')->setWidth(24);
                $ws->getColumnDimension('D')->setWidth(16);
            },
        ];
    }
}
