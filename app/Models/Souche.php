<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souche extends Model
{
    protected $fillable = [
        'code',
        'name',
        'prefix',
        'last_number',
        'number_length',
        'suffix',
        'type',
    ];

    /**
     * Génère le prochain numéro complet basé sur la souche
     */
    public function getNextNumber()
    {
        $next = $this->last_number + 1;
        $number = str_pad($next, $this->number_length, '0', STR_PAD_LEFT);
        return ($this->prefix ?? '') . $number . ($this->suffix ?? '');
    }

    /**
     * Incrémente le numéro après usage
     */
    public function incrementNumber()
    {
        $this->increment('last_number');
    }
}
