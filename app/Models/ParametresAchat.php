<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametresAchat extends Model
{
    use HasFactory;

    protected $table = 'parametres_achat';

    protected $fillable = [
        'reception_obligatoire_validation',
        'reception_obligatoire_retour',
    ];
}
