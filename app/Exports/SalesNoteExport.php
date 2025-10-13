<?php

namespace App\Exports;

use App\Models\SalesNote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Events\AfterSheet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesNoteExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles,
    WithEvents,
    ShouldAutoSize
{
    protected $note;

    public function __construct(SalesNote $note)
    {
        $this->note = $note;
    }

    public function collection(): Collection
    {
        // Ligne 1: Ligne vide pour espacement
        $emptyRow = (object) [
            'En-tête Avoir' => '',
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => '',
            'Total HT' => '',
            'Total TTC' => '',
        ];

        $data = collect([$emptyRow]);

        // Ligne 2: En-tête de l'avoir (avec fond bleu)
        $customerName = $this->note->customer ? $this->note->customer->name : 'N/A';
        $noteDate = $this->note->note_date ? Carbon::parse($this->note->note_date)->format('d/m/Y') : 'N/A';
        $noteNum = $this->note->numdoc ?? 'N/A';
        
        // Type de référence
        $refText = '';
        if ($this->note->sales_invoice_id) {
            $refText = ' (Facture: ' . ($this->note->salesInvoice->numdoc ?? 'N/A') . ')';
        } elseif ($this->note->sales_return_id) {
            $refText = ' (Retour: ' . ($this->note->salesReturn->numdoc ?? 'N/A') . ')';
        }
        
        $headerText = 'AVOIR N° ' . $noteNum . ' - Client: ' . $customerName . ' - Date: ' . $noteDate . $refText;
        
        $headerRow = (object) [
            'En-tête Avoir' => $headerText,
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => '',
            'Total HT' => '',
            'Total TTC' => '',
        ];

        $data->push($headerRow);

        // Ligne 3: Séparation
        $data->push($emptyRow);

        // Vos lignes de données (logique originale pour avoirs)
        $linesData = $this->note->lines->map(function ($line) {
            return (object) [
                'En-tête Avoir' => '',
                'Article' => $line->item ? $line->item->name : ($line->description ?? $line->article_code ?? '-'),
                'Quantité' => $line->quantity ?? 0,
                'Prix Unitaire HT' => $line->unit_price_ht ?? 0,
                'Remise (%)' => $line->remise ?? 0,
                'Total HT' => $line->total_ligne_ht ?? 0,
                'Total TTC' => $line->total_ligne_ttc ?? 0,
            ];
        });

        $data = $data->concat($linesData);

        // Ligne de séparation après les données
        $data->push($emptyRow);

        // Calcul des totaux
        $totalHT = collect($this->note->lines)->sum(function ($line) {
            return $line->total_ligne_ht ?? 0;
        });
        $totalTTC = collect($this->note->lines)->sum(function ($line) {
            return $line->total_ligne_ttc ?? 0;
        });

        // Ligne Total HT
        $totalHTRow = (object) [
            'En-tête Avoir' => '',
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => 'TOTAL HT',
            'Total HT' => $totalHT,
            'Total TTC' => '',
        ];
        $data->push($totalHTRow);

        // Ligne Total TTC
        $totalTTCRow = (object) [
            'En-tête Avoir' => '',
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => 'TOTAL TTC',
            'Total HT' => $totalTTC,
            'Total TTC' => '',
        ];
        $data->push($totalTTCRow);

        // Ligne de séparation finale
        $data->push($emptyRow);

        // Pied de page
        $footerRow = (object) [
            'En-tête Avoir' => 'AZ NEGOCE - Merci pour votre confiance',
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => '',
            'Total HT' => '',
            'Total TTC' => '',
        ];
        $data->push($footerRow);

        return $data;
    }

    public function headings(): array
    {
        return [
            'En-tête Avoir',
            'Article',
            'Quantité',
            'Prix Unitaire HT',
            'Remise (%)',
            'Total HT',
            'Total TTC',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $linesCount = count($this->note->lines);
        $headerRow = 2;        // Ligne 2: En-tête avec fond bleu
        $separatorRow1 = 1;    // Ligne 1: Séparation
        $separatorRow2 = 3;    // Ligne 3: Séparation
        $headersRow = 4;       // Ligne 4: En-têtes colonnes
        $dataStartRow = 5;     // Ligne 5: Données
        $dataEndRow = $dataStartRow + $linesCount - 1;
        $separatorRow3 = $dataEndRow + 1;
        $totalRow = $separatorRow3 + 1;
        $totalTTCRow = $totalRow + 1;
        $separatorRow4 = $totalTTCRow + 1;
        $footerRow = $separatorRow4 + 1;
        
        return [
            // Ligne 1: Séparation haute
            $separatorRow1 => [
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                ],
            ],
            
            // Ligne 2: En-tête de l'avoir (FOND BLEU)
            $headerRow => [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E79'], // ← FOND BLEU SUR LIGNE 2
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                ],
            ],
            
            // Ligne 3: Séparation entre en-tête et tableau
            $separatorRow2 => [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                ],
            ],
            
            // Ligne 4: En-têtes des colonnes (fond gris clair)
            $headersRow => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'color' => ['rgb' => '333333'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F5F5F5'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
            
            // Lignes 5 à N: Données des articles
            'B5:F' . $dataEndRow => [
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
                    'size' => 8.5,
                ],
            ],
            
            // Alignement des colonnes numériques
            'D5:D' . $dataEndRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'F5:F' . $dataEndRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            'G5:G' . $dataEndRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            // Séparation après données
            $separatorRow3 => [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                        'color' => ['rgb' => '1F4E79'],
                        'width' => 2,
                    ],
                ],
            ],
            
            // Ligne Total HT
            $totalRow => [
                'font' => [
                    'bold' => true,
                    'size' => 9.5,
                    'color' => ['rgb' => '1F4E79'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8F9FA'],
                ],
            ],
            
            // Ligne Total TTC (mise en évidence)
            $totalTTCRow => [
                'font' => [
                    'bold' => true,
                    'size' => 10.5,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E79'],
                ],
            ],
            
            // Séparation avant footer
            $separatorRow4 => [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
            
            // Pied de page
            $footerRow => [
                'font' => [
                    'italic' => true,
                    'size' => 8,
                    'color' => ['rgb' => '666666'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $note = $this->note;
                $linesCount = count($this->note->lines);
                
                $separatorRow1 = 1;
                $headerRow = 2;        // ← EN-TÊTE AVEC FOND BLEU
                $separatorRow2 = 3;
                $headersRow = 4;
                $dataStartRow = 5;
                $dataEndRow = $dataStartRow + $linesCount - 1;
                $separatorRow3 = $dataEndRow + 1;
                $totalRow = $separatorRow3 + 1;
                $totalTTCRow = $totalRow + 1;
                $separatorRow4 = $totalTTCRow + 1;
                $footerRow = $separatorRow4 + 1;
                
                // 1. FUSION DE L'EN-TÊTE (ligne 2, A à G) ← FOND BLEU ICI
                $sheet->mergeCells('A' . $headerRow . ':G' . $headerRow);
                
                // 2. FUSION DU PIED DE PAGE
                $sheet->mergeCells('A' . $footerRow . ':G' . $footerRow);
                
                // 3. HAUTEURS DES LIGNES (compactes)
                $sheet->getRowDimension($separatorRow1)->setRowHeight(3);
                $sheet->getRowDimension($headerRow)->setRowHeight(22);         // En-tête bleu
                $sheet->getRowDimension($separatorRow2)->setRowHeight(3);
                $sheet->getRowDimension($headersRow)->setRowHeight(18);
                for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(16);
                }
                $sheet->getRowDimension($separatorRow3)->setRowHeight(3);
                $sheet->getRowDimension($totalRow)->setRowHeight(18);
                $sheet->getRowDimension($totalTTCRow)->setRowHeight(20);
                $sheet->getRowDimension($separatorRow4)->setRowHeight(3);
                $sheet->getRowDimension($footerRow)->setRowHeight(16);
                
                // 4. LARGEURS DES COLONNES
                $sheet->getColumnDimension('A')->setWidth(55);
                $sheet->getColumnDimension('B')->setWidth(0);  // Invisible
                $sheet->getColumnDimension('C')->setWidth(8);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('F')->setWidth(12);
                $sheet->getColumnDimension('G')->setWidth(12);
                
                // 5. FUSION DES CELLULES VIDES POUR L'EN-TÊTE (B à G ligne 2)
                for ($col = 'B'; $col <= 'G'; $col++) {
                    $sheet->mergeCells($col . $headerRow . ':' . $col . $headerRow);
                }
                
                // 6. BORDURE TABLEAU PRINCIPAL (C à G)
                $sheet->getStyle('C' . $headersRow . ':G' . $dataEndRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                        'inside' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'D3D3D3'],
                        ],
                    ],
                ]);
                
                // 7. BORDURE TOTAUX (E à G)
                $sheet->getStyle('E' . $totalRow . ':G' . $totalTTCRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                        'inside' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'D3D3D3'],
                        ],
                    ],
                ]);
                
                // 8. SÉPARATIONS VISUELLES
                $sheet->getStyle('A' . $separatorRow1 . ':G' . $separatorRow1)->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                ]);
                
                $sheet->getStyle('A' . $separatorRow2 . ':G' . $separatorRow2)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                ]);
                
                $sheet->getStyle('A' . $separatorRow3 . ':G' . $separatorRow3)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                            'color' => ['rgb' => '1F4E79'],
                            'width' => 2,
                        ],
                    ],
                ]);
                
                $sheet->getStyle('A' . $separatorRow4 . ':G' . $separatorRow4)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);
                
                // 9. FORMAT NUMÉRIQUE POUR LES TOTAUX
                $sheet->getStyle('F' . $totalRow . ':G' . $totalTTCRow)->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                
                // 10. MASQUER COLONNE B
                $sheet->getColumnDimension('B')->setVisible(false);
                
                // 11. WRAP TEXT POUR L'EN-TÊTE
                $sheet->getStyle('A' . $headerRow)->getAlignment()->setWrapText(true);
                
                // 12. GEL DES EN-TÊTES
                $sheet->freezePane('C5');
            },
        ];
    }
}