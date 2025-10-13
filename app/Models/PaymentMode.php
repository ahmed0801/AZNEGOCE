<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    protected $fillable = [
        'name',
        'customer_balance_action', // '+' or '-' for customer balance
        'supplier_balance_action', // '+' or '-' for supplier balance
                'type', // décaissement or encaissement
                'debit_account_id',
                'credit_account_id',


    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

        public function debitAccount()
    {
        return $this->belongsTo(GeneralAccount::class, 'debit_account_id');
    }

    public function creditAccount()
    {
        return $this->belongsTo(GeneralAccount::class, 'credit_account_id');
    }

}