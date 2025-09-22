<?php

namespace App\Exports;

use App\Models\Invoice;
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

class SalesInvoicesExport implements 
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
        $query = Invoice::with([
            'customer',
            'lines.item',
            'vehicle',
            'deliveryNotes',
            'salesReturns'
        ])->orderBy('updated_at', 'desc');

        // Appliquer les filtres
        if ($this->request->filled('customer_id')) {
            $query->where('customer_id', $this->request->customer_id);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('paid')) {
            $query->where('paid', $this->request->paid === '1');
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $this->request->date_to);
        }

        return $query->get()->map(function ($invoice) {
            // Client
            $customerText = '-';
            if ($invoice->customer) {
                $customerText = $invoice->customer->name . ' (' . $invoice->customer->code . ')';
            }

            // Véhicule
            $vehicleText = '-';
            if ($invoice->vehicle) {
                $vehicleText = $invoice->vehicle->license_plate . ' (' . 
                              $invoice->vehicle->brand_name . ' ' . 
                              $invoice->vehicle->model_name . ')';
            }

            // Statut de paiement
            $paymentStatus = $invoice->paid ? '🟢 PAYÉ' : '🔴 NON PAYÉ';
            if ($invoice->paid && $invoice->status === 'validée') {
                $remainingBalance = $this->getRemainingBalance($invoice);
                if ($remainingBalance > 0) {
                    $paymentStatus = '🟡 PARTIELLEMENT PAYÉ (' . number_format($remainingBalance, 2, ',', ' ') . ' €)';
                }
            }

            // Nombre de lignes
            $linesCount = $invoice->lines ? $invoice->lines->count() : 0;

            // Total lignes HT
            $totalLinesHT = $invoice->lines ? $invoice->lines->sum('total_ligne_ht') : 0;

            // Nombre de bons de livraison
            $deliveryNotesCount = $invoice->deliveryNotes ? $invoice->deliveryNotes->count() : 0;

            // Nombre de retours
            $returnsCount = $invoice->salesReturns ? $invoice->salesReturns->count() : 0;

            return (object) [
                'Numéro Facture' => $invoice->numdoc ?? '-',
                'Date Facture' => $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-',
                'Client' => $customerText,
                'Véhicule' => $vehicleText,
                'Statut Facture' => ucfirst($invoice->status ?? '-'),
                'Payé' => $paymentStatus,
                'Type' => ucfirst($invoice->type ?? '-'),
                'Total HT' => number_format($invoice->total_ht ?? 0, 2, ',', ' '),
                'Total TTC' => number_format($invoice->total_ttc ?? 0, 2, ',', ' '),
                'TVA Rate' => number_format($invoice->tva_rate ?? 0, 2, ',', ' ') . '%',
                'Nb Lignes' => $linesCount,
                'Total Lignes HT' => number_format($totalLinesHT, 2, ',', ' '),
                'Bons Livraison' => $deliveryNotesCount,
                'Retours Vente' => $returnsCount,
                'Num Client' => $invoice->numclient ?? '-',
                'Créé le' => $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') : '-',
                'Mis à jour le' => $invoice->updated_at ? \Carbon\Carbon::parse($invoice->updated_at)->format('d/m/Y') : '-',
            ];
        });
    }

    /**
     * Calculer le solde restant pour une facture
     */
    private function getRemainingBalance($invoice)
    {
        if (!$invoice->paid) {
            return $invoice->total_ttc ?? 0;
        }

        // Logique pour calculer le solde restant (à adapter selon votre modèle)
        $totalPaid = 0;
        // Si vous avez une relation payments, vous pouvez faire :
        // $totalPaid = $invoice->payments->sum('amount') ?? 0;
        
        // Pour l'instant, on retourne 0 si payé
        return max(0, ($invoice->total_ttc ?? 0) - $totalPaid);
    }

    public function headings(): array
    {
        return [
            'Numéro Facture',
            'Date Facture',
            'Client',
            'Véhicule',
            'Statut Facture',
            'Payé',
            'Type',
            'Total HT (€)',
            'Total TTC (€)',
            'TVA (%)',
            'Nb Lignes',
            'Total Lignes HT (€)',
            'Bons Livraison',
            'Retours Vente',
            'Num Client',
            'Créé le',
            'Mis à jour le',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => '#,##0.00', // Total HT
            'I' => '#,##0.00', // Total TTC
            'J' => '#,##0.00', // TVA Rate
            'L' => '#,##0.00', // Total Lignes HT
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
            'A2:Q' . $lastRow => [
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
            
            'J2:J' . $lastRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            'L2:L' . $lastRow => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            
            // Colonne statut - mise en évidence
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
            EventsAfterSheet::class => function(EventsAfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $invoices = $this->getInvoicesForTotals();
                $lastRow = $invoices->count() + 1;
                
                // Calculer les totaux
                $totalHT = $invoices->sum(function ($invoice) {
                    return $invoice->total_ht ?? 0;
                });
                
                $totalTTC = $invoices->sum(function ($invoice) {
                    return $invoice->total_ttc ?? 0;
                });
                
                $totalLines = $invoices->sum(function ($invoice) {
                    return $invoice->lines ? $invoice->lines->count() : 0;
                });
                
                $totalDeliveryNotes = $invoices->sum(function ($invoice) {
                    return $invoice->deliveryNotes ? $invoice->deliveryNotes->count() : 0;
                });
                
                $totalReturns = $invoices->sum(function ($invoice) {
                    return $invoice->salesReturns ? $invoice->salesReturns->count() : 0;
                });
                
                // Ajouter ligne de total
                $sheet->insertNewRowBefore($lastRow + 1, 1);
                
                // Écrire les totaux
                $sheet->setCellValue('A' . ($lastRow + 1), 'TOTAL GÉNÉRAL');
                $sheet->setCellValue('H' . ($lastRow + 1), $totalHT);
                $sheet->setCellValue('I' . ($lastRow + 1), $totalTTC);
                $sheet->setCellValue('K' . ($lastRow + 1), $totalLines);
                $sheet->setCellValue('M' . ($lastRow + 1), $totalDeliveryNotes);
                $sheet->setCellValue('N' . ($lastRow + 1), $totalReturns);
                $sheet->setCellValue('P' . ($lastRow + 1), $invoices->count() . ' factures');
                
                // Style ligne total
                $event->sheet->getStyle('A' . ($lastRow + 1) . ':Q' . ($lastRow + 1))->applyFromArray([
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
                
                // Style spécifique pour les totaux monétaires
                $event->sheet->getStyle('H' . ($lastRow + 1) . ':I' . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FF0000'],
                    ],
                ]);
                
                // Auto-ajustement des largeurs
                foreach (range('A', 'Q') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Gel des premières lignes et colonnes
                $sheet->freezePane('A2');
                
                // Ajouter informations en bas
                $footerRow = $lastRow + 3;
                $sheet->insertNewRowBefore($footerRow, 2);
                
                $exportDate = \Carbon\Carbon::now()->format('d/m/Y à H:i');
                
                $event->sheet->getStyle('A' . $footerRow . ':Q' . ($footerRow + 1))->applyFromArray([
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
                $sheet->mergeCells('A' . $footerRow . ':Q' . $footerRow);
                $sheet->getRowDimension($footerRow)->setRowHeight(20);
                
                $sheet->setCellValue('A' . ($footerRow + 1), 'Nombre total de factures : ' . $invoices->count());
                $sheet->mergeCells('A' . ($footerRow + 1) . ':Q' . ($footerRow + 1));
                $sheet->getRowDimension($footerRow + 1)->setRowHeight(18);
                
                // Ajuster la hauteur des lignes
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
                
                // Colorer les lignes selon le statut de paiement
                $invoices->each(function ($invoice, $index) use ($event) {
                    $row = $index + 2;
                    $fillColor = 'F8FFF8'; // Vert par défaut
                    
                    if ($invoice->status === 'brouillon') {
                        $fillColor = 'FFF8F8'; // Rouge clair pour brouillon
                    } elseif (!$invoice->paid) {
                        $fillColor = 'FFFFF0'; // Jaune clair pour non payé
                    }
                    
                    $event->sheet->getStyle('A' . $row . ':Q' . $row)->applyFromArray([
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
     * Méthode helper pour récupérer les invoices pour les totaux
     */
    private function getInvoicesForTotals()
    {
        $query = Invoice::with(['lines', 'deliveryNotes', 'salesReturns']);

        // Réappliquer les mêmes filtres que dans collection()
        if ($this->request->filled('customer_id')) {
            $query->where('customer_id', $this->request->customer_id);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('paid')) {
            $query->where('paid', $this->request->paid === '1');
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $this->request->date_to);
        }

        return $query->get();
    }
}