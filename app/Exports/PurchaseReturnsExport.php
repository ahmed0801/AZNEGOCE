<?php

namespace App\Exports;

use App\Models\PurchaseReturn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseReturnsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = PurchaseReturn::with(['purchaseOrder.supplier', 'lines.item']);

        if ($this->request->filled('supplier_id')) {
            $query->whereHas('purchaseOrder', function ($q) {
                $q->where('supplier_id', $this->request->supplier_id);
            });
        }

        if ($this->request->filled('purchase_order_id')) {
            $query->where('purchase_order_id', $this->request->purchase_order_id);
        }

        if ($this->request->filled('type')) {
            $query->where('type', $this->request->type);
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $this->request->date_to);
        }

        return $query->get()->map(function ($return) {
            return [
                'numdoc' => $return->numdoc,
                'commande' => $return->purchaseOrder->numdoc,
                'fournisseur' => $return->purchaseOrder->supplier->name,
                'date' => \Carbon\Carbon::parse($return->return_date)->format('d/m/Y'),
                'type' => ucfirst($return->type),
                'total_ht' => number_format($return->total_ht, 2),
                'total_ttc' => number_format($return->total_ttc, 2),
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
            'Total HT (€)',
            'Total TTC (€)',
        ];
    }
}