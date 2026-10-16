<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPriceChange extends Model
{
    protected $fillable = ['item_id', 'article_code', 'old_sale_price', 'new_sale_price', 'net_cost', 'strategy', 'source', 'user_id'];
}
