<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLine extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'article_code',
        'ordered_quantity',
        'received_quantity',
        'unit_price_ht',
        'remise',
        'tva',
        'total_ligne_ht'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }

    /** Reste à recevoir (jamais négatif). */
    public function getRemainingQuantityAttribute()
    {
        return max(0, (float) $this->ordered_quantity - (float) $this->received_quantity);
    }
}
