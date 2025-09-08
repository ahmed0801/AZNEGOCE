<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesNote extends Model
{
    protected $fillable = [
        'numdoc',
        'customer_id',
        'note_date',
        'due_date',
        'status',
        'paid',
        'total_ht',
        'total_ttc',
        'tva_rate',
        'notes',
        'type',
        'sales_invoice_id',
        'sales_return_id',
    ];

    protected $casts = [
        'note_date' => 'date',
        'due_date' => 'date',
        'paid' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesInvoice()
    {
        return $this->belongsTo(Invoice::class, 'sales_invoice_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function lines()
    {
        return $this->hasMany(SalesNoteLine::class);
    }
}
