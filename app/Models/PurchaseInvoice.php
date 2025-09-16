<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'supplier_id', 'numdoc', 'invoice_date', 'status','paid', 'total_ht', 'total_ttc',
        'tva_rate', 'notes', 'type','supplier_invoice_file'
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
        // Calculate remaining balance: total_ttc - sum of payment amounts
        $paidAmount = $this->payments->sum('amount');
        // For purchase invoices, total_ttc can be negative (e.g., credit invoice).
        // A negative payment (décaissement) reduces the liability.
        return round($this->total_ttc + $paidAmount, 2);
    }

    public function markAsPaid()
    {
        $this->update(['paid' => $this->getRemainingBalanceAttribute() <= 0.01]); // Add tolerance
    }

}