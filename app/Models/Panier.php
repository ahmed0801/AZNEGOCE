<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'panier';

    // Attributs modifiables en masse
    protected $fillable = [
        'entetepanier_id', // Référence à l'entête du panier
        'item_reference',  // Référence de l'article
        'item_name',       // Nom de l'article
        'quantity',        // Quantité
        'price',           // Prix
        'remise',
    ];

    /**
     * Relation avec l'entête de panier
     */
    public function entetepanier()
    {
        return $this->belongsTo(Entetepanier::class, 'entetepanier_id');
    }
}
