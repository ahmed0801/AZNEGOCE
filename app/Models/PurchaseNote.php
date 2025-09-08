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
}