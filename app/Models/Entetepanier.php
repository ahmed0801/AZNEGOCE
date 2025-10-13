<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entetepanier extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'entetepanier';

    // Attributs modifiables en masse
    protected $fillable = [
        'user_id',   // Correspond au CustomerNo (ex: CL0025)
        'status',
        'type',   
        'CustomerName',
        'MatFiscale', 
        'VATCode',

    ];

    /**
     * Relation avec les paniers
     */
    public function paniers()
    {
        return $this->hasMany(Panier::class, 'entetepanier_id');
    }
}
