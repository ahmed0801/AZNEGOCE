<?php


namespace App\Exports;

use App\Models\PurchaseNote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchaseNotesExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

     public function collection(): Collection
    {
        $query = PurchaseNote::with(['supplier', 'purchaseReturn', 'purchaseInvoice']);

        if (!empty($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
}
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
}
        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
}
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('note_date', '>=', $this->filters['date_from']);
}
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('note_date', '<=', $this->filters['date_to']);
}

        return $query->get()->map(function ($note) {
            return (object)[ // 👈 conversion en objet
                'Numéro' => $note->numdoc,
                'Fournisseur' => $note->supplier->name?? '-',
                'Date' => $note->note_date? \Carbon\Carbon::parse($note->note_date)->format('d/m/Y'): '-',
                'Statut' => ucfirst($note->status),
                'Type' => ucfirst(str_replace('_', ' ', $note->type)),
                'Retour Lié' => $note->purchaseReturn? $note->purchaseReturn->numdoc: '-',
                'Facture Liée' => $note->purchaseInvoice? $note->purchaseInvoice->numdoc: '-',
                'Total HT' => number_format($note->total_ht, 2). ' €',
                'Total TTC' => number_format($note->total_ttc, 2). ' €',
            ];
});
}

    public function headings(): array
    {
        return [
            'Numéro',
            'Fournisseur',
            'Date',
            'Statut',
            'Type',
            'Retour Lié',
            'Facture Liée',
            'Total HT',
            'Total TTC',
        ];
    }
}