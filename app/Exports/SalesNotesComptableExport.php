<?php

namespace App\Exports;

use App\Models\SalesNote;
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
 * Export comptable des avoirs de vente
 * Feuille 1 : export standard (identique à SalesNotesExport existant)
 * Feuille 2 : écritures comptables journal VE par avoir
 *
 * Pour chaque avoir de vente (écriture inverse d'une facture) :
 *   JOURNAL  COMPTE    D        C        DATE         N°Pièce
 *   VE       70702000   HT               date         numdoc   ← produit au débit (annulation)
 *   VE       44571000   TVA              date         numdoc   ← TVA au débit (annulation)
 *   VE       9CB                 TTC     date         numdoc   ← client au crédit
 */
class SalesNotesComptableExport implements WithMultipleSheets
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            new SalesNotesStandardSheet($this->request),
            new SalesNotesEcritureComptableSheet($this->request),
        ];
    }
}


// ═══════════════════════════════════════════════════════
// FEUILLE 1 : Export standard (identique à SalesNotesExport)
// ═══════════════════════════════════════════════════════
class SalesNotesStandardSheet implements FromArray, WithTitle, WithStyles, WithEvents
{
    protected $request;
    protected $notes;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->notes   = $this->loadNotes();
    }

    public function title(): string { return 'Avoirs de Vente'; }

    private function loadNotes()
    {
        $query = SalesNote::with(['customer', 'lines.item', 'salesInvoice', 'salesReturn'])
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
        if ($this->request->filled('date_from'))
            $query->whereDate('note_date', '>=', $this->request->date_from);
        if ($this->request->filled('date_to'))
            $query->whereDate('note_date', '<=', $this->request->date_to);
    }

    public function array(): array
    {
        $rows = [[
            'Numéro Avoir', 'Date Avoir', 'Client', 'Référence',
            'Statut Avoir', 'Payé', 'Type', 'Total HT (€)', 'TOTAL TVA',
            'Total TTC (€)', 'TVA (%)', 'Nb Lignes', 'Num Client', 'Créé le', 'Mis à jour le',
        ]];

        foreach ($this->notes as $note) {
            $refText = '-';
            if ($note->sales_invoice_id)
                $refText = 'Facture: ' . ($note->salesInvoice->numdoc ?? 'N/A');
            elseif ($note->sales_return_id)
                $refText = 'Retour: ' . ($note->salesReturn->numdoc ?? 'N/A');

            $paid = $note->paid ? '🟢 PAYÉ' : '🔴 NON PAYÉ';
            if ($note->status === 'validée' && !$note->paid) {
                $remaining = $note->getRemainingBalanceAttribute() ?? $note->total_ttc;
                $paid = '🔴 NON PAYÉ (' . number_format($remaining, 2, ',', ' ') . ' €)';
            }

            $tva = round(($note->total_ttc ?? 0) - ($note->total_ht ?? 0), 2);

            $rows[] = [
                $note->numdoc ?? '-',
                $note->note_date ? Carbon::parse($note->note_date)->format('d/m/Y') : '-',
                $note->customer ? $note->customer->name . ' (' . $note->customer->code . ')' : '-',
                $refText,
                ucfirst($note->status ?? '-'),
                $paid,
                ucfirst($note->type ?? '-'),
                number_format($note->total_ht  ?? 0, 2, ',', ' '),
                number_format($tva,                2, ',', ' '),
                number_format($note->total_ttc ?? 0, 2, ',', ' '),
                number_format($note->tva_rate  ?? 0, 2, ',', ' ') . '%',
                $note->lines ? $note->lines->count() : 0,
                $note->numclient ?? '-',
                $note->created_at ? Carbon::parse($note->created_at)->format('d/m/Y') : '-',
                $note->updated_at ? Carbon::parse($note->updated_at)->format('d/m/Y') : '-',
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6B1A1A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach (range('A', 'O') as $col)
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                $sheet->freezePane('A2');

                $last = count($this->notes) + 1;
                for ($r = 2; $r <= $last; $r++) {
                    $color = ($r % 2 === 0) ? 'FFF5F5' : 'FFFFFF';
                    $event->sheet->getStyle('A'.$r.':O'.$r)->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                        'font' => ['size' => 9],
                    ]);
                }

                // Ligne total
                $totalHT  = $this->notes->sum(fn($n) => $n->total_ht  ?? 0);
                $totalTTC = $this->notes->sum(fn($n) => $n->total_ttc ?? 0);
                $totalTVA = round($totalTTC - $totalHT, 2);
                $totalRow = $last + 1;

                $sheet->setCellValue('A'.$totalRow, 'TOTAL GÉNÉRAL');
                $sheet->setCellValue('H'.$totalRow, number_format($totalHT,  2, ',', ' '));
                $sheet->setCellValue('I'.$totalRow, number_format($totalTVA, 2, ',', ' '));
                $sheet->setCellValue('J'.$totalRow, number_format($totalTTC, 2, ',', ' '));
                $sheet->setCellValue('L'.$totalRow, count($this->notes) . ' avoirs');

                $event->sheet->getStyle('A'.$totalRow.':O'.$totalRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '6B1A1A']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE7E7']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '6B1A1A']]],
                ]);

                // Footer
                $footerRow = $totalRow + 2;
                $sheet->setCellValue('A'.$footerRow, 'Exporté le ' . Carbon::now()->format('d/m/Y à H:i') . ' — AZ ERP');
                $sheet->mergeCells('A'.$footerRow.':O'.$footerRow);
                $event->sheet->getStyle('A'.$footerRow)->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '888888']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            },
        ];
    }
}


// ═══════════════════════════════════════════════════════════════
// FEUILLE 2 : ECRITURE COMPTABLE avoirs de vente
// ═══════════════════════════════════════════════════════════════
class SalesNotesEcritureComptableSheet implements FromArray, WithTitle, WithStyles, WithEvents
{
    protected $request;
    protected $notes;
    protected $sectionRows = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->notes   = $this->loadNotes();
    }

    public function title(): string { return 'ECRITURE COMPTABLE'; }

    private function loadNotes()
    {
        $query = SalesNote::with(['customer'])
            ->orderBy('note_date', 'asc');

        if ($this->request->filled('customer_id'))
            $query->where('customer_id', $this->request->customer_id);
        if ($this->request->filled('status'))
            $query->where('status', $this->request->status);
        if ($this->request->filled('date_from'))
            $query->whereDate('note_date', '>=', $this->request->date_from);
        if ($this->request->filled('date_to'))
            $query->whereDate('note_date', '<=', $this->request->date_to);

        return $query->get();
    }

    public function array(): array
    {
        $rows = [];
        $currentRow = 1;

        // En-tête colonnes info avoir
        $rows[] = [
            'Numéro Avoir', 'Date Avoir', 'Client', 'Référence',
            'Statut Avoir', 'Payé', 'Type', 'Total HT (€)', 'TOTAL TVA',
            'Total TTC (€)', 'TVA (%)', 'Nb Lignes', 'Num Client', 'Créé le', 'Mis à jour le',
        ];
        $this->sectionRows[] = ['type' => 'main_header', 'row' => $currentRow++];

        foreach ($this->notes as $note) {
            $customerText = $note->customer
                ? $note->customer->name . ' (' . $note->customer->code . ')' : '-';

            $refText = '-';
            if ($note->sales_invoice_id && $note->salesInvoice)
                $refText = 'Facture: ' . $note->salesInvoice->numdoc;
            elseif ($note->sales_return_id && $note->salesReturn)
                $refText = 'Retour: ' . $note->salesReturn->numdoc;

            $paid = $note->paid ? '🟢 PAYÉ' : '🔴 NON PAYÉ';

            $ht     = round($note->total_ht  ?? 0, 2);
            $ttc    = round($note->total_ttc ?? 0, 2);
            $tva    = round($ttc - $ht, 2);
            $date   = $note->note_date ? Carbon::parse($note->note_date)->format('d/m/Y') : '-';
            $numdoc = $note->numdoc ?? '-';

            $htF  = number_format($ht,  2, ',', ' ');
            $tvaF = number_format($tva, 2, ',', ' ');
            $ttcF = number_format($ttc, 2, ',', ' ');

            // ── Ligne récapitulative avoir ──
            $rows[] = [
                $numdoc, $date, $customerText, $refText,
                ucfirst($note->status ?? '-'), $paid, ucfirst($note->type ?? '-'),
                $htF, $tvaF, $ttcF,
                number_format($note->tva_rate ?? 0, 2, ',', ' ') . '%',
                '-', $note->numclient ?? '-',
                $note->created_at ? Carbon::parse($note->created_at)->format('d/m/Y') : '-',
                $note->updated_at ? Carbon::parse($note->updated_at)->format('d/m/Y') : '-',
            ];
            $this->sectionRows[] = ['type' => 'note_info', 'row' => $currentRow++];

            // ── Titre "Avoir de vente" ──
            $rows[] = ['Avoir de vente'];
            $this->sectionRows[] = ['type' => 'avoir_header', 'row' => $currentRow++];

            // ── En-tête colonnes journal ──
            $rows[] = ['JOURNAL', '', 'D', 'C', 'DATE DE FACTURE', 'N°Pièce'];
            $this->sectionRows[] = ['type' => 'journal_header', 'row' => $currentRow++];

            // Compte produit HT  → Débit (annulation vente)
            $rows[] = ['VE', '70702000', $htF, '', $date, $numdoc];
            $this->sectionRows[] = ['type' => 'journal_line', 'row' => $currentRow++];

            // Compte TVA collectée → Débit (annulation TVA)
            $rows[] = ['VE', '44571000', $tvaF, '', $date, $numdoc];
            $this->sectionRows[] = ['type' => 'journal_line', 'row' => $currentRow++];

            // Compte client (9CB) → Crédit (remboursement client)
            $rows[] = ['VE', '9CB', '', $ttcF, $date, $numdoc];
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
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6B1A1A']],
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
                        case 'note_info':
                            $event->sheet->getStyle('A'.$row.':O'.$row)->applyFromArray([
                                'font' => ['bold' => true, 'size' => 10],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5D0D0']],
                                'borders' => [
                                    'top'    => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '8B0000']],
                                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '8B0000']],
                                ],
                            ]);
                            break;

                        case 'avoir_header':
                            $event->sheet->getStyle('A'.$row)->applyFromArray([
                                'font' => ['bold' => true, 'color' => ['rgb' => '8B0000'], 'size' => 10],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE7E7']],
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
                            break;
                    }
                }

                foreach (range('A', 'F') as $col)
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                foreach (range('G', 'O') as $col)
                    $sheet->getColumnDimension($col)->setWidth(16);

                $sheet->freezePane('A2');

                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->setCellValue('A'.$lastRow, 'Export comptable avoirs — AZ ERP — ' . Carbon::now()->format('d/m/Y à H:i'));
                $event->sheet->getStyle('A'.$lastRow)->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '888888']],
                ]);
                $sheet->mergeCells('A'.$lastRow.':O'.$lastRow);
            },
        ];
    }
}