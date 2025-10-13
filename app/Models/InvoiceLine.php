<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'delivery_note_id', 'sales_return_id', 'article_code',
        'quantity', 'unit_price_ht', 'remise', 'total_ligne_ht', 'total_ligne_ttc'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}