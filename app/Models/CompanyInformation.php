<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
        protected $table = 'company_informations';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'matricule_fiscal',
        'swift',
        'rib',
        'iban',
        'logo_path',
    ];
}