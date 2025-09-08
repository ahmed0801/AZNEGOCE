<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'code','name', 'email', 'phone1', 'phone2', 'address', 'address_delivery', 'city', 'country',
        'solde', 'plafond', 'risque','matfiscal','bank_no','tva_group_id', 'discount_group_id','payment_mode_id','payment_term_id','blocked'
    ];

    public function discountGroup()
    {
        return $this->belongsTo(DiscountGroup::class);
    }
    public function paymentMode()
{
    return $this->belongsTo(PaymentMode::class);
}

public function paymentTerm()
{
    return $this->belongsTo(PaymentTerm::class);
}

    public function tvaGroup()
    {
        return $this->belongsTo(TvaGroup::class, 'tva_group_id');
    }
    

     public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    

}
