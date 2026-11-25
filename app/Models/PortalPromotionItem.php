<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalPromotionItem extends Model
{
    protected $table = 'portal_promotion_items';
    protected $guarded = [];

    public function promotion()
    {
        return $this->belongsTo(PortalPromotion::class, 'promotion_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
