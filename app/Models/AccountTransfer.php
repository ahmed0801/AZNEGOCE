<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'from_account_id',
        'to_account_id',
        'amount',
        'transfer_date',
        'reference',
        'notes',
    ];

    protected $casts = [
        'transfer_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saved(function ($transfer) {
            // Update source account (decrease balance)
            $fromAccount = GeneralAccount::find($transfer->from_account_id);
            if ($fromAccount) {
                $fromAccount->balance -= abs($transfer->amount);
                $fromAccount->save();
                \Log::info('Source account balance updated', [
                    'account_id' => $fromAccount->id,
                    'transfer_id' => $transfer->id,
                    'amount' => $transfer->amount,
                    'new_balance' => $fromAccount->balance,
                ]);
            }

            // Update destination account (increase balance)
            $toAccount = GeneralAccount::find($transfer->to_account_id);
            if ($toAccount) {
                $toAccount->balance += abs($transfer->amount);
                $toAccount->save();
                \Log::info('Destination account balance updated', [
                    'account_id' => $toAccount->id,
                    'transfer_id' => $transfer->id,
                    'amount' => $transfer->amount,
                    'new_balance' => $toAccount->balance,
                ]);
            }
        });
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function fromAccount()
    {
        return $this->belongsTo(GeneralAccount::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(GeneralAccount::class, 'to_account_id');
    }
}