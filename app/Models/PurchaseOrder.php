<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'supplier_id', 'numdoc', 'order_date', 'status', 'type', 'total_ht', 'total_ttc', 'notes', 'tva_rate',
        'invoiced', 'status_livraison', 'acheteur', 'purchase_project_id', 'store_id', 'reception_status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }

    /** Toutes les réceptions de la commande (livraisons partielles possibles). */
    public function receptions()
    {
        return $this->hasMany(Reception::class, 'purchase_order_id');
    }

    /** Compatibilité avec les anciennes vues : la dernière réception. */
    public function reception()
    {
        return $this->hasOne(Reception::class, 'purchase_order_id')->latestOfMany();
    }

    public function returns()
    {
        return $this->hasMany(PurchaseReturn::class, 'purchase_order_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($PurchaseOrder) {
            if (Auth::check()) {
                $PurchaseOrder->acheteur = Auth::user()->name;
            }
        });

        static::updating(function ($PurchaseOrder) {
            if (Auth::check()) {
                $PurchaseOrder->acheteur = Auth::user()->name;
            }
        });
    }
}
