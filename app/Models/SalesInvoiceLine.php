<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_invoice_id', 'article_code', 'sales_order_id', 'quantity', 'unit_price_ht', 'unit_price_ttc', 'remise', 'total_ligne_ht', 'total_ligne_ttc', 'tva', 'description'
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}