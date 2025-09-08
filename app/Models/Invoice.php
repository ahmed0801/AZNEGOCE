<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'numdoc', 'type', 'numclient', 'customer_id', 'invoice_date', 'due_date', 'status', 'paid',
        'total_ht', 'total_ttc', 'tva_rate', 'notes'
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'paid' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function deliveryNotes()
    {
        return $this->belongsToMany(DeliveryNote::class, 'invoice_delivery_notes')
                    ->withPivot('sales_return_id');
    }

    public function salesReturns()
    {
        return $this->belongsToMany(SalesReturn::class, 'invoice_delivery_notes')
                    ->withPivot('delivery_note_id');
    }
}