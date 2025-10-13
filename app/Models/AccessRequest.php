<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_name',
        'requester_name',
        'whatsapp_number',
        'is_client',
        'status',
        'numclient', // Ajoutez numclient ici
    ];
}
