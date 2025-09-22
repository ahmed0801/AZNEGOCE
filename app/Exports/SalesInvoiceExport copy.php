<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SalesInvoiceExport implements FromCollection, WithHeadings
{
    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

public function collection(): Collection
{
    return collect($this->invoice->lines)->map(function ($line) {
        return (object)[
            'Article' => $line->item->name?? $line->description?? $line->article_code,
            'Quantité' => $line->quantity,
            'Prix Unitaire HT' => $line->unit_price_ht,
            'Remise (%)' => $line->remise?? 0,
            'Total HT' => $line->total_ligne_ht,
            'Total TTC' => $line->total_ligne_ttc,
        ];
});
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