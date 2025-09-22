<?php

namespace App\Exports;

use App\Models\Invoice;
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

class SalesInvoiceExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles,
    WithEvents,
    ShouldAutoSize
{
    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function collection(): Collection
    {
        // En-tête de facture en première ligne
        $customerName = $this->invoice->customer ? $this->invoice->customer->name : 'N/A';
        $invoiceDate = $this->invoice->invoice_date ? Carbon::parse($this->invoice->invoice_date)->format('d/m/Y') : 'N/A';
        $invoiceNum = $this->invoice->numdoc ?? 'N/A';
        
        $headerRow = (object) [
            'En-tête Facture' => 'FACTURE N° ' . $invoiceNum . ' - Client: ' . $customerName . ' - Date: ' . $invoiceDate,
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => '',
            'Total HT' => '',
            'Total TTC' => '',
        ];

        $data = collect([$headerRow]);

        // Vos lignes de données (logique originale)
        $linesData = $this->invoice->lines->map(function ($line) {
            return (object) [
                'En-tête Facture' => '',
                'Article' => $line->item ? $line->item->name : ($line->description ?? $line->article_code ?? '-'),
                'Quantité' => $line->quantity ?? 0,
                'Prix Unitaire HT' => $line->unit_price_ht ?? 0,
                'Remise (%)' => $line->remise ?? 0,
                'Total HT' => $line->total_ligne_ht ?? 0,
                'Total TTC' => $line->total_ligne_ttc ?? 0,
            ];
        });

        $data = $data->concat($linesData);

        // Ligne de séparation
        $separatorRow = (object) [
            'En-tête Facture' => '',
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => '',
            'Total HT' => '',
            'Total TTC' => '',
        ];
        $data->push($separatorRow);

        // Calcul des totaux
        $totalHT = collect($this->invoice->lines)->sum(function ($line) {
            return $line->total_ligne_ht ?? 0;
        });
        $totalTTC = collect($this->invoice->lines)->sum(function ($line) {
            return $line->total_ligne_ttc ?? 0;
        });

        // Ligne Total HT
        $totalHTRow = (object) [
            'En-tête Facture' => '',
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
            'En-tête Facture' => '',
            'Article' => '',
            'Quantité' => '',
            'Prix Unitaire HT' => '',
            'Remise (%)' => 'TOTAL TTC',
            'Total HT' => $totalTTC,
            'Total TTC' => '',
        ];
        $data->push($totalTTCRow);

        // Ligne de séparation finale
        $data->push($separatorRow);

        // Pied de page
        $footerRow = (object) [
            'En-tête Facture' => 'AZ NEGOCE - Merci pour votre confiance',
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
        // En-têtes alignés avec la structure des objets
        return [
            'En-tête Facture',
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
        $linesCount = count($this->invoice->lines);
        
        return [
            // Ligne 1: En-tête facture (gras et fusionnée)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '1F4E79'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            // Ligne 3: En-têtes des colonnes
            3 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E79'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            // Lignes 4 à N: Données des articles (B à F)
            'B4:F' . (3 + $linesCount) => [
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
            ],
            
            // Alignement des colonnes numériques (lignes de données B4:F)
            'D4:D' . (3 + $linesCount) => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'F4:F' . (3 + $linesCount) => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            'G4:G' . (3 + $linesCount) => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            // Ligne des totaux (ligne 3 + linesCount + 3)
            (3 + $linesCount + 3) => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '1F4E79'],
                ],
                'borders' => [
                    'outline' => [
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
            
            // Dernière ligne: Pied de page
            (3 + $linesCount + 6) => [
                'font' => [
                    'italic' => true,
                    'size' => 10,
                    'color' => ['rgb' => '666666'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $invoice = $this->invoice;
                $linesCount = count($this->invoice->lines);
                
                $headerRow = 1;
                $headersRow = 3;
                $dataStartRow = 4;
                $dataEndRow = $dataStartRow + $linesCount - 1;
                $separatorRow = $dataEndRow + 1;
                $totalRow = $separatorRow + 2;
                $footerRow = $totalRow + 3;
                
                // 1. FUSION DE L'EN-TÊTE (ligne 1, colonne A seulement)
                // L'en-tête est déjà dans la colonne A, pas besoin de fusion
                
                // 2. FUSION DU PIED DE PAGE (dernière ligne, A à G)
                $sheet->mergeCells('A' . $footerRow . ':G' . $footerRow);
                
                // 3. HAUTEURS DES LIGNES
                $sheet->getRowDimension($headerRow)->setRowHeight(30);     // En-tête plus haut
                $sheet->getRowDimension(2)->setRowHeight(5);               // Séparation fine
                $sheet->getRowDimension($headersRow)->setRowHeight(25);    // En-têtes tableau
                for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);          // Lignes de données
                }
                $sheet->getRowDimension($separatorRow)->setRowHeight(5);   // Séparation
                $sheet->getRowDimension($totalRow)->setRowHeight(25);      // Ligne TOTAL HT
                $sheet->getRowDimension($totalRow + 1)->setRowHeight(25);  // Ligne TOTAL TTC
                $sheet->getRowDimension($totalRow + 2)->setRowHeight(5);   // Séparation
                $sheet->getRowDimension($footerRow)->setRowHeight(20);     // Pied de page
                
                // 4. LARGEURS DES COLONNES
                $sheet->getColumnDimension('A')->setWidth(50); // En-tête + Article (plus large pour l'en-tête)
                $sheet->getColumnDimension('B')->setWidth(0);  // Invisible (pas utilisé)
                $sheet->getColumnDimension('C')->setWidth(10); // Quantité
                $sheet->getColumnDimension('D')->setWidth(14); // PU HT
                $sheet->getColumnDimension('E')->setWidth(12); // Remise
                $sheet->getColumnDimension('F')->setWidth(14); // Total HT
                $sheet->getColumnDimension('G')->setWidth(14); // Total TTC
                
                // 5. BORDURE AUTOUR DU TABLEAU DES LIGNES (colonnes C à G)
                $sheet->getStyle('C' . $headersRow . ':G' . $dataEndRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                ]);
                
                // 6. BORDURE AUTOUR DES TOTAUX (colonnes E à G)
                $sheet->getStyle('E' . $totalRow . ':G' . ($totalRow + 1))->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                ]);
                
                // 7. FORMAT NUMÉRIQUE POUR LES TOTAUX
                $sheet->getStyle('F' . $totalRow . ':G' . ($totalRow + 1))->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                    
                // 8. MASQUER LA COLONNE B (invisible)
                $sheet->getColumnDimension('B')->setVisible(false);
            },
        ];
    }
}