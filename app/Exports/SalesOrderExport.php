<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class SalesOrderExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $order;

    public function __construct(SalesOrder $order)
    {
        $this->order = $order;
    }

    public function collection()
    {
        $data = collect();

        // Calculate TVA
        $totalTva = $this->order->total_ttc - $this->order->total_ht;

        // Order details
        $data->push([
            'Type' => 'Commande',
            'Numéro' => $this->order->numdoc,
            'N° Client' => $this->order->customer->code ?? '-',
            'Client' => $this->order->customer->name ?? '-',
            'Date Commande' => \Carbon\Carbon::parse($this->order->order_date)->format('d/m/Y'),
            'Statut' => ucfirst($this->order->status),
            'Statut BL' => $this->order->deliveryNote ? ucfirst($this->order->deliveryNote->status) : 'Aucun BL',
            'Total HT' => number_format($this->order->total_ht, 2) . ' €',
            'Total TVA' => number_format($totalTva, 2) . ' €',
            'Total TTC' => number_format($this->order->total_ttc, 2) . ' €',
            'Code Article' => '',
            'Désignation' => '',
            'Quantité' => '',
            'PU HT' => '',
            'Remise (%)' => '',
            'Total Ligne' => '',
        ]);

        // Blank row for separation
        $data->push([
            'Type' => '',
            'Numéro' => '',
            'N° Client' => '',
            'Client' => '',
            'Date Commande' => '',
            'Statut' => '',
            'Statut BL' => '',
            'Total HT' => '',
            'Total TVA' => '',
            'Total TTC' => '',
            'Code Article' => '',
            'Désignation' => '',
            'Quantité' => '',
            'PU HT' => '',
            'Remise (%)' => '',
            'Total Ligne' => '',
        ]);

        // Order lines
        foreach ($this->order->lines as $line) {
            $data->push([
                'Type' => 'Ligne',
                'Numéro' => '',
                'N° Client' => '',
                'Client' => '',
                'Date Commande' => '',
                'Statut' => '',
                'Statut BL' => '',
                'Total HT' => '',
                'Total TVA' => '',
                'Total TTC' => '',
                'Code Article' => $line->article_code,
                'Désignation' => $line->item->name ?? '-',
                'Quantité' => $line->ordered_quantity,
                'PU HT' => number_format($line->unit_price_ht, 2) . ' €',
                'Remise (%)' => $line->remise . '%',
                'Total Ligne' => number_format($line->total_ligne_ht, 2) . ' €',
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Numéro',
            'N° Client',
            'Client',
            'Date Commande',
            'Statut',
            'Statut BL',
            'Total HT',
            'Total TVA',
            'Total TTC',
            'Code Article',
            'Désignation',
            'Quantité',
            'PU HT',
            'Remise (%)',
            'Total Ligne',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row
            1 => [
                'font' => ['bold' => true],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
            // Order details
            2 => [
                'font' => ['bold' => true],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
            // Lines data
            '3:' . (3 + count($this->order->lines)) => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
        ];
    }
}