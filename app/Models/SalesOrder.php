<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'numclient', 'order_date', 'status', 'total_ht', 'total_ttc', 'notes', 'numdoc', 'tva_rate', 'store_id', 'invoiced','vendeur',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines()
    {
        return $this->hasMany(SalesOrderLine::class);
    }

    public function deliveryNote()
    {
        return $this->hasOne(DeliveryNote::class);
    }

    public function invoice()
    {
        return $this->hasOne(SalesInvoice::class);
    }


        protected static function boot()
    {
        parent::boot();

        static::creating(function ($salesOrder) {
            if (Auth::check()) {
                $salesOrder->vendeur = Auth::user()->name;
            }
        });

        static::updating(function ($salesOrder) {
            if (Auth::check()) {
                $salesOrder->vendeur = Auth::user()->name;
            }
        });
    }

    
}