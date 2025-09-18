<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'name',
        'balance',
        'type',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function debitPaymentModes()
    {
        return $this->hasMany(PaymentMode::class, 'debit_account_id');
    }

    public function creditPaymentModes()
    {
        return $this->hasMany(PaymentMode::class, 'credit_account_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'account_id');
    }

    public function fromTransfers()
    {
        return $this->hasMany(AccountTransfer::class, 'from_account_id');
    }

    public function toTransfers()
    {
        return $this->hasMany(AccountTransfer::class, 'to_account_id');
    }
}