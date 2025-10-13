<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = ['item_id', 'store_id', 'quantity', 'type', 'reference', 'note','cost_price','supplier_name'];

    public function item() { return $this->belongsTo(Item::class); }
    public function store() { return $this->belongsTo(Store::class); }
}
