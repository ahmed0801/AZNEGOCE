<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_return_id',
        'article_code',
        'returned_quantity',
        'unit_price_ht',
        'remise',
        'total_ligne_ht',
    ];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}