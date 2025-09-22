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
        $totalLines = $linesCount + 5; // En-tête + séparation + 2 totaux + séparation + footer
        
        return [
            // Ligne 1: En-tête facture (taille réduite)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 10, // Réduit de 14 à 10
                    'color' => ['rgb' => '1F4E79'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
            ],
            
            // Ligne 3: En-têtes des colonnes (taille réduite)
            3 => [
                'font' => [
                    'bold' => true,
                    'size' => 9, // Réduit de 11 à 9
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E79'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            
            // Lignes 4 à N: Données des articles
            'A4:F' . (3 + $linesCount) => [
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
                    'size' => 9, // Taille réduite pour les données
                ],
            ],
            
            // Alignement des colonnes numériques (lignes de données)
            'C4:C' . (3 + $linesCount) => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'E4:F' . (3 + $linesCount) => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            // Ligne Total HT (ligne 3 + linesCount + 2)
            (3 + $linesCount + 2) => [
                'font' => [
                    'bold' => true,
                    'size' => 10, // Taille réduite
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
            
            // Ligne Total TTC (ligne 3 + linesCount + 3)
            (3 + $linesCount + 3) => [
                'font' => [
                    'bold' => true,
                    'size' => 11, // Légèrement plus grand pour le total final
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
            
            // Dernière ligne: Pied de page (taille réduite)
            (3 + $linesCount + 6) => [
                'font' => [
                    'italic' => true,
                    'size' => 8, // Réduit de 10 à 8
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
                $totalTTCRow = $totalRow + 1;
                $footerRow = $totalTTCRow + 3;
                
                // 1. FUSION DE L'EN-TÊTE (ligne 1, colonnes A à G)
                $sheet->mergeCells('A' . $headerRow . ':G' . $headerRow);
                
                // 2. FUSION DU PIED DE PAGE (dernière ligne, A à G)
                $sheet->mergeCells('A' . $footerRow . ':G' . $footerRow);
                
                // 3. HAUTEURS DES LIGNES (réduites)
                $sheet->getRowDimension($headerRow)->setRowHeight(25);        // En-tête (réduit de 30 à 25)
                $sheet->getRowDimension(2)->setRowHeight(3);                   // Séparation fine (réduit de 5 à 3)
                $sheet->getRowDimension($headersRow)->setRowHeight(20);        // En-têtes tableau (réduit de 25 à 20)
                for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(18);             // Lignes de données (réduit de 20 à 18)
                }
                $sheet->getRowDimension($separatorRow)->setRowHeight(3);       // Séparation (réduit de 5 à 3)
                $sheet->getRowDimension($totalRow)->setRowHeight(20);          // Ligne TOTAL HT (réduit de 25 à 20)
                $sheet->getRowDimension($totalTTCRow)->setRowHeight(22);       // Ligne TOTAL TTC (réduit de 25 à 22)
                $sheet->getRowDimension($totalTTCRow + 1)->setRowHeight(3);    // Séparation (réduit de 5 à 3)
                $sheet->getRowDimension($footerRow)->setRowHeight(15);         // Pied de page (réduit de 20 à 15)
                
                // 4. LARGEURS DES COLONNES (optimisées)
                $sheet->getColumnDimension('A')->setWidth(60); // En-tête (plus large pour texte long)
                $sheet->getColumnDimension('B')->setWidth(0);  // Invisible
                $sheet->getColumnDimension('C')->setWidth(8);  // Quantité (réduit de 10 à 8)
                $sheet->getColumnDimension('D')->setWidth(12); // PU HT (réduit de 14 à 12)
                $sheet->getColumnDimension('E')->setWidth(10); // Remise (réduit de 12 à 10)
                $sheet->getColumnDimension('F')->setWidth(12); // Total HT (réduit de 14 à 12)
                $sheet->getColumnDimension('G')->setWidth(12); // Total TTC (réduit de 14 à 12)
                
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
                $sheet->getStyle('E' . $totalRow . ':G' . $totalTTCRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                ]);
                
                // 7. FORMAT NUMÉRIQUE POUR LES TOTAUX
                $sheet->getStyle('F' . $totalRow . ':G' . $totalTTCRow)->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                    
                // 8. MASQUER LA COLONNE B (invisible)
                $sheet->getColumnDimension('B')->setVisible(false);
                
                // 9. AJUSTEMENT SPÉCIAL POUR L'EN-TÊTE (wrap text et centrage)
                $sheet->getStyle('A' . $headerRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}