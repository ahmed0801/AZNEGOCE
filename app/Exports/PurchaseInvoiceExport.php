<?php


namespace App\Exports;

use App\Models\PurchaseInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchaseInvoiceExport implements FromCollection, WithHeadings
{
    protected $invoice;

    public function __construct(PurchaseInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

   public function collection(): Collection
{
    $data = collect();

    $data->push((object)[
        'Type' => 'Facture',
        'Numéro' => $this->invoice->numdoc,
        'Fournisseur' => $this->invoice->supplier->name?? '',
        'Date Facture' => \Carbon\Carbon::parse($this->invoice->invoice_date)->format('d/m/Y'),
        'Statut' => ucfirst($this->invoice->status),
        'Type Facture' => ucfirst($this->invoice->type),
        'Total HT' => number_format($this->invoice->total_ht, 2). ' €',
        'Total TTC' => number_format($this->invoice->total_ttc, 2). ' €',
        'Code Article' => '',
        'Désignation' => '',
        'Quantité' => '',
        'PU HT' => '',
        'Remise (%)' => '',
        'TVA (%)' => '',
        'Total Ligne' => '',
    ]);

    $data->push((object)[
        'Type' => '',
        'Numéro' => '',
        'Fournisseur' => '',
        'Date Facture' => '',
        'Statut' => '',
        'Type Facture' => '',
        'Total HT' => '',
        'Total TTC' => '',
        'Code Article' => '',
        'Désignation' => '',
        'Quantité' => '',
        'PU HT' => '',
        'Remise (%)' => '',
        'TVA (%)' => '',
        'Total Ligne' => '',
    ]);

    foreach ($this->invoice->lines as $line) {
        $data->push((object)[
            'Type' => 'Ligne',
            'Numéro' => '',
            'Fournisseur' => '',
            'Date Facture' => '',
            'Statut' => '',
            'Type Facture' => '',
            'Total HT' => '',
            'Total TTC' => '',
            'Code Article' => $line->article_code?? '-',
            'Désignation' => $line->item->name?? $line->description?? '-',
            'Quantité' => $line->quantity,
            'PU HT' => number_format($line->unit_price_ht, 2). ' €',
            'Remise (%)' => $line->remise. '%',
            'TVA (%)' => number_format($line->tva, 2). '%',
            'Total Ligne' => number_format($line->total_ligne_ht, 2). ' €',
        ]);
}

    return $data;
}

    public function headings(): array
    {
        return [
            'Type',
            'Numéro',
            'Fournisseur',
            'Date Facture',
            'Statut',
            'Type Facture',
            'Total HT',
            'Total TTC',
            'Code Article',
            'Désignation',
            'Quantité',
            'PU HT',
            'Remise (%)',
            'TVA (%)',
            'Total Ligne',
        ];
    }
}