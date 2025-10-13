<?php
namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchasesExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

      public function collection(): Collection
    {
        $query = PurchaseOrder::with('supplier');

        // Appliquer les filtres
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
}

        if (!empty($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
}

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('order_date', '>=', $this->filters['date_from']);
}

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('order_date', '<=', $this->filters['date_to']);
}

        return $query->get()->map(function ($purchase) {
            return (object)[
                'Numéro' => $purchase->numdoc,
                'Fournisseur' => $purchase->supplier->name?? '-',
                'Date' => $purchase->order_date
? \Carbon\Carbon::parse($purchase->order_date)->format('d/m/Y')
: '-',
                'Statut' => ucfirst($purchase->status),
                'Total HT' => number_format($purchase->total_ht, 2, ',', ' '). ' €',
                'Total TTC' => number_format($purchase->total_ttc, 2, ',', ' '). ' €',
            ];
});
}

    public function headings(): array
    {
        return ['Numéro', 'Fournisseur', 'Date', 'Statut', 'Total HT', 'Total TTC'];
    }
}