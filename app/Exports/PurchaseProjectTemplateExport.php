<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;



class PurchaseProjectTemplateExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        // 👇 Une ligne vide convertie en objet pour éviter les erreurs PHP 8+
        return collect([
            (object)[
                'article_code' => '',
                'ordered_quantity' => '',
                'unit_price_ht' => '',
                'remise' => ''
            ]
        ]);
}

    public function headings(): array
    {
        return [
            'article_code',
            'ordered_quantity',
            'unit_price_ht',
            'remise'
        ];
}
}
