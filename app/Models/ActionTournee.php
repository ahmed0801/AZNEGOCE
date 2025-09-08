<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionTournee extends Model
{
    use HasFactory;

    protected $table = 'actions_tournees';

    protected $fillable = [
        'planification_tournee_id', 'user_id', 'type_action', 'type_document',
        'document_id', 'code_article', 'quantite', 'notes'
    ];

    public function planificationTournee()
    {
        return $this->belongsTo(PlanificationTournee::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}