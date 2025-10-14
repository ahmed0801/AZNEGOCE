<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = ['id','name', 'description'];
    protected $primaryKey = 'id';
    protected $keyType = 'string'; // Specify that the primary key is a string
    public $incrementing = false; // Disable auto-incrementing

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}