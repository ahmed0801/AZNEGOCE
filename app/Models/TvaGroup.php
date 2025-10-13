<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvaGroup extends Model
{
    use HasFactory;
        protected $fillable = ['name', 'rate','code'];

    public function items()
    {
        return $this->hasMany(Item::class, 'tva_group_id');
    }
}
