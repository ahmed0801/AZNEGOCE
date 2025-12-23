<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNoteLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id', 'article_code', 'delivered_quantity', 'unit_price_ht', 'unit_price_ttc', 'remise', 'total_ligne_ht', 'total_ligne_ttc',
        'supplier_id', 'unit_coast', 'discount_coast' // ← Nouveaux champs
    ];

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }

    // Nouvelle relation
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Accessors utiles pour afficher la marge nette
    public function getNetPurchasePriceAttribute()
    {
        return $this->unit_coast ? round($this->unit_coast * (1 - $this->discount_coast / 100), 2) : null;
    }

    public function getNetSalePriceAttribute()
    {
        return round($this->unit_price_ht * (1 - $this->remise / 100), 2);
    }

    public function getNetMarginAttribute()
    {
        if (!$this->unit_coast) return null;
        return round($this->netSalePrice - $this->netPurchasePrice, 2);
    }

    public function getNetMarginPercentAttribute()
    {
        if (!$this->unit_coast || $this->netPurchasePrice == 0) return null;
        return round(($this->netMargin / $this->netPurchasePrice) * 100, 1);
    }
    

}