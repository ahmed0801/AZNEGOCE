<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'supplier_id', 'numdoc', 'invoice_date', 'status', 'total_ht', 'total_ttc',
        'tva_rate', 'notes', 'type','supplier_invoice_file'
    ];

public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseInvoiceLine::class);
    }

    public function orders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'purchase_invoice_lines', 'purchase_invoice_id', 'purchase_order_id');
    }
}