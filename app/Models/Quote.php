<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'numclient', 'quote_date', 'status', 'total_ht', 'total_ttc', 'tva_rate', 'notes', 'numdoc'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines()
    {
        return $this->hasMany(QuoteLine::class);
    }
}