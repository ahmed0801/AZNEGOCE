<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNoteLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id', 'article_code', 'delivered_quantity', 'unit_price_ht', 'unit_price_ttc', 'remise', 'total_ligne_ht', 'total_ligne_ttc'
    ];

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}