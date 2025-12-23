<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'article_code',
        'ordered_quantity',
        'unit_price_ht',
        'unit_price_ttc',
        'remise',
        'total_ligne_ht',
        'total_ligne_ttc', // Add this
        // Nouveaux
        'supplier_id',
        'unit_coast',
        'discount_coast',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}