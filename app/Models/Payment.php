<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payable_id',
        'payable_type',
        'customer_id',
        'supplier_id',
        'account_id', // Add this
        'amount',
        'payment_date',
        'payment_mode',
        'reference',
        'lettrage_code',
        'notes',
        'reconciled', // Add this
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
        'reconciled' => 'boolean', // Add this
    ];

    protected static function booted()
    {
        static::saving(function ($payment) {
            $paymentMode = PaymentMode::where('name', $payment->payment_mode)->first();
            if ($paymentMode) {
                // Adjust amount based on payment mode type
                $payment->amount = $paymentMode->type === 'décaissement' ? -abs($payment->amount) : abs($payment->amount);
            }
        });

        static::saved(function ($payment) {
            $paymentMode = PaymentMode::where('name', $payment->payment_mode)->first();
            if ($paymentMode) {
                // Update customer balance if customer_id is set
                if ($payment->customer_id && $paymentMode->customer_balance_action) {
                    $customer = Customer::find($payment->customer_id);
                    if ($customer) {
                        $amount = abs($payment->amount);
                        if ($paymentMode->customer_balance_action === '+') {
                            $customer->solde += $amount;
                        } else {
                            $customer->solde -= $amount;
                        }
                        $customer->save();
                        \Log::info('Customer balance updated', [
                            'customer_id' => $customer->id,
                            'payment_id' => $payment->id,
                            'action' => $paymentMode->customer_balance_action,
                            'type' => $paymentMode->type,
                            'new_balance' => $customer->solde,
                        ]);
                    }
                }

                // Update supplier balance if supplier_id is set
                if ($payment->supplier_id && $paymentMode->supplier_balance_action) {
                    $supplier = Supplier::find($payment->supplier_id);
                    if ($supplier) {
                        $amount = abs($payment->amount);
                        if ($paymentMode->supplier_balance_action === '+') {
                            $supplier->solde += $amount;
                        } else {
                            $supplier->solde -= $amount;
                        }
                        $supplier->save();
                        \Log::info('Supplier balance updated', [
                            'supplier_id' => $supplier->id,
                            'payment_id' => $payment->id,
                            'action' => $paymentMode->supplier_balance_action,
                            'type' => $paymentMode->type,
                            'new_balance' => $supplier->solde,
                        ]);
                    }
                }

                // Update general account balances
                $amount = abs($payment->amount);
                if ($paymentMode->debit_account_id) {
                    $debitAccount = GeneralAccount::find($paymentMode->debit_account_id);
                    if ($debitAccount) {
                        $debitAccount->balance += $amount; // ✅ Débit augmente le solde
                        $debitAccount->save();
                        \Log::info('Debit account balance updated', [
                            'account_id' => $debitAccount->id,
                            'payment_id' => $payment->id,
                            'amount' => $amount,
                            'new_balance' => $debitAccount->balance,
                        ]);
                    }
                }
                if ($paymentMode->credit_account_id) {
                    $creditAccount = GeneralAccount::find($paymentMode->credit_account_id);
                    if ($creditAccount) {
                        $creditAccount->balance -= $amount; // ✅ Crédit diminue le solde
                        $creditAccount->save();
                        \Log::info('Credit account balance updated', [
                            'account_id' => $creditAccount->id,
                            'payment_id' => $payment->id,
                            'amount' => $amount,
                            'new_balance' => $creditAccount->balance,
                        ]);
                    }
                }
            }
        });
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

        public function transfers()
    {
        return $this->hasMany(AccountTransfer::class);
    }

     public function paymentMode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode', 'name');
    }



    // Add relationship to GeneralAccount
    public function account()
    {
        return $this->belongsTo(GeneralAccount::class, 'account_id');
    }

    

    public function generateLettrageCode()
    {
        $invoice = $this->payable;
        $prefix = $invoice instanceof Invoice ? 'CL' : ($invoice instanceof PurchaseInvoice ? 'FR' : ($invoice instanceof SalesNote ? 'AVCL' : 'AVFR'));
        return $prefix . '-' . $invoice->numdoc . '-' . $this->payment_date->format('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}