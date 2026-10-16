<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceptionLine extends Model
{
    protected $fillable = [
        'reception_id', 'purchase_order_line_id', 'article_code', 'received_quantity',
        'unit_price_ht', 'remise', 'tva', 'total_ligne_ht',
    ];

    public function reception()
    {
        return $this->belongsTo(Reception::class);
    }

    public function orderLine()
    {
        return $this->belongsTo(PurchaseOrderLine::class, 'purchase_order_line_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}
