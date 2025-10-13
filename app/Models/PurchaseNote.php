<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'numdoc',
        'note_date',
        'status',
        'type',
        'total_ht',
        'total_ttc',
        'tva_rate',
        'notes',
        'paid',
        'purchase_return_id',
        'purchase_invoice_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseNoteLine::class);
    }



        public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }



    public function getRemainingBalanceAttribute()
    {
        // Calculate remaining balance: total_ttc - sum of payment amounts
        $paidAmount = $this->payments->sum('amount');
        // For purchase notes, total_ttc is typically negative (e.g., refund owed).
        // A negative payment (décaissement) reduces the liability.
        return round($this->total_ttc + $paidAmount, 2);
    }


    public function markAsPaid()
    {
        $this->update(['paid' => $this->getRemainingBalanceAttribute() <= 0.01]); // Add tolerance
    }

}