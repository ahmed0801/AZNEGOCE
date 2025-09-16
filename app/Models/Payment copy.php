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
        'amount',
        'payment_date',
        'payment_mode',
        'reference',
        'lettrage_code',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saved(function ($payment) {
            // Update customer balance if customer_id is set
            if ($payment->customer_id) {
                $paymentMode = PaymentMode::where('name', $payment->payment_mode)->first();
                if ($paymentMode && $paymentMode->customer_balance_action) {
                    $customer = Customer::find($payment->customer_id);
                    if ($customer) {
                        $amount = $payment->amount;
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
                            'new_balance' => $customer->solde,
                        ]);
                    }
                }
            }

            // Update supplier balance if supplier_id is set
            if ($payment->supplier_id) {
                $paymentMode = PaymentMode::where('name', $payment->payment_mode)->first();
                if ($paymentMode && $paymentMode->supplier_balance_action) {
                    $supplier = Supplier::find($payment->supplier_id);
                    if ($supplier) {
                        $amount = $payment->amount;
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
                            'new_balance' => $supplier->solde,
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

    public function generateLettrageCode()
    {
        $invoice = $this->payable;
        $prefix = $invoice instanceof Invoice ? 'CL' : 'FR';
        return $prefix . '-' . $invoice->numdoc . '-' . $this->payment_date->format('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}