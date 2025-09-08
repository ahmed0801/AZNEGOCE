<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id', 'article_code', 'quantity', 'unit_price_ht', 'unit_price_ttc', 'remise', 'total_ligne_ht', 'total_ligne_ttc'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}