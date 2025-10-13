<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'category_id',
        'brand_id', 'unit_id', 'barcode',
        'cost_price', 'sale_price','tva_group_id',
        'stock_min', 'stock_max', 'store_id', 'location', 'is_active','codefournisseur'
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function equivalents()
    {
        return $this->belongsToMany(Item::class, 'item_equivalents', 'item_id', 'equivalent_item_id');
    }

    public function tvaGroup()
{
    return $this->belongsTo(TvaGroup::class, 'tva_group_id');
}
public function store()
{
    return $this->belongsTo(Store::class);
}


public function stocks()
{
    return $this->hasMany(Stock::class);
}

public function stockMovements()
{
    return $this->hasMany(StockMovement::class);
}

public function getStockQuantityAttribute()
{
    return $this->stocks->sum('quantity');
}

public function supplier()
{
    return $this->belongsTo(Supplier::class, 'codefournisseur', 'code');
}






}
