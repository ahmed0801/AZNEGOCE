<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'code','name', 'email', 'phone1', 'phone2', 'address', 'address_delivery', 'city', 'country',
        'balance', 'credit_limit', 'credit_risk','matfiscal','bank_no','tva_group_id', 'discount_group_id','blocked','risque','planfond','has_b2b', 'b2b_url'  // ← AJOUTÉ
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

public function items()
{
    return $this->hasMany(Item::class, 'codefournisseur', 'code');
}

    public function tvaGroup()
{
    return $this->belongsTo(TvaGroup::class, 'tva_group_id');
}

}
