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
use Illuminate\Http\Request;
use Maatwebsite\Excel\Events\AfterSheet as EventsAfterSheet;

class CustomersExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles,
    WithEvents,
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $query = Customer::with([
            'vehicles',
            'tvaGroup',
            'discountGroup', 
            'paymentMode',
            'paymentTerm'
        ]);

        // Appliquer les filtres
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('phone1', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        if ($this->request->filled('status')) {
            $query->where('blocked', $this->request->status == 'blocked' ? 1 : 0);
        }

        if ($this->request->filled('city')) {
            $query->where('city', 'LIKE', "%{$this->request->city}%");
        }

        if ($this->request->filled('min_solde')) {
            $query->where('solde', '>=', $this->request->min_solde);
        }

        if ($this->request->filled('max_solde')) {
            $query->where('solde', '<=', $this->request->max_solde);
        }

        if ($this->request->filled('tva_group_id')) {
            $query->where('tva_group_id', $this->request->tva_group_id);
        }

        if ($this->request->filled('discount_group_id')) {
            $query->where('discount_group_id', $this->request->discount_group_id);
        }

        return $query->orderBy('name')->get()->map(function ($customer) {
            // TVA Group
            $tvaGroupText = '-';
            if ($customer->tvaGroup) {
                $tvaGroupText = $customer->tvaGroup->name . ' (' . $customer->tvaGroup->rate . '%)';
            }

            // Groupe Remise
            $discountGroupText = '-';
            if ($customer->discountGroup) {
                $discountGroupText = $customer->discountGroup->name . ' (' . $customer->discountGroup->discount_rate . '%)';
            }

            // Mode de paiement
            $paymentModeText = '-';
            if ($customer->paymentMode) {
                $paymentModeText = $customer->paymentMode->name;
            }

            // Condition de paiement
            $paymentTermText = '-';
            if ($customer->paymentTerm) {
                $paymentTermText = $customer->paymentTerm->label . ' (' . $customer->paymentTerm->days . ' jours)';
            }

            // Statut
            $statusText = $customer->blocked ? '🔴 BLOQUÉ' : '🟢 ACTIF';

            // Nombre de véhicules
            $vehiclesCount = $customer->vehicles ? $customer->vehicles->count() : 0;

            return (object) [
                'Code' => $customer->code ?? '-',
                'Nom' => $customer->name ?? '-',
                'Email' => $customer->email ?? '-',
                'Téléphone 1' => $customer->phone1 ?? '-',
                'Téléphone 2' => $customer->phone2 ?? '-',
                'Adresse' => $customer->address ?? '-',
                'Ville' => $customer->city ?? '-',
                'Pays' => $customer->country ?? '-',
                'Matricule Fiscale' => $customer->matfiscal ?? '-',
                'Compte Bancaire' => $customer->bank_no ?? '-',
                'Solde (€)' => number_format($customer->solde ?? 0, 2, ',', ' '),
                'Plafond (€)' => number_format($customer->plafond ?? 0, 2, ',', ' '),
                'Risque' => $customer->risque ?? 0,
                'Groupe TVA' => $tvaGroupText,
                'Groupe Remise' => $discountGroupText,
                'Mode de Paiement' => $paymentModeText,
                'Condition de Paiement' => $paymentTermText,
                'Statut' => $statusText,
                'Nb Véhicules' => $vehiclesCount,
                'Créé le' => $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') : '-',
                'Mis à jour le' => $customer->updated_at ? \Carbon\Carbon::parse($customer->updated_at)->format('d/m/Y') : '-',
            ];
        });
    }

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

    public function columnFormats(): array
    {
        return [
            'K' => '#,##0.00', // Solde
            'L' => '#,##0.00', // Plafond
            'M' => '#,##0',    // Risque
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
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => ['rgb' => '1F4E79'],
                    'endColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                ],
            ],
            
            // Données - bordures
            'A2:U' . $lastRow => [
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
            
            // Colonnes monétaires - alignement à droite
            'K2:K' . $lastRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'L2:L' . $lastRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            EventsAfterSheet::class => function(EventsAfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $customers = $this->getCustomersForTotals(); // Méthode helper pour les totaux
                $lastRow = $customers->count() + 1;
                
                // Calculer les totaux
                $totalSolde = $customers->sum(function ($customer) {
                    return $customer->solde ?? 0;
                });
                
                $totalPlafond = $customers->sum(function ($customer) {
                    return $customer->plafond ?? 0;
                });
                
                $totalVehicules = $customers->sum(function ($customer) {
                    return $customer->vehicles_count ?? 0;
                });
                
                // Ajouter ligne de total
                $sheet->insertNewRowBefore($lastRow + 1, 1);
                
                // Écrire les totaux
                $sheet->setCellValue('A' . ($lastRow + 1), 'TOTAL GÉNÉRAL');
                $sheet->setCellValue('K' . ($lastRow + 1), $totalSolde);
                $sheet->setCellValue('L' . ($lastRow + 1), $totalPlafond);
                $sheet->setCellValue('S' . ($lastRow + 1), $totalVehicules);
                $sheet->setCellValue('T' . ($lastRow + 1), $customers->count() . ' clients');
                
                // Style ligne total
                $event->sheet->getStyle('A' . ($lastRow + 1) . ':U' . ($lastRow + 1))->applyFromArray([
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
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
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
                
                $exportDate = \Carbon\Carbon::now()->format('d/m/Y à H:i');
                
                $event->sheet->getStyle('A' . $footerRow . ':U' . ($footerRow + 1))->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 9,
                        'color' => ['rgb' => '666666'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                $sheet->setCellValue('A' . $footerRow, 'Exporté le ' . $exportDate . ' depuis AZ ERP');
                $sheet->mergeCells('A' . $footerRow . ':U' . $footerRow);
                $sheet->getRowDimension($footerRow)->setRowHeight(20);
                
                $sheet->setCellValue('A' . ($footerRow + 1), 'Nombre total de clients : ' . $customers->count());
                $sheet->mergeCells('A' . ($footerRow + 1) . ':U' . ($footerRow + 1));
                $sheet->getRowDimension($footerRow + 1)->setRowHeight(18);
                
                // Ajuster la hauteur des lignes
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
                
                // Colorer les lignes par statut
                $customers->each(function ($customer, $index) use ($event) {
                    $row = $index + 2;
                    $fillColor = $customer->blocked ? 'FFF2F2' : 'F8FFF8';
                    
                    $event->sheet->getStyle('A' . $row . ':U' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor],
                        ],
                    ]);
                });
            },
        ];
    }

    /**
     * Méthode helper pour récupérer les customers pour les totaux
     */
    private function getCustomersForTotals()
    {
        $query = Customer::with(['vehicles']);

        // Réappliquer les mêmes filtres que dans collection()
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('phone1', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        if ($this->request->filled('status')) {
            $query->where('blocked', $this->request->status == 'blocked' ? 1 : 0);
        }

        if ($this->request->filled('city')) {
            $query->where('city', 'LIKE', "%{$this->request->city}%");
        }

        if ($this->request->filled('min_solde')) {
            $query->where('solde', '>=', $this->request->min_solde);
        }

        if ($this->request->filled('max_solde')) {
            $query->where('solde', '<=', $this->request->max_solde);
        }

        $customers = $query->get();
        
        // Ajouter vehicles_count à chaque customer
        return $customers->map(function ($customer) {
            $customer->vehicles_count = $customer->vehicles ? $customer->vehicles->count() : 0;
            return $customer;
        });
    }
}