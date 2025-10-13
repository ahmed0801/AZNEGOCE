<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PlanificationTournee extends Model
{
    use HasFactory;

    protected $table = 'planifications_tournees';

    protected $fillable = [
        'user_id', 'datetime_planifie', 'statut','validee_at', 'notes'
    ];

    protected $casts = [
        'datetime_planifie' => 'datetime',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(PlanificationTourneeDocument::class);
    }

    public function commandesAchats()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'planification_tournee_documents', 'planification_tournee_id', 'document_id')
                    ->wherePivot('document_type', 'commande_achat')
                    ->withPivot('document_type');
    }

    public function bonsLivraisons()
    {
        return $this->belongsToMany(DeliveryNote::class, 'planification_tournee_documents', 'planification_tournee_id', 'document_id')
                    ->wherePivot('document_type', 'bon_livraison')
                    ->withPivot('document_type');
    }

    public function actions()
    {
        return $this->hasMany(ActionTournee::class);
    }

        public function isValidee()
    {
        return !is_null($this->validee_at);
    }
}