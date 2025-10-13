<?php

namespace App\Exports;

use App\Models\SalesNote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class SalesNotesExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $query = SalesNote::with([
            'customer',
            'lines.item',
            'salesInvoice',
            'salesReturn'
        ])->orderBy('updated_at', 'desc');

        // Appliquer les filtres
        if ($this->request->filled('customer_id')) {
            $query->where('customer_id', $this->request->customer_id);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('note_date', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('note_date', '<=', $this->request->date_to);
        }

        return $query->get()->map(function ($note) {
            // Référence
            $refText = '-';
            if ($note->sales_invoice_id) {
                $refText = 'Facture: ' . ($note->salesInvoice->numdoc ?? 'N/A');
            } elseif ($note->sales_return_id) {
                $refText = 'Retour: ' . ($note->salesReturn->numdoc ?? 'N/A');
            }

            // Statut de paiement
            $paymentStatus = $note->paid ? '🟢 PAYÉ' : '🔴 NON PAYÉ';
            if ($note->status === 'validée' && !$note->paid) {
                $remaining = $note->getRemainingBalanceAttribute() ?? $note->total_ttc;
                $paymentStatus = '🔴 NON PAYÉ (' . number_format($remaining, 2, ',', ' ') . ' €)';
            }

            // Nombre de lignes
            $linesCount = $note->lines ? $note->lines->count() : 0;

            return (object) [
                'Numéro Avoir' => $note->numdoc ?? '-',
                'Date Avoir' => $note->note_date ? Carbon::parse($note->note_date)->format('d/m/Y') : '-',
                'Client' => $note->customer ? $note->customer->name . ' (' . $note->customer->code . ')' : '-',
                'Référence' => $refText,
                'Statut Avoir' => ucfirst($note->status ?? '-'),
                'Payé' => $paymentStatus,
                'Type' => ucfirst($note->type ?? '-'),
                'Total HT' => number_format($note->total_ht ?? 0, 2, ',', ' '),
                'Total TTC' => number_format($note->total_ttc ?? 0, 2, ',', ' '),
                'TVA Rate' => number_format($note->tva_rate ?? 0, 2, ',', ' ') . '%',
                'Nb Lignes' => $linesCount,
                'Num Client' => $note->numclient ?? '-',
                'Créé le' => $note->created_at ? Carbon::parse($note->created_at)->format('d/m/Y') : '-',
                'Mis à jour le' => $note->updated_at ? Carbon::parse($note->updated_at)->format('d/m/Y') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Numéro Avoir',
            'Date Avoir',
            'Client',
            'Référence',
            'Statut Avoir',
            'Payé',
            'Type',
            'Total HT (€)',
            'Total TTC (€)',
            'TVA (%)',
            'Nb Lignes',
            'Num Client',
            'Créé le',
            'Mis à jour le',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->collection()->count() + 1;
        
        return [
            // En-tête principal
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => ['rgb' => '1F4E79'],
                    'endColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            // Données
            'A2:N' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'D3D3D3'],
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'font' => [
                    'size' => 9,
                ],
            ],
            
            // Colonnes monétaires
            'H2:H' . $lastRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'I2:I' . $lastRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            // Colonne statut
            'F2:F' . $lastRow => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $notes = $this->getNotesForTotals();
                $lastRow = $notes->count() + 1;
                
                // Calculer les totaux
                $totalHT = $notes->sum(function ($note) {
                    return $note->total_ht ?? 0;
                });
                
                $totalTTC = $notes->sum(function ($note) {
                    return $note->total_ttc ?? 0;
                });
                
                $totalLines = $notes->sum(function ($note) {
                    return $note->lines ? $note->lines->count() : 0;
                });
                
                // Ajouter ligne de total
                $sheet->insertNewRowBefore($lastRow + 1, 1);
                
                // Écrire les totaux
                $sheet->setCellValue('A' . ($lastRow + 1), 'TOTAL GÉNÉRAL');
                $sheet->setCellValue('H' . ($lastRow + 1), $totalHT);
                $sheet->setCellValue('I' . ($lastRow + 1), $totalTTC);
                $sheet->setCellValue('K' . ($lastRow + 1), $totalLines);
                $sheet->setCellValue('M' . ($lastRow + 1), $notes->count() . ' avoirs');
                
                // Style ligne total
                $event->sheet->getStyle('A' . ($lastRow + 1) . ':N' . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E7F3FF'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);
                
                // Style totaux monétaires
                $event->sheet->getStyle('H' . ($lastRow + 1) . ':I' . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FF0000'],
                    ],
                ]);
                
                // Auto-ajustement
                foreach (range('A', 'N') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Gel des en-têtes
                $sheet->freezePane('A2');
                
                // Footer
                $footerRow = $lastRow + 3;
                $sheet->insertNewRowBefore($footerRow, 2);
                
                $exportDate = Carbon::now()->format('d/m/Y à H:i');
                
                $event->sheet->getStyle('A' . $footerRow . ':N' . ($footerRow + 1))->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 9,
                        'color' => ['rgb' => '666666'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                $sheet->setCellValue('A' . $footerRow, 'Exporté le ' . $exportDate . ' depuis AZ ERP');
                $sheet->mergeCells('A' . $footerRow . ':N' . $footerRow);
                $sheet->getRowDimension($footerRow)->setRowHeight(20);
                
                $sheet->setCellValue('A' . ($footerRow + 1), 'Nombre total d\'avoirs : ' . $notes->count());
                $sheet->mergeCells('A' . ($footerRow + 1) . ':N' . ($footerRow + 1));
                $sheet->getRowDimension($footerRow + 1)->setRowHeight(18);
                
                // Hauteurs des lignes de données
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(18);
                }
                
                // Coloration par statut
                $notes->each(function ($note, $index) use ($event) {
                    $row = $index + 2;
                    $fillColor = 'F8FFF8'; // Vert par défaut
                    
                    if ($note->status === 'brouillon') {
                        $fillColor = 'FFF8F8'; // Rouge clair
                    } elseif (!$note->paid) {
                        $fillColor = 'FFFFF0'; // Jaune clair
                    }
                    
                    $event->sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor],
                        ],
                    ]);
                });
            },
        ];
    }

    private function getNotesForTotals()
    {
        $query = SalesNote::with(['lines']);

        if ($this->request->filled('customer_id')) {
            $query->where('customer_id', $this->request->customer_id);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('note_date', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('note_date', '<=', $this->request->date_to);
        }

        return $query->get();
    }
}