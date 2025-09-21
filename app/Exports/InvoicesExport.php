<?php


namespace App\Exports;

use App\Models\PurchaseInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class InvoicesExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $query = PurchaseInvoice::with('supplier');

        // Appliquer les filtres si fournis
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
        }
        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('invoice_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('invoice_date', '<=', $this->filters['date_to']);
        }

        return $query->get()->map(function ($invoice) {
            return [
                'Numéro' => $invoice->numdoc,
                'Fournisseur' => $invoice->supplier->name ?? '',
                'Date' => $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '',
                'Statut' => ucfirst($invoice->status),
                'Type' => ucfirst($invoice->type),
                'Total HT' => number_format($invoice->total_ht, 2) . ' €',
                'Total TTC' => number_format($invoice->total_ttc, 2) . ' €',
            ];
        });
    }

    public function headings(): array
    {
        return ['Numéro', 'Fournisseur', 'Date', 'Statut', 'Type', 'Total HT', 'Total TTC'];
    }
}