<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $fillable = ['label', 'days'];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}