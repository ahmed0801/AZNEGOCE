<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Events\AfterSheet;
use Carbon\Carbon;

class SalesInvoiceExport implements 
    FromArray,
    WithStyles,
    WithEvents,
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function array(): array
    {
        // Ligne 1: En-tête facture (gras et fusionnée)
        $customerName = $this->invoice->customer ? $this->invoice->customer->name : 'N/A';
        $invoiceDate = $this->invoice->invoice_date ? Carbon::parse($this->invoice->invoice_date)->format('d/m/Y') : 'N/A';
        
        $headerText = 'FACTURE N° ' . ($this->invoice->numdoc ?? 'N/A') . 
                     ' - Client: ' . $customerName .
                     ' - Date: ' . $invoiceDate;
        
        $data = [
            [$headerText], // Ligne 1: En-tête fusionnée (string simple)
            [''],          // Ligne 2: Séparation
            [              // Ligne 3: En-têtes des colonnes (strings simples)
                'Article',
                'Quantité',
                'Prix Unitaire HT',
                'Remise (%)',
                'Total HT',
                'Total TTC',
            ],
        ];
        
        // Lignes 4+: Données des articles (valeurs scalaires)
        foreach ($this->invoice->lines as $line) {
            $data[] = [
                $line->item ? $line->item->name : ($line->description ?? $line->article_code ?? '-'), // string
                $line->quantity ?? 0, // int
                $line->unit_price_ht ?? 0, // float
                $line->remise ?? 0, // float
                $line->total_ligne_ht ?? 0, // float
                $line->total_ligne_ttc ?? 0, // float
            ];
        }
        
        // Ligne de séparation après les données
        $data[] = [''];
        
        // Calcul des totaux
        $totalHT = collect($this->invoice->lines)->sum(function ($line) {
            return $line->total_ligne_ht ?? 0;
        });
        $totalTTC = collect($this->invoice->lines)->sum(function ($line) {
            return $line->total_ligne_ttc ?? 0;
        });
        
        // Ligne de totaux HT
        $data[] = [
            '', // string vide
            '', // string vide
            'TOTAL HT', // string
            '', // string vide
            $totalHT, // float
            '', // string vide
        ];
        
        // Ligne de totaux TTC
        $data[] = [
            '', // string vide
            '', // string vide
            'TOTAL TTC', // string
            '', // string vide
            $totalTTC, // float
            '', // string vide
        ];
        
        // Ligne de séparation
        $data[] = [''];
        
        // Pied de page
        $data[] = ['AZ NEGOCE - Merci pour votre confiance']; // string simple
        
        return $data;
    }

    public function columnFormats(): array
    {
        $linesCount = count($this->invoice->lines);
        $dataStartRow = 4;
        $dataEndRow = $dataStartRow + $linesCount - 1;
        
        return [
            // Formats pour les colonnes numériques (lignes de données uniquement)
            'C' . $dataStartRow . ':C' . $dataEndRow => '#,##0.00', // Prix Unitaire HT
            'E' . $dataStartRow . ':E' . $dataEndRow => '#,##0.00', // Total HT
            'F' . $dataStartRow . ':F' . $dataEndRow => '#,##0.00', // Total TTC
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $linesCount = count($this->invoice->lines);
        $headerRow = 1;
        $headersRow = 3;
        $dataStartRow = 4;
        $dataEndRow = $dataStartRow + $linesCount - 1;
        $totalRow = $dataEndRow + 2;
        $footerRow = $totalRow + 2;
        
        return [
            // Ligne 1: En-tête facture (gras et fusionnée)
            $headerRow => [
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
            $headersRow => [
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
            
            // Lignes 4 à N: Données des articles
            'A' . $dataStartRow . ':F' . $dataEndRow => [
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
            
            // Alignement des colonnes numériques (lignes de données)
            'C' . $dataStartRow . ':C' . $dataEndRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'E' . $dataStartRow . ':F' . $dataEndRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            // Ligne des totaux HT (ligne $totalRow)
            $totalRow => [
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
            
            // Ligne TOTAL TTC (ligne $totalRow + 1)
            ($totalRow + 1) => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
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
                    'startColor' => ['rgb' => 'E7F3FF'],
                ],
            ],
            
            // Dernière ligne: Pied de page
            $footerRow => [
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
                $totalRow = $dataEndRow + 2;
                $footerRow = $totalRow + 2;
                
                // 1. FUSION DE L'EN-TÊTE (ligne 1)
                $sheet->mergeCells('A' . $headerRow . ':F' . $headerRow);
                
                // 2. FUSION DU PIED DE PAGE (dernière ligne)
                $sheet->mergeCells('A' . $footerRow . ':F' . $footerRow);
                
                // 3. HAUTEURS DES LIGNES
                $sheet->getRowDimension($headerRow)->setRowHeight(30);     // En-tête plus haut
                $sheet->getRowDimension(2)->setRowHeight(5);               // Séparation fine
                $sheet->getRowDimension($headersRow)->setRowHeight(25);    // En-têtes tableau
                for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);          // Lignes de données
                }
                $sheet->getRowDimension($totalRow)->setRowHeight(25);      // Ligne des totaux
                $sheet->getRowDimension($totalRow + 1)->setRowHeight(25);  // Ligne TOTAL TTC
                $sheet->getRowDimension($totalRow + 2)->setRowHeight(5);   // Séparation
                $sheet->getRowDimension($footerRow)->setRowHeight(20);     // Pied de page
                
                // 4. LARGEURS DES COLONNES
                $sheet->getColumnDimension('A')->setWidth(15); // Article
                $sheet->getColumnDimension('B')->setWidth(40); // Désignation (plus large)
                $sheet->getColumnDimension('C')->setWidth(10); // Quantité
                $sheet->getColumnDimension('D')->setWidth(14); // PU HT
                $sheet->getColumnDimension('E')->setWidth(12); // Remise
                $sheet->getColumnDimension('F')->setWidth(14); // Total TTC
                
                // 5. BORDURE AUTOUR DU TABLEAU DES LIGNES
                $sheet->getStyle('A' . $headersRow . ':F' . $dataEndRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                ]);
                
                // 6. BORDURE AUTOUR DES TOTAUX
                $sheet->getStyle('C' . $totalRow . ':F' . ($totalRow + 1))->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                ]);
                
                // 7. FORMAT NUMÉRIQUE POUR LES TOTAUX (appliqué directement)
                $sheet->getStyle('E' . $totalRow . ':F' . ($totalRow + 1))->getNumberFormat()
                    ->setFormatCode('#,##0.00');
            },
        ];
    }
}