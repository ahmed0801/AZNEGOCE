<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanificationTourneeDocument extends Model
{
    use HasFactory;

    protected $table = 'planification_tournee_documents';

    protected $fillable = [
        'planification_tournee_id', 'document_type', 'document_id'
    ];

    public function planificationTournee()
    {
        return $this->belongsTo(PlanificationTournee::class);
    }
}