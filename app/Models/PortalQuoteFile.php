<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalQuoteFile extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['created_at' => 'datetime'];

    public function getIsImageAttribute() { return strpos($this->mime, 'image/') === 0; }
}
