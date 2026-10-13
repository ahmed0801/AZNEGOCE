<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Item extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'category_id', 'subcategory_id',
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

    public function subcategory()
    {
        return $this->belongsTo(ItemSubcategory::class, 'subcategory_id');
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











    const LOCK_TABLES = [
    'quote_lines' => 'devis', 'sales_order_lines' => 'commandes vente', 'delivery_note_lines' => 'bons de livraison',
    'sales_invoice_lines' => 'factures vente', 'sales_return_lines' => 'retours vente', 'sales_note_lines' => 'avoirs vente',
    'purchase_project_lines' => 'projets d\'achat', 'purchase_order_lines' => 'commandes achat', 'reception_lines' => 'réceptions',
    'purchase_invoice_lines' => 'factures achat', 'invoice_lines' => 'factures achat', 'purchase_return_lines' => 'retours achat',
    'purchase_note_lines' => 'avoirs achat',
];

/** Codes (parmi $codes) qui ne doivent pas être supprimés : clé = code. Une requête par table, pas par article. */
public static function lockedCodes(array $codes): array
{
    $locked = [];
    if (!$codes) return $locked;
    foreach (array_keys(self::LOCK_TABLES) as $t) {
        if (Schema::hasTable($t) && Schema::hasColumn($t, 'article_code')) {
            foreach (DB::table($t)->whereIn('article_code', $codes)->distinct()->pluck('article_code') as $c) $locked[$c] = true;
        }
    }
    $ids = Item::whereIn('code', $codes)->pluck('id', 'code');   // code => id
    $byId = array_flip($ids->all());                             // id => code
    $withMov = DB::table('stock_movements')->whereIn('item_id', $ids->values())->distinct()->pluck('item_id');
    $withStock = DB::table('stocks')->whereIn('item_id', $ids->values())->where('quantity', '<>', 0)->distinct()->pluck('item_id');
    foreach ($withMov->merge($withStock) as $id) if (isset($byId[$id])) $locked[$byId[$id]] = true;
    return $locked;
}

/** Raisons lisibles pour le message d'erreur. */
public function lockReasons(): array
{
    $r = [];
    if (DB::table('stock_movements')->where('item_id', $this->id)->exists()) $r[] = 'historique de mouvements de stock';
    if (DB::table('stocks')->where('item_id', $this->id)->where('quantity', '<>', 0)->exists()) $r[] = 'stock non nul';
    $docs = [];
    foreach (self::LOCK_TABLES as $t => $label) {
        if (Schema::hasTable($t) && Schema::hasColumn($t, 'article_code') && DB::table($t)->where('article_code', $this->code)->exists()) $docs[$label] = true;
    }
    if ($docs) $r[] = 'utilisé dans : ' . implode(', ', array_keys($docs));
    return $r;
}




}
