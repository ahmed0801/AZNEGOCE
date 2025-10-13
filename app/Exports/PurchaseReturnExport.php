<?php

namespace App\Exports;

use App\Models\PurchaseReturn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchaseReturnExport implements FromCollection, WithHeadings
{
    protected $return;

    public function __construct(PurchaseReturn $return)
    {
        $this->return = $return;
    }

public function collection(): Collection
    {
        return $this->return->lines->map(function ($line) {
            return (object)[ // 👈 conversion en objet pour éviter method_exists sur array
                'numdoc' => $this->return->numdoc,
                'commande' => $this->return->purchaseOrder->numdoc,
                'fournisseur' => $this->return->purchaseOrder->supplier->name,
                'date' => \Carbon\Carbon::parse($this->return->return_date)->format('d/m/Y'),
                'type' => ucfirst($this->return->type),
                'article_code' => $line->article_code,
                'designation' => $line->item->name?? '-',
                'quantite' => $line->returned_quantity,
                'pu_ht' => number_format($line->unit_price_ht, 2),
                'remise' => $line->remise,
                'total_ligne_ht' => number_format($line->total_ligne_ht, 2),
            ];
});
}

    public function headings(): array
    {
        return [
            'N° Retour',
            'Commande',
            'Fournisseur',
            'Date',
            'Type',
            'Code Article',
            'Désignation',
            'Qté Retournée',
            'PU HT (€)',
            'Remise (%)',
            'Total Ligne HT (€)',
        ];
    }
}