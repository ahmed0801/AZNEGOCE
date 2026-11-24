<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalOrder extends Model
{
    protected $guarded = [];
    protected $casts = ['auto_validated' => 'boolean', 'modified' => 'boolean', 'validated_at' => 'datetime'];

    const LABELS = ['pending' => 'En attente de validation', 'validated' => 'Validée', 'refused' => 'Refusée', 'cancelled' => 'Annulée'];

    public function lines() { return $this->hasMany(PortalOrderLine::class, 'order_id')->orderBy('id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function user() { return $this->belongsTo(B2bUser::class, 'b2b_user_id'); }
    public function getStatusLabelAttribute()
    {
        if ($this->status === 'validated' && $this->modified) return 'Acceptée avec modifications';
        return self::LABELS[$this->status] ?? $this->status;
    }
}
