<?php

namespace App\Exports;


use App\Models\PurchaseProject;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchaseProjectExport implements FromArray, WithHeadings
{
    protected $project;

    public function __construct(PurchaseProject $project)
    {
        $this->project = $project;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->project->lines as $line) {
            $data[] = [
                'article_code' => $line->article_code,
                'ordered_quantity' => $line->ordered_quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'remise' => $line->remise,
            ];
        }
        return $data;
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