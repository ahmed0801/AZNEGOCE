<?php

namespace App\Exports;

use App\Models\SalesReturn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SalesReturnExport implements FromCollection, WithHeadings
{
    protected $return;

    public function __construct(SalesReturn $return)
    {
        $this->return = $return;
    }

    public function collection(): Collection
    {
        return $this->return->lines->map(function ($line) {
            return [
                'numdoc' => $this->return->numdoc,
                'delivery_note' => $this->return->deliveryNote->numdoc ?? '-',
                'customer' => $this->return->customer->name ?? '-',
                'return_date' => \Carbon\Carbon::parse($this->return->return_date)->format('d/m/Y'),
                'type' => ucfirst($this->return->type),
                'article_code' => $line->article_code,
                'article_name' => $line->item->name ?? '-',
                'returned_quantity' => $line->returned_quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'remise' => $line->remise,
                'total_ligne_ht' => $line->total_ligne_ht,
                'total_ht' => $this->return->total_ht,
                'total_ttc' => $this->return->total_ttc,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'N° Retour',
            'Bon de Livraison',
            'Client',
            'Date Retour',
            'Type',
            'Code Article',
            'Désignation',
            'Qté Retournée',
            'PU HT',
            'Remise (%)',
            'Total Ligne HT',
            'Total HT',
            'Total TTC',
        ];
    }
}
