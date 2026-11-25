<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalQuoteEvent extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['created_at' => 'datetime'];

    const LABELS = ['created' => 'Demande envoyée', 'answered' => 'Devis envoyé par notre équipe', 'negotiate' => 'Modification demandée', 'accepted' => 'Devis accepté', 'declined' => 'Refusé', 'cancelled' => 'Demande annulée', 'file' => 'Pièce jointe ajoutée'];
}
