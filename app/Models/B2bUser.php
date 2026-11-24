<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/** Compte de connexion au portail B2B (rattaché à un client). */
class B2bUser extends Authenticatable
{
    protected $table = 'b2b_users';
    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'must_change_password' => 'boolean',
        'last_login_at'        => 'datetime',
        'reset_requested_at'   => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}
