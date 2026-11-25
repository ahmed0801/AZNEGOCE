<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalPromotion extends Model
{
    protected $table = 'portal_promotions';
    protected $guarded = [];
    protected $casts = ['active' => 'boolean', 'starts_at' => 'datetime', 'ends_at' => 'datetime'];

    public function items()
    {
        return $this->hasMany(PortalPromotionItem::class, 'promotion_id');
    }

    /** upcoming | running | ended | off */
    public function getStateAttribute()
    {
        if (!$this->active) return 'off';
        $now = now();
        if ($this->ends_at->lt($now)) return 'ended';
        if ($this->starts_at->gt($now)) return 'upcoming';
        return 'running';
    }
}
