<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'numclient', 'numdoc', 'invoice_date', 'status', 'total_ht', 'total_ttc', 'tva_rate', 'notes', 'type', 'sales_order_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines()
    {
        return $this->hasMany(SalesInvoiceLine::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }


    
}