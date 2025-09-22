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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
        // Convertir le tableau en collection d'objets
        if (is_array($customers)) {
            $this->customers = collect(array_map(function ($customer) {
                return (object) $customer;
            }, $customers));
        } else {
            $this->customers = collect($customers)->map(function ($customer) {
                return (object) $customer;
            });
        }
        
        $this->exportDate = Carbon::now()->format('d/m/Y à H:i');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return $this->customers->map(function ($customer) {
            // S'assurer que $customer est un objet
            $customer = (object) $customer;
            
            return (object) [
                'code' => $customer->code ?? '',
                'name' => $customer->name ?? '',
                'email' => $customer->email ?? '',
                'phone1' => $customer->phone1 ?? '',
                'phone2' => $customer->phone2 ?? '',
                'address' => $customer->address ?? '',
                'city' => $customer->city ?? '',
                'country' => $customer->country ?? '',
                'matfiscal' => $customer->matfiscal ?? '',
                'bank_no' => $customer->bank_no ?? '',
                'solde' => $customer->solde ?? 0,
                'plafond' => $customer->plafond ?? 0,
                'risque' => $customer->risque ?? 0,
                'tva_group' => isset($customer->tvaGroup) && is_object($customer->tvaGroup) 
                    ? ($customer->tvaGroup->name ?? '') . ' (' . ($customer->tvaGroup->rate ?? 0) . '%)' 
                    : (isset($customer->tva_group) ? $customer->tva_group : ''),
                'discount_group' => isset($customer->discountGroup) && is_object($customer->discountGroup)
                    ? ($customer->discountGroup->name ?? '') . ' (' . ($customer->discountGroup->discount_rate ?? 0) . '%)'
                    : (isset($customer->discount_group) ? $customer->discount_group : ''),
                'payment_mode' => isset($customer->paymentMode) && is_object($customer->paymentMode) 
                    ? ($customer->paymentMode->name ?? '') 
                    : (isset($customer->payment_mode) ? $customer->payment_mode : ''),
                'payment_term' => isset($customer->paymentTerm) && is_object($customer->paymentTerm)
                    ? ($customer->paymentTerm->label ?? '') . ' (' . ($customer->paymentTerm->days ?? 0) . ' jours)'
                    : (isset($customer->payment_term) ? $customer->payment_term : ''),
                'blocked' => $customer->blocked ?? 0,
                'vehicles_count' => isset($customer->vehicles) && is_array($customer->vehicles) 
                    ? count($customer->vehicles) 
                    : (isset($customer->vehicles_count) ? $customer->vehicles_count : 0),
                'created_at' => isset($customer->created_at) ? Carbon::parse($customer->created_at)->format('d/m/Y') : '',
                'updated_at' => isset($customer->updated_at) ? Carbon::parse($customer->updated_at)->format('d/m/Y') : '',
            ];
        });
    }

    /**
     * En-têtes du tableau
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
     * Formatage des colonnes monétaires
     */
    public function columnFormats(): array
    {
        return [
            'K' => '#,##0.00', // Solde
            'L' => '#,##0.00', // Plafond
            'M' => '#,##0',    // Risque
        ];
    }

    /**
     * Styles du document
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
                    'startColor' => ['rgb' => '1F4E79'],
                    'endColor' => ['rgb' => '4472C4'],
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
            
            // Données - bordures
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
            
            // Colonnes monétaires - alignement à droite
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
            
            // Colonne statut - couleur conditionnelle
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
     * Événements après génération du sheet
     */
    public function registerEvents(): array
    {
        return [
            EventsAfterSheet::class => function(EventsAfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->customers->count() + 1;
                
                // Calculer les totaux de manière sécurisée
                $totalSolde = $this->customers->sum(function ($customer) {
                    $customer = (object) $customer;
                    return $customer->solde ?? 0;
                });
                
                $totalPlafond = $this->customers->sum(function ($customer) {
                    $customer = (object) $customer;
                    return $customer->plafond ?? 0;
                });
                
                $totalVehicules = $this->customers->sum(function ($customer) {
                    $customer = (object) $customer;
                    return $customer->vehicles_count ?? 0;
                });
                
                // Ajouter ligne de total
                $sheet->insertNewRowBefore($lastRow + 1, 1);
                
                // Écrire les totaux
                $sheet->setCellValue('A' . ($lastRow + 1), 'TOTAL GÉNÉRAL');
                $sheet->setCellValue('K' . ($lastRow + 1), $totalSolde);
                $sheet->setCellValue('L' . ($lastRow + 1), $totalPlafond);
                $sheet->setCellValue('S' . ($lastRow + 1), $totalVehicules);
                $sheet->setCellValue('T' . ($lastRow + 1), $this->customers->count() . ' clients');
                
                // Style ligne total
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
                
                // Style spécifique pour les totaux
                $event->sheet->getStyle('K' . ($lastRow + 1) . ':L' . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FF0000'],
                    ],
                ]);
                
                // Auto-ajustement des largeurs
                foreach (range('A', 'U') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Gel des premières lignes et colonnes
                $sheet->freezePane('A2');
                
                // Ajouter informations en bas
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
                
                // Colorer les lignes par statut pour une meilleure lisibilité
                $this->customers->each(function ($customer, $index) use ($event) {
                    $customer = (object) $customer;
                    $row = $index + 2;
                    $fillColor = ($customer->blocked ?? 0) ? 'FFF2F2' : 'F8FFF8';
                    
                    $event->sheet->getStyle('A' . $row . ':U' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor],
                        ],
                    ]);
                });
            },
        ];
    }
}