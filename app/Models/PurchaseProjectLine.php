<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProjectLine extends Model
{
    protected $fillable = [
        'purchase_project_id', 'article_code', 'ordered_quantity', 'unit_price_ht',
        'remise', 'tva', 'total_ligne_ht', 'prix_ttc'
    ];

    public function purchaseProject()
    {
        return $this->belongsTo(PurchaseProject::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}