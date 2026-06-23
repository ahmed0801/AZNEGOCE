<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Export comptable des factures de VENTE uniquement
 * Feuille 1 : export standard
 * Feuille 2 : écritures comptables journal VE par facture
 *
 * Pour chaque facture de vente :
 *   JOURNAL  COMPTE    D        C        DATE         N°Pièce
 *   VE       70702000           HT       date         numdoc   ← produit au crédit
 *   VE       44571000           TVA      date         numdoc   ← TVA collectée au crédit
 *   VE       9CB        TTC              date         numdoc   ← client au débit
 */
class SalesInvoicesComptableExport implements WithMultipleSheets
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            new SalesInvoicesStandardSheet($this->request),
            new SalesInvoicesEcritureComptableSheet($this->request),
        ];
    }
}


// ═══════════════════════════════════════════════════════
// FEUILLE 1 : Export standard
// ═══════════════════════════════════════════════════════
class SalesInvoicesStandardSheet implements FromArray, WithTitle, WithStyles, WithEvents
{
    protected $request;
    protected $invoices;

    public function __construct(Request $request)
    {
        $this->request  = $request;
        $this->invoices = $this->loadInvoices();
    }

    public function title(): string { return 'Worksheet'; }

    private function loadInvoices()
    {
        $query = Invoice::with(['customer', 'lines', 'vehicle', 'deliveryNotes', 'salesReturns'])
            ->orderBy('updated_at', 'desc');
        $this->applyFilters($query);
        return $query->get();
    }

    private function applyFilters($query)
    {
        if ($this->request->filled('customer_id'))
            $query->where('customer_id', $this->request->customer_id);
        if ($this->request->filled('status'))
            $query->where('status', $this->request->status);
        if ($this->request->filled('paid'))
            $query->where('paid', $this->request->paid === '1');
        if ($this->request->filled('date_from'))
            $query->whereDate('invoice_date', '>=', $this->request->date_from);
        if ($this->request->filled('date_to'))
            $query->whereDate('invoice_date', '<=', $this->request->date_to);
    }

    public function array(): array
    {
        $rows = [[
            'Numéro Facture','Date Facture','Client','Véhicule',
            'Statut Facture','Payé','Type','Total HT (€)','TOTAL TVA',
            'Total TTC (€)','TVA (%)','Nb Lignes','Total Lignes HT (€)',
            'Bons Livraison','Retours Vente','Num Client','Créé le','Mis à jour le',
        ]];

        foreach ($this->invoices as $inv) {
            $customerText = $inv->customer
                ? $inv->customer->name . ' (' . $inv->customer->code . ')' : '-';
            $vehicleText = $inv->vehicle
                ? $inv->vehicle->license_plate . ' (' . $inv->vehicle->brand_name . ' ' . $inv->vehicle->model_name . ')' : '-';

            $paid = $inv->paid ? '🟢 PAYÉ' : '🔴 NON PAYÉ';
            if ($inv->paid && $inv->status === 'validée') {
                $rem = max(0, $inv->total_ttc ?? 0);
                if ($rem > 0)
                    $paid = '🟡 PARTIELLEMENT PAYÉ (' . number_format($rem, 2, ',', ' ') . ' €)';
            }
            $tva = round(($inv->total_ttc ?? 0) - ($inv->total_ht ?? 0), 2);

            $rows[] = [
                $inv->numdoc ?? '-',
                $inv->invoice_date ? Carbon::parse($inv->invoice_date)->format('d/m/Y') : '-',
                $customerText, $vehicleText,
                ucfirst($inv->status ?? '-'), $paid, ucfirst($inv->type ?? '-'),
                number_format($inv->total_ht ?? 0, 2, ',', ' '),
                number_format($tva, 2, ',', ' '),
                number_format($inv->total_ttc ?? 0, 2, ',', ' '),
                number_format($inv->tva_rate ?? 0, 2, ',', ' ') . '%',
                $inv->lines ? $inv->lines->count() : 0,
                number_format($inv->lines ? $inv->lines->sum('total_ligne_ht') : 0, 2, ',', ' '),
                $inv->deliveryNotes ? $inv->deliveryNotes->count() : 0,
                $inv->salesReturns  ? $inv->salesReturns->count()  : 0,
                $inv->numclient ?? '-',
                $inv->created_at ? Carbon::parse($inv->created_at)->format('d/m/Y') : '-',
                $inv->updated_at ? Carbon::parse($inv->updated_at)->format('d/m/Y') : '-',
            ];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E79']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach (range('A', 'R') as $col)
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                $sheet->freezePane('A2');
                $last = count($this->invoices) + 1;
                for ($r = 2; $r <= $last; $r++) {
                    $color = ($r % 2 === 0) ? 'F5F9FF' : 'FFFFFF';
                    $event->sheet->getStyle('A'.$r.':R'.$r)->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                    ]);
                }
            },
        ];
    }
}


// ═══════════════════════════════════════════════════════════════
// FEUILLE 2 : ECRITURE COMPTABLE (factures de vente uniquement)
// ═══════════════════════════════════════════════════════════════
class SalesInvoicesEcritureComptableSheet implements FromArray, WithTitle, WithStyles, WithEvents
{
    protected $request;
    protected $invoices;
    protected $sectionRows = [];

    public function __construct(Request $request)
    {
        $this->request  = $request;
        $this->invoices = $this->loadInvoices();
    }

    public function title(): string { return 'ECRITURE COMPTABLE'; }

    private function loadInvoices()
    {
        $query = Invoice::with(['customer', 'vehicle'])
            ->orderBy('invoice_date', 'asc');

        if ($this->request->filled('customer_id'))
            $query->where('customer_id', $this->request->customer_id);
        if ($this->request->filled('status'))
            $query->where('status', $this->request->status);
        if ($this->request->filled('paid'))
            $query->where('paid', $this->request->paid === '1');
        if ($this->request->filled('date_from'))
            $query->whereDate('invoice_date', '>=', $this->request->date_from);
        if ($this->request->filled('date_to'))
            $query->whereDate('invoice_date', '<=', $this->request->date_to);

        return $query->get();
    }

    public function array(): array
    {
        $rows = [];
        $currentRow = 1;

        // En-tête info facture (colonnes A–R)
        $rows[] = [
            'Numéro Facture','Date Facture','Client','Véhicule',
            'Statut Facture','Payé','Type','Total HT (€)','TOTAL TVA',
            'Total TTC (€)','TVA (%)','Nb Lignes','Total Lignes HT (€)',
            'Bons Livraison','Retours Vente','Num Client','Créé le','Mis à jour le',
        ];
        $this->sectionRows[] = ['type' => 'main_header', 'row' => $currentRow++];

        foreach ($this->invoices as $inv) {
            $customerText = $inv->customer
                ? $inv->customer->name . ' (' . $inv->customer->code . ')' : '-';
            $vehicleText = $inv->vehicle
                ? $inv->vehicle->license_plate . ' (' . $inv->vehicle->brand_name . ' ' . $inv->vehicle->model_name . ')' : '-';

            $paid = $inv->paid ? '🟢 PAYÉ' : '🔴 NON PAYÉ';
            if ($inv->paid && $inv->status === 'validée') {
                $rem = max(0, $inv->total_ttc ?? 0);
                if ($rem > 0)
                    $paid = '🟡 PARTIELLEMENT PAYÉ (' . number_format($rem, 2, ',', ' ') . ' €)';
            }

            $ht      = round($inv->total_ht  ?? 0, 2);
            $ttc     = round($inv->total_ttc ?? 0, 2);
            $tva     = round($ttc - $ht, 2);
            $date    = $inv->invoice_date ? Carbon::parse($inv->invoice_date)->format('d/m/Y') : '-';
            $numdoc  = $inv->numdoc ?? '-';

            // ── Ligne récapitulative facture ──
            $rows[] = [
                $numdoc, $date, $customerText, $vehicleText,
                ucfirst($inv->status ?? '-'), $paid, ucfirst($inv->type ?? '-'),
                number_format($ht,  2, ',', ' '),
                number_format($tva, 2, ',', ' '),
                number_format($ttc, 2, ',', ' '),
                number_format($inv->tva_rate ?? 0, 2, ',', ' ') . '%',
                '-', '-', '-', '-',
                $inv->numclient ?? '-',
                $inv->created_at ? Carbon::parse($inv->created_at)->format('d/m/Y') : '-',
                $inv->updated_at ? Carbon::parse($inv->updated_at)->format('d/m/Y') : '-',
            ];
            $this->sectionRows[] = ['type' => 'invoice_info', 'row' => $currentRow++];

            // ── Titre "Facture de vente" ──
            $rows[] = ['Facture de vente'];
            $this->sectionRows[] = ['type' => 'vente_header', 'row' => $currentRow++];

            // ── En-tête colonnes journal ──
            $rows[] = ['JOURNAL', '', 'D', 'C', 'DATE DE FACTURE', 'N°Pièce'];
            $this->sectionRows[] = ['type' => 'journal_header', 'row' => $currentRow++];

            // Compte produit HT  → Crédit (vente)
            $rows[] = ['VE', '70702000', '', $ht,  $date, $numdoc];
            $this->sectionRows[] = ['type' => 'journal_line', 'row' => $currentRow++];

            // Compte TVA collectée → Crédit
            $rows[] = ['VE', '44571000', '', $tva, $date, $numdoc];
            $this->sectionRows[] = ['type' => 'journal_line', 'row' => $currentRow++];

            // Compte client (9CB) → Débit
            $rows[] = ['VE', '9CB', $ttc, '', $date, $numdoc];
            $this->sectionRows[] = ['type' => 'journal_line', 'row' => $currentRow++];

            // Ligne vide séparatrice
            $rows[] = [];
            $currentRow++;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E79']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach ($this->sectionRows as $sr) {
                    $row  = $sr['row'];
                    $type = $sr['type'];

                    switch ($type) {
                        case 'invoice_info':
                            $event->sheet->getStyle('A'.$row.':R'.$row)->applyFromArray([
                                'font' => ['bold' => true, 'size' => 10],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D6E4F0']],
                                'borders' => [
                                    'top'    => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4472C4']],
                                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4472C4']],
                                ],
                            ]);
                            break;

                        case 'vente_header':
                            $event->sheet->getStyle('A'.$row)->applyFromArray([
                                'font' => ['bold' => true, 'color' => ['rgb' => '1A7A3A'], 'size' => 10],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']],
                            ]);
                            break;

                        case 'journal_header':
                            $event->sheet->getStyle('A'.$row.':F'.$row)->applyFromArray([
                                'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '2C3E50']],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ECF0F1']],
                                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                            ]);
                            break;

                        case 'journal_line':
                            $event->sheet->getStyle('A'.$row.':F'.$row)->applyFromArray([
                                'font' => ['size' => 9],
                                'borders' => ['bottom' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['rgb' => 'CCCCCC']]],
                            ]);
                            $event->sheet->getStyle('C'.$row.':D'.$row)->applyFromArray([
                                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                            ]);
                            // Format numérique sur les montants
                            $sheet->getStyle('C'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
                            $sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
                            break;
                    }
                }

                // Largeurs colonnes
                foreach (range('A', 'F') as $col)
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                foreach (range('G', 'R') as $col)
                    $sheet->getColumnDimension($col)->setWidth(16);

                $sheet->freezePane('A2');

                // Pied de page
                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->setCellValue('A'.$lastRow, 'Export comptable — AZ ERP — ' . Carbon::now()->format('d/m/Y à H:i'));
                $event->sheet->getStyle('A'.$lastRow)->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '888888']],
                ]);
                $sheet->mergeCells('A'.$lastRow.':R'.$lastRow);
            },
        ];
    }
}