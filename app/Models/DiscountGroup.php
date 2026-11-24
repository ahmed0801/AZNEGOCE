<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountGroup extends Model
{
    protected $fillable = ['name', 'discount_rate','discount_rate_jobber',
        'discount_rate_professionnel', 'type', 'price_tier', 'price_mode'];

    const TYPE_ARTICLE = 'article';
    const TYPE_CLIENT  = 'client';

    public function scopeForArticles($q) { return $q->where('type', self::TYPE_ARTICLE); }
    public function scopeForClients($q)  { return $q->where('type', self::TYPE_CLIENT); }

    public function items()
    {
        return $this->hasMany(Item::class, 'discount_group_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
