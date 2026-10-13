<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSubcategory extends Model
{
    protected $fillable = ['category_id', 'code', 'name', 'default_sale_margin'];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'subcategory_id');
    }
}
