<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'numdoc',
        'return_date',
        'type',
        'total_ht',
        'total_ttc',
        'notes',
        'tva_rate',
        'supplier_id',
        'invoiced',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }


       public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }



    public function lines()
    {
        return $this->hasMany(PurchaseReturnLine::class);
    }
}