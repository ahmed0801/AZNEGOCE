<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceLine extends Model
{
    protected $fillable = [
        'purchase_invoice_id', 'article_code', 'purchase_order_id', 'purchase_return_id', 'reception_id', 'quantity',
        'unit_price_ht', 'remise', 'total_ligne_ht', 'tva', 'prix_ttc', 'description'
    ];

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function reception()
    {
        return $this->belongsTo(Reception::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}
