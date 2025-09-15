<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
 use HasFactory;

    protected $fillable = [
        'payable_id',
        'payable_type',
        'customer_id',
        'supplier_id',
        'amount',
        'payment_date',
        'payment_mode',
        'reference',
        'lettrage_code',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function generateLettrageCode()
    {
        $invoice = $this->payable;
        $prefix = $invoice instanceof Invoice ? 'CL' : 'FR';
        return $prefix . '-' . $invoice->numdoc . '-' . $this->payment_date->format('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}