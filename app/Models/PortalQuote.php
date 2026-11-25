<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalQuote extends Model
{
    protected $guarded = [];
    protected $casts = ['answered_at' => 'datetime', 'decided_at' => 'datetime', 'valid_until' => 'date'];

    const LABELS = ['new' => 'Envoyée', 'processing' => 'En cours de traitement', 'answered' => 'Devis disponible', 'negotiating' => 'Modification demandée', 'accepted' => 'Acceptée', 'declined' => 'Refusée', 'cancelled' => 'Annulée'];

    public function events() { return $this->hasMany(PortalQuoteEvent::class, 'quote_id')->orderBy('id'); }

    /** Trace un échange (jamais bloquant). */
    public function log($actor, $type, $message = null, $who = null)
    {
        try {
            PortalQuoteEvent::create(['quote_id' => $this->id, 'actor' => $actor, 'type' => $type, 'message' => $message ? mb_substr($message, 0, 2000) : null, 'who' => $who, 'created_at' => now()]);
        } catch (\Throwable $e) { }
    }

    public function files() { return $this->hasMany(PortalQuoteFile::class, 'quote_id')->orderBy('id'); }
    public function lines() { return $this->hasMany(PortalQuoteLine::class, 'quote_id')->orderBy('id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function user() { return $this->belongsTo(B2bUser::class, 'b2b_user_id'); }
    public function getStatusLabelAttribute() { return self::LABELS[$this->status] ?? $this->status; }
    public function getExpiredAttribute() { return $this->status === 'answered' && $this->valid_until && $this->valid_until->copy()->endOfDay()->isPast(); }
}
