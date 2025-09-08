<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['supplier_id','numdoc', 'order_date', 'status', 'total_ht', 'total_ttc', 'notes','tva_rate','invoiced','status_livraison'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }

public function reception() {
    return $this->hasOne(Reception::class, 'purchase_order_id');
}

    public function returns()
    {
        return $this->hasMany(PurchaseReturn::class, 'purchase_order_id');
    }

}

