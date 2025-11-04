<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SalesReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id', 'numdoc', 'return_date', 'type', 'total_ht', 'total_ttc',
        'tva_rate', 'notes', 'customer_id', 'invoiced','vendeur'
    ];

    protected $casts = [
        'return_date' => 'datetime',
        'invoiced' => 'boolean',
    ];

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function lines()
    {
        return $this->hasMany(SalesReturnLine::class);
    }


    // Dans SalesReturn.php
protected static function boot()
{
    parent::boot();

    static::creating(function ($return) {
        if (Auth::check()) {
            $return->vendeur = Auth::user()->name;
        }
    });

    static::updating(function ($return) {
        if (Auth::check()) {
            $return->vendeur = Auth::user()->name;
        }
    });
}


}