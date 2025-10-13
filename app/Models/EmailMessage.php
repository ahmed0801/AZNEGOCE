<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
{
        use HasFactory;

    protected $table = 'email_messages';

    protected $fillable = [
        'messagefacturevente',
        'messagefactureachat',
        'messageavoirvente',
        'messageavoirachat',
        'messagedeliverynote',
        'messageconfirmationdelivraison',
        'messagedemanderetourachat',
    ];
}
