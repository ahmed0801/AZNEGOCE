<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'supplier_id', 'numdoc', 'invoice_date', 'due_date', 'status', 'paid', 'total_ht', 'total_ttc', 'acheteur',
        'tva_rate', 'notes', 'type', 'supplier_invoice_file', 'supplier_ref',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseInvoiceLine::class);
    }

    public function orders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'purchase_invoice_lines', 'purchase_invoice_id', 'purchase_order_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function getRemainingBalanceAttribute()
    {
        // Reste = total_ttc + somme des règlements (les décaissements sont stockés en négatif)
        $paidAmount = $this->payments->sum('amount');
        return round($this->total_ttc + $paidAmount, 2);
    }

    public function markAsPaid()
    {
        $this->update(['paid' => $this->getRemainingBalanceAttribute() <= 0.01]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($Purchase_invoice) {
            if (Auth::check()) {
                $Purchase_invoice->acheteur = Auth::user()->name;
            }
        });

        static::updating(function ($Purchase_invoice) {
            if (Auth::check()) {
                $Purchase_invoice->acheteur = Auth::user()->name;
            }
        });
    }
}
