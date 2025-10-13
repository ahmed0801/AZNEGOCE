<?php

namespace App\Exports;


use App\Models\PurchaseProject;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseProjectExport implements FromCollection, WithHeadings
{
    protected $project;

    public function __construct(PurchaseProject $project)
    {
        $this->project = $project;
}

    public function collection(): Collection
    {
        return collect($this->project->lines)->map(function ($line) {
            return (object)[
                'article_code' => $line->article_code,
                'ordered_quantity' => $line->ordered_quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'remise' => $line->remise,
            ];
});
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