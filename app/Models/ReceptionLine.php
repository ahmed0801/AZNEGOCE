<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceptionLine extends Model
{
    protected $fillable = [
        'reception_id',
        'article_code',
        'received_quantity'
    ];

    public function reception()
    {
        return $this->belongsTo(Reception::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}
