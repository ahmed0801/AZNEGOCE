<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesInvoiceExport implements FromArray, WithHeadings
{
    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function array(): array
    {
        return $this->invoice->lines->map(function ($line) {
            return [
                'Article' => $line->item->name?? $line->description?? $line->article_code,
                'Quantité' => $line->quantity,
                'Prix Unitaire HT' => $line->unit_price_ht,
                'Remise (%)' => $line->remise?? 0,
                'Total HT' => $line->total_ligne_ht,
                'Total TTC' => $line->total_ligne_ttc,
            ];
})->toArray(); // 👈 important: convertir en tableau
}

    public function headings(): array
    {
        return [
            'Article',
            'Quantité',
            'Prix Unitaire HT',
            'Remise (%)',
            'Total HT',
            'Total TTC',
        ];
    }
}