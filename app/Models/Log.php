<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table = 'logs'; // Nom de la table
    protected $fillable = ['CustomerNo','CustomerName', 'login_date','lieu_de_connexion','region']; // Colonnes pouvant être remplies
}
