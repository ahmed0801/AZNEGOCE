<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'brand_id',
        'brand_name',
        'model_id',
        'model_name',
        'engine_id',
        'engine_description',
        'linkage_target_id',
        'license_plate',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
