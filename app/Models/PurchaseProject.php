<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProject extends Model
{
    protected $fillable = [
        'numdoc', 'supplier_id', 'order_date', 'notes', 'total_ht', 'total_ttc', 'status', 'tva_rate'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseProjectLine::class);
    }
}
