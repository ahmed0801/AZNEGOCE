<?php

namespace App\Exports;

use App\Models\Purchase;
use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchaseExport implements FromCollection, WithHeadings
{
    protected $purchase;

    public function __construct(PurchaseOrder $purchase)
    {
        $this->purchase = $purchase;
    }

    public function collection(): Collection
    {
        $data = collect();

        // Informations de la commande
        $data->push((object)[
            'Type' => 'Commande',
            'Numéro' => $this->purchase->numdoc,
            'Fournisseur' => $this->purchase->supplier->name?? '',
            'Date Commande' => \Carbon\Carbon::parse($this->purchase->order_date)->format('d/m/Y'),
            'Statut' => ucfirst($this->purchase->status),
            'Statut Réception' => $this->purchase->reception? ucfirst($this->purchase->reception->status): 'Aucune réception',
            'Total HT' => number_format($this->purchase->total_ht, 2). ' €',
            'Total TTC' => number_format($this->purchase->total_ttc, 2). ' €',
            'Code Article' => '',
            'Désignation' => '',
            'Quantité' => '',
            'PU HT' => '',
            'Remise (%)' => '',
            'Total Ligne' => '',
        ]);

        // Ligne vide
        $data->push((object)[
            'Type' => '',
            'Numéro' => '',
            'Fournisseur' => '',
            'Date Commande' => '',
            'Statut' => '',
            'Statut Réception' => '',
            'Total HT' => '',
            'Total TTC' => '',
            'Code Article' => '',
            'Désignation' => '',
            'Quantité' => '',
            'PU HT' => '',
            'Remise (%)' => '',
            'Total Ligne' => '',
        ]);

        // Lignes de la commande
        foreach ($this->purchase->lines as $line) {
            $data->push((object)[
                'Type' => 'Ligne',
                'Numéro' => '',
                'Fournisseur' => '',
                'Date Commande' => '',
                'Statut' => '',
                'Statut Réception' => '',
                'Total HT' => '',
                'Total TTC' => '',
                'Code Article' => $line->article_code,
                'Désignation' => $line->item->name?? '-',
                'Quantité' => $line->ordered_quantity,
                'PU HT' => number_format($line->unit_price_ht, 2). ' €',
                'Remise (%)' => $line->remise. '%',
                'Total Ligne' => number_format($line->total_ligne_ht, 2). ' €',
            ]);
}

        return $data;
}

    public function headings(): array
    {
        return [
            'Type', 'Numéro', 'Fournisseur', 'Date Commande', 'Statut', 'Statut Réception',
            'Total HT', 'Total TTC', 'Code Article', 'Désignation', 'Quantité', 'PU HT',
            'Remise (%)', 'Total Ligne',
        ];
    }
}