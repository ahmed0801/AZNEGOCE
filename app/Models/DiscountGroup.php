<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountGroup extends Model
{
    protected $fillable = ['name', 'discount_rate','discount_rate_jobber',
        'discount_rate_professionnel'];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
