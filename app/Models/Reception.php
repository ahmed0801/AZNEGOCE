<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    protected $fillable = [
        'numdoc', 'purchase_order_id', 'supplier_id', 'store_id', 'reception_date', 'status',
        'total_received', 'supplier_ref', 'invoiced', 'stock_applied', 'notes',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function lines()
    {
        return $this->hasMany(ReceptionLine::class);
    }
}
