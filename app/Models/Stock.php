<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['item_id', 'store_id', 'quantity', 'min_quantity', 'max_quantity'];

    public function item() { return $this->belongsTo(Item::class); }
    public function store() { return $this->belongsTo(Store::class); }
}
