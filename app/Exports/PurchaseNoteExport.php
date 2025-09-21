<?php


namespace App\Exports;

use App\Models\PurchaseNote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchaseNoteExport implements FromCollection, WithHeadings
{
    protected $note;

    public function __construct(PurchaseNote $note)
    {
        $this->note = $note;
    }

    public function collection(): Collection
    {
        $data = collect();

        $data->push([
            'Type' => 'Avoir',
            'Numéro' => $this->note->numdoc,
            'Fournisseur' => $this->note->supplier->name ?? '-',
            'Date Avoir' => \Carbon\Carbon::parse($this->note->note_date)->format('d/m/Y'),
            'Statut' => ucfirst($this->note->status),
            'Type Avoir' => ucfirst(str_replace('_', ' ', $this->note->type)),
            'Retour Lié' => $this->note->purchaseReturn ? $this->note->purchaseReturn->numdoc : '-',
            'Facture Liée' => $this->note->purchaseInvoice ? $this->note->purchaseInvoice->numdoc : '-',
            'Total HT' => number_format($this->note->total_ht, 2) . ' €',
            'Total TTC' => number_format($this->note->total_ttc, 2) . ' €',
            'Code Article' => '',
            'Désignation' => '',
            'Quantité' => '',
            'PU HT' => '',
            'Remise (%)' => '',
            'TVA (%)' => '',
            'Total Ligne' => '',
        ]);

        $data->push([
            'Type' => '',
            'Numéro' => '',
            'Fournisseur' => '',
            'Date Avoir' => '',
            'Statut' => '',
            'Type Avoir' => '',
            'Retour Lié' => '',
            'Facture Liée' => '',
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

        foreach ($this->note->lines as $line) {
            $data->push([
                'Type' => 'Ligne',
                'Numéro' => '',
                'Fournisseur' => '',
                'Date Avoir' => '',
                'Statut' => '',
                'Type Avoir' => '',
                'Retour Lié' => '',
                'Facture Liée' => '',
                'Total HT' => '',
                'Total TTC' => '',
                'Code Article' => $line->article_code ?? '-',
                'Désignation' => $line->item->name ?? $line->description ?? '-',
                'Quantité' => $line->quantity,
                'PU HT' => number_format($line->unit_price_ht, 2) . ' €',
                'Remise (%)' => $line->remise . '%',
                'TVA (%)' => number_format($line->tva, 2) . '%',
                'Total Ligne' => number_format($line->total_ligne_ht, 2) . ' €',
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
            'Date Avoir',
            'Statut',
            'Type Avoir',
            'Retour Lié',
            'Facture Liée',
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