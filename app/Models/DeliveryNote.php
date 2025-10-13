<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DeliveryNote extends Model
{
    use HasFactory;

protected $fillable = [
    'sales_order_id', 'numclient', 'vehicle_id', 'delivery_date', 'status', 'total_delivered', 
    'total_ht', 'total_ttc', 'tva_rate', 'notes', 'numdoc','status_livraison','invoiced', 'vendeur',
];

public function customer()
{
    return $this->belongsTo(Customer::class, 'numclient', 'code');
}
    
    protected $casts = [
        'delivery_date' => 'datetime', // Cast delivery_date to Carbon
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    public function lines()
    {
        return $this->hasMany(DeliveryNoteLine::class);
    }

    // Add this relationship
    public function salesReturns()
    {
        return $this->hasMany(SalesReturn::class, 'delivery_note_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deliveryNote) {
            if (Auth::check()) {
                $deliveryNote->vendeur = Auth::user()->name;
            }
        });

        static::updating(function ($deliveryNote) {
            if (Auth::check()) {
                $deliveryNote->vendeur = Auth::user()->name;
            }
        });
    }
    
}