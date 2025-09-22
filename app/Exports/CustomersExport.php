<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
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
        // S'assurer que $customers est une Collection
        if (!($customers instanceof Collection)) {
            if (is_array($customers)) {
                $customers = collect($customers);
            } else {
                $customers = collect([$customers]);
            }
        }
        
        // Filtrer pour s'assurer que ce sont des objets Customer
        $this->customers = $customers->filter(function ($item) {
            return $item instanceof Customer;
        })->values();
        
        $this->exportDate = Carbon::now()->format('d/m/Y à H:i');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->customers->isEmpty()) {
            return collect([]);
        }

        return $this->customers->map(function ($customer) {
            // Vérification de sécurité : s'assurer que c'est un objet Customer
            if (!($customer instanceof Customer)) {
                return null; // Retourner null pour les éléments invalides
            }

            // Chargement des relations si elles n'existent pas
            if (!$customer->relationLoaded('tvaGroup')) {
                $customer->load('tvaGroup');
            }
            if (!$customer->relationLoaded('discountGroup')) {
                $customer->load('discountGroup');
            }
            if (!$customer->relationLoaded('paymentMode')) {
                $customer->load('paymentMode');
            }
            if (!$customer->relationLoaded('paymentTerm')) {
                $customer->load('paymentTerm');
            }
            if (!$customer->relationLoaded('vehicles')) {
                $customer->load('vehicles');
            }

            return [
                'Code' => $customer->code ?? '',
                'Nom' => $customer->name ?? '',
                'Email' => $customer->email ?? '',
                'Téléphone 1' => $customer->phone1 ?? '',
                'Téléphone 2' => $customer->phone2 ?? '',
                'Adresse' => $customer->address ?? '',
                'Ville' => $customer->city ?? '',
                'Pays' => $customer->country ?? '',
                'Matricule Fiscale' => $customer->matfiscal ?? '',
                'Compte Bancaire' => $customer->bank_no ?? '',
                'Solde (€)' => $customer->solde ?? 0,
                'Plafond (€)' => $customer->plafond ?? 0,
                'Risque' => $customer->risque ?? 0,
                'Groupe TVA' => $customer->tvaGroup ? 
                    ($customer->tvaGroup->name . ' (' . $customer->tvaGroup->rate . '%)') : '',
                'Groupe Remise' => $customer->discountGroup ? 
                    ($customer->discountGroup->name . ' (' . $customer->discountGroup->discount_rate . '%)') : '',
                'Mode Paiement' => $customer->paymentMode ? $customer->paymentMode->name : '',
                'Condition Paiement' => $customer->paymentTerm ? 
                    ($customer->paymentTerm->label . ' (' . $customer->paymentTerm->days . ' jours)') : '',
                'Statut' => $customer->blocked ? '🔴 BLOQUÉ' : '🟢 ACTIF',
                'Nb Véhicules' => $customer->vehicles ? $customer->vehicles->count() : 0,
                'Créé le' => $customer->created_at ? $customer->created_at->format('d/m/Y') : '',
                'Mis à jour le' => $customer->updated_at ? $customer->updated_at->format('d/m/Y') : '',
            ];
        })->filter(); // Supprimer les éléments null
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
                
                // Vérifier s'il y a des données
                if ($this->customers->isEmpty()) {
                    // Message si aucune donnée
                    $sheet->setCellValue('A3', 'Aucune donnée à exporter avec les filtres appliqués.');
                    $sheet->mergeCells('A3:U3');
                    $event->sheet->getStyle('A3')->applyFromArray([
                        'font' => [
                            'italic' => true,
                            'size' => 12,
                            'color' => ['rgb' => '666666'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    return;
                }
                
                // Ligne de total
                $totalSolde = $this->customers->sum(function ($customer) {
                    return $customer instanceof Customer ? ($customer->solde ?? 0) : 0;
                });
                
                $totalPlafond = $this->customers->sum(function ($customer) {
                    return $customer instanceof Customer ? ($customer->plafond ?? 0) : 0;
                });
                
                $totalVehicules = $this->customers->sum(function($customer) {
                    if ($customer instanceof Customer && $customer->relationLoaded('vehicles')) {
                        return $customer->vehicles->count();
                    }
                    return 0;
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
                
                $sheet->setCellValue('A' . ($footerRow + 1), 'Nombre total de clients exportés : ' . $this->customers->count());
                $sheet->mergeCells('A' . ($footerRow + 1) . ':U' . ($footerRow + 1));
                $sheet->getRowDimension($footerRow + 1)->setRowHeight(18);
                
                // Ajuster la hauteur des lignes
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
                
                // Colorer les lignes par statut pour une meilleure lisibilité
                $this->customers->each(function ($customer, $index) use ($event, $sheet) {
                    if ($customer instanceof Customer) {
                        $row = $index + 2;
                        $fillColor = $customer->blocked ? 'FFF2F2' : 'F8FFF8';
                        $event->sheet->getStyle('A' . $row . ':U' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $fillColor],
                            ],
                        ]);
                    }
                });
            },
        ];
    }
}