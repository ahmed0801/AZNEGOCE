<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
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
use Maatwebsite\Excel\Events\AfterSheet as EventsAfterSheet;

class CustomersExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithEvents,
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $customers;
    protected $exportDate;

    public function __construct($customers)
    {
        $this->customers = $customers;
        $this->exportDate = Carbon::now()->format('d/m/Y à H:i');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->customers->map(function ($customer) {
            return [
                'Code' => $customer->code,
                'Nom' => $customer->name,
                'Email' => $customer->email ?? '',
                'Téléphone 1' => $customer->phone1 ?? '',
                'Téléphone 2' => $customer->phone2 ?? '',
                'Adresse' => $customer->address ?? '',
                'Ville' => $customer->city ?? '',
                'Pays' => $customer->country ?? '',
                'Matricule Fiscale' => $customer->matfiscal ?? '',
                'Compte Bancaire' => $customer->bank_no ?? '',
                'Solde (€)' => $customer->solde,
                'Plafond (€)' => $customer->plafond,
                'Risque' => $customer->risque ?? 0,
                'TVA Group' => $customer->tvaGroup ? $customer->tvaGroup->name . ' (' . $customer->tvaGroup->rate . '%)' : '',
                'Groupe Remise' => $customer->discountGroup ? $customer->discountGroup->name . ' (' . $customer->discountGroup->discount_rate . '%)' : '',
                'Mode Paiement' => $customer->paymentMode ? $customer->paymentMode->name : '',
                'Condition Paiement' => $customer->paymentTerm ? $customer->paymentTerm->label . ' (' . $customer->paymentTerm->days . ' jours)' : '',
                'Statut' => $customer->blocked ? '🔴 BLOQUÉ' : '🟢 ACTIF',
                'Nb Véhicules' => $customer->vehicles->count(),
                'Créé le' => $customer->created_at ? $customer->created_at->format('d/m/Y') : '',
                'Mis à jour le' => $customer->updated_at ? $customer->updated_at->format('d/m/Y') : '',
            ];
        });
    }

    /**
     * En-têtes du tableau (SUPPRESSION DE LA COLONNE #)
     */
    public function headings(): array
    {
        return [
            'Code',
            'Nom',
            'Email',
            'Téléphone 1',
            'Téléphone 2',
            'Adresse',
            'Ville',
            'Pays',
            'Matricule Fiscale',
            'Compte Bancaire',
            'Solde (€)',
            'Plafond (€)',
            'Risque',
            'Groupe TVA',
            'Groupe Remise',
            'Mode de Paiement',
            'Condition de Paiement',
            'Statut',
            'Nb Véhicules',
            'Créé le',
            'Mis à jour le',
        ];
    }

    /**
     * Formatage des colonnes monétaires (CORRECTION DES LETTRES)
     */
    public function columnFormats(): array
    {
        return [
            'K' => '#,##0.00', // Solde (colonne K au lieu de L)
            'L' => '#,##0.00', // Plafond (colonne L au lieu de M)
            'M' => '#,##0',    // Risque (colonne M au lieu de N)
        ];
    }

    /**
     * Styles du document (CORRECTION DES RÉFÉRENCES DE COLONNES)
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->customers->count() + 1;
        
        return [
            // En-tête principal
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => ['rgb' => '1F4E79'], // Bleu foncé
                    'endColor' => ['rgb' => '4472C4'],   // Bleu moyen
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                ],
            ],
            
            // Données - bordures (A2:U au lieu de A2:V)
            'A2:U' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D3D3D3'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
            
            // Colonnes monétaires - alignement à droite (CORRECTION DES LETTRES)
            'K2:K' . $lastRow => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'L2:L' . $lastRow => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            // Colonne statut - couleur conditionnelle (COLONNE R au lieu de S)
            'R2:R' . $lastRow => [
                'font' => [
                    'bold' => true,
                ],
            ],
            
            // Ligne de total (style de base)
            $lastRow + 2 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F2F2F2'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
        ];
    }

    /**
     * Événements après génération du sheet (CORRECTION DES RÉFÉRENCES)
     */
    public function registerEvents(): array
    {
        return [
            EventsAfterSheet::class => function(EventsAfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->customers->count() + 1;
                
                // Ligne de total
                $totalSolde = $this->customers->sum('solde');
                $totalPlafond = $this->customers->sum('plafond');
                $totalVehicules = $this->customers->sum(function($customer) {
                    return $customer->vehicles->count();
                });
                
                // Ajouter ligne de total
                $sheet->insertNewRowBefore($lastRow + 1, 1);
                
                // Écrire les totaux (CORRECTION DES LETTRES DE COLONNES)
                $sheet->setCellValue('A' . ($lastRow + 1), 'TOTAL GÉNÉRAL');
                $sheet->setCellValue('K' . ($lastRow + 1), $totalSolde);      // Solde en K
                $sheet->setCellValue('L' . ($lastRow + 1), $totalPlafond);    // Plafond en L
                $sheet->setCellValue('S' . ($lastRow + 1), $totalVehicules);  // Nb Véhicules en S
                $sheet->setCellValue('T' . ($lastRow + 1), $this->customers->count() . ' clients'); // Total clients en T
                
                // Style ligne total (A:U au lieu de A:V)
                $event->sheet->getStyle('A' . ($lastRow + 1) . ':U' . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '1F4E79'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E7F3FF'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E79'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Style spécifique pour les totaux (K:L au lieu de L:M)
                $event->sheet->getStyle('K' . ($lastRow + 1) . ':L' . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FF0000'],
                    ],
                ]);
                
                // Auto-ajustement des largeurs (A:U au lieu de A:V)
                foreach (range('A', 'U') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Gel des premières lignes et colonnes
                $sheet->freezePane('A2');
                
                // Ajouter informations en bas (A:U au lieu de A:V)
                $footerRow = $lastRow + 3;
                $sheet->insertNewRowBefore($footerRow, 2);
                
                $event->sheet->getStyle('A' . $footerRow . ':U' . ($footerRow + 1))->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 9,
                        'color' => ['rgb' => '666666'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                $sheet->setCellValue('A' . $footerRow, 'Exporté le ' . $this->exportDate . ' depuis AZ ERP');
                $sheet->mergeCells('A' . $footerRow . ':U' . $footerRow);
                $sheet->getRowDimension($footerRow)->setRowHeight(20);
                
                $sheet->setCellValue('A' . ($footerRow + 1), 'Nombre total de clients : ' . $this->customers->count());
                $sheet->mergeCells('A' . ($footerRow + 1) . ':U' . ($footerRow + 1));
                $sheet->getRowDimension($footerRow + 1)->setRowHeight(18);
                
                // Ajuster la hauteur des lignes
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
                
                // Colorer les lignes par statut pour une meilleure lisibilité (A:U au lieu de A:V)
                foreach ($this->customers as $index => $customer) {
                    $row = $index + 2;
                    $fillColor = $customer->blocked ? 'FFF2F2' : 'F8FFF8'; // Rouge clair pour bloqués, vert clair pour actifs
                    $event->sheet->getStyle('A' . $row . ':U' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor],
                        ],
                    ]);
                }
            },
        ];
    }
}