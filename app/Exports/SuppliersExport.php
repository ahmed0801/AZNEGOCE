<?php

namespace App\Exports;

use App\Models\Supplier;
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

class SuppliersExport implements 
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
        $query = Supplier::with([
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

        return $query->orderBy('name')->get()->map(function ($supplier) {
            // TVA Group
            $tvaGroupText = '-';
            if ($supplier->tvaGroup) {
                $tvaGroupText = $supplier->tvaGroup->name . ' (' . $supplier->tvaGroup->rate . '%)';
            }

            // Groupe Remise
            $discountGroupText = '-';
            if ($supplier->discountGroup) {
                $discountGroupText = $supplier->discountGroup->name . ' (' . $supplier->discountGroup->discount_rate . '%)';
            }

            // Mode de paiement
            $paymentModeText = '-';
            if ($supplier->paymentMode) {
                $paymentModeText = $supplier->paymentMode->name;
            }

            // Condition de paiement
            $paymentTermText = '-';
            if ($supplier->paymentTerm) {
                $paymentTermText = $supplier->paymentTerm->label . ' (' . $supplier->paymentTerm->days . ' jours)';
            }

            // Statut
            $statusText = $supplier->blocked ? '🔴 BLOQUÉ' : '🟢 ACTIF';

            return (object) [
                'Code' => $supplier->code ?? '-',
                'Nom' => $supplier->name ?? '-',
                'Email' => $supplier->email ?? '-',
                'Téléphone 1' => $supplier->phone1 ?? '-',
                'Téléphone 2' => $supplier->phone2 ?? '-',
                'Adresse' => $supplier->address ?? '-',
                'Ville' => $supplier->city ?? '-',
                'Pays' => $supplier->country ?? '-',
                'Matricule Fiscale' => $supplier->matfiscal ?? '-',
                'Compte Bancaire' => $supplier->bank_no ?? '-',
                'Solde (€)' => number_format($supplier->solde ?? 0, 2, ',', ' '),
                'Plafond (€)' => number_format($supplier->plafond ?? 0, 2, ',', ' '),
                'Risque' => $supplier->risque ?? 0,
                'Groupe TVA' => $tvaGroupText,
                'Groupe Remise' => $discountGroupText,
                'Mode de Paiement' => $paymentModeText,
                'Condition de Paiement' => $paymentTermText,
                'Statut' => $statusText,
                'Créé le' => $supplier->created_at ? \Carbon\Carbon::parse($supplier->created_at)->format('d/m/Y') : '-',
                'Mis à jour le' => $supplier->updated_at ? \Carbon\Carbon::parse($supplier->updated_at)->format('d/m/Y') : '-',
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
            'A2:T' . $lastRow => [
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
                $suppliers = $this->getSuppliersForTotals();
                $lastRow = $suppliers->count() + 1;
                
                // Calculer les totaux
                $totalSolde = $suppliers->sum(function ($supplier) {
                    return $supplier->solde ?? 0;
                });
                
                $totalPlafond = $suppliers->sum(function ($supplier) {
                    return $supplier->plafond ?? 0;
                });
                
                // Ajouter ligne de total
                $sheet->insertNewRowBefore($lastRow + 1, 1);
                
                // Écrire les totaux
                $sheet->setCellValue('A' . ($lastRow + 1), 'TOTAL GÉNÉRAL');
                $sheet->setCellValue('K' . ($lastRow + 1), $totalSolde);
                $sheet->setCellValue('L' . ($lastRow + 1), $totalPlafond);
                $sheet->setCellValue('R' . ($lastRow + 1), $suppliers->count() . ' fournisseurs');
                
                // Style ligne total
                $event->sheet->getStyle('A' . ($lastRow + 1) . ':T' . ($lastRow + 1))->applyFromArray([
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
                foreach (range('A', 'T') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Gel des premières lignes et colonnes
                $sheet->freezePane('A2');
                
                // Ajouter informations en bas
                $footerRow = $lastRow + 3;
                $sheet->insertNewRowBefore($footerRow, 2);
                
                $exportDate = \Carbon\Carbon::now()->format('d/m/Y à H:i');
                
                $event->sheet->getStyle('A' . $footerRow . ':T' . ($footerRow + 1))->applyFromArray([
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
                $sheet->mergeCells('A' . $footerRow . ':T' . $footerRow);
                $sheet->getRowDimension($footerRow)->setRowHeight(20);
                
                $sheet->setCellValue('A' . ($footerRow + 1), 'Nombre total de fournisseurs : ' . $suppliers->count());
                $sheet->mergeCells('A' . ($footerRow + 1) . ':T' . ($footerRow + 1));
                $sheet->getRowDimension($footerRow + 1)->setRowHeight(18);
                
                // Ajuster la hauteur des lignes
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
                
                // Colorer les lignes par statut
                $suppliers->each(function ($supplier, $index) use ($event) {
                    $row = $index + 2;
                    $fillColor = $supplier->blocked ? 'FFF2F2' : 'F8FFF8';
                    
                    $event->sheet->getStyle('A' . $row . ':T' . $row)->applyFromArray([
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
     * Méthode helper pour récupérer les suppliers pour les totaux
     */
    private function getSuppliersForTotals()
    {
        $query = Supplier::query();

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

        return $query->get();
    }
}