<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'category_id',
        'brand_id', 'unit_id', 'barcode',
        'cost_price', 'remise_achat',
    'codefournisseur_2', 'cost_price_2', 'remise_achat_2',
    'codefournisseur_3', 'cost_price_3', 'remise_achat_3'
    , 'sale_price','tva_group_id',
        'stock_min', 'stock_max', 'store_id', 'location', 'is_active','codefournisseur','Poids','Hauteur','Longueur','Largeur','Ref_TecDoc','Code_pays','Code_douane','discount_group_id'
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

public function supplier2()
{
    return $this->belongsTo(Supplier::class, 'codefournisseur_2', 'code');
}

public function supplier3()
{
    return $this->belongsTo(Supplier::class, 'codefournisseur_3', 'code');
}



// Nouvelle relation
    public function discountGroup()
    {
        return $this->belongsTo(DiscountGroup::class);
    }





}
