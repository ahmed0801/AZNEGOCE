<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_return_id', 'article_code', 'returned_quantity', 'unit_price_ht',
        'remise', 'total_ligne_ht'
    ];

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if ($model->returned_quantity < 0) {
                throw new \Exception('La quantité retournée doit être positive.');
            }
        });
    }
}