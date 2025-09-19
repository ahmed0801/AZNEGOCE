<?php

namespace App\Exports;

use App\Models\DeliveryNote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DeliveryNoteExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $deliveryNote;

    public function __construct(DeliveryNote $deliveryNote)
    {
        // Charger les relations
        $this->deliveryNote = $deliveryNote->loadMissing(['lines.item', 'salesOrder']);
    }

    public function collection()
    {
        $data = collect();

        $totalTva = $this->deliveryNote->total_ttc - $this->deliveryNote->total_ht;

        // En-tête bon de livraison
        $data->push([
            'Type' => 'Bon de Livraison',
            'Numéro' => $this->deliveryNote->numdoc,
            'N° Client' => $this->deliveryNote->numclient ?? '-',
            'Client' => $this->deliveryNote->customer_name ?? '-',
            'Date Livraison' => $this->deliveryNote->delivery_date ? $this->deliveryNote->delivery_date->format('d/m/Y') : '-',
            'Statut' => ucfirst($this->deliveryNote->status ?? '-'),
            'N° Commande' => $this->deliveryNote->salesOrder->numdoc ?? '-',
            'Total HT' => number_format($this->deliveryNote->total_ht, 2, ',', ' ') . ' €',
            'Total TVA' => number_format($totalTva, 2, ',', ' ') . ' €',
            'Total TTC' => number_format($this->deliveryNote->total_ttc, 2, ',', ' ') . ' €',
            'Code Article' => '',
            'Désignation' => '',
            'Quantité' => '',
            'PU HT' => '',
            'Remise (%)' => '',
            'Total Ligne' => '',
        ]);

        // Ligne vide
        $data->push(array_fill_keys(array_keys($data->first()), ''));

        // Lignes du bon de livraison
        foreach ($this->deliveryNote->lines as $line) {
            $data->push([
                'Type' => 'Ligne',
                'Numéro' => '',
                'N° Client' => '',
                'Client' => '',
                'Date Livraison' => '',
                'Statut' => '',
                'N° Commande' => '',
                'Total HT' => '',
                'Total TVA' => '',
                'Total TTC' => '',
                'Code Article' => $line->article_code,
                'Désignation' => $line->item ? $line->item->name : '-',
                'Quantité' => $line->delivered_quantity,
                'PU HT' => number_format($line->unit_price_ht, 2, ',', ' ') . ' €',
                'Remise (%)' => $line->remise . '%',
                'Total Ligne' => number_format($line->total_ligne_ht, 2, ',', ' ') . ' €',
            ]);
        }

        // **Convertir tout en array simple** pour éviter method_exists sur un array
        return $data->map(function($row) {
            return collect($row)->map(function($value) {
                return is_object($value) ? (string) $value : $value;
            })->toArray();
        });
    }

    public function headings(): array
    {
        return [
            'Type',
            'Numéro',
            'N° Client',
            'Client',
            'Date Livraison',
            'Statut',
            'N° Commande',
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
            1 => ['font' => ['bold' => true], 'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]],
            2 => ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]],
            '3:' . (2 + count($this->deliveryNote->lines)) => ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]],
        ];
    }
}
