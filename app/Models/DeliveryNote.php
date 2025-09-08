<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;

protected $fillable = [
    'sales_order_id', 'numclient', 'delivery_date', 'status', 'total_delivered', 
    'total_ht', 'total_ttc', 'tva_rate', 'notes', 'numdoc'
];

public function customer()
{
    return $this->belongsTo(Customer::class, 'numclient', 'code');
}
    

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    public function lines()
    {
        return $this->hasMany(DeliveryNoteLine::class);
    }
}