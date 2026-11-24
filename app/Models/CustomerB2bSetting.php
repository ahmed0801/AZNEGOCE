<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerB2bSetting extends Model
{
    protected $table = 'customer_b2b_settings';
    protected $guarded = ['id'];
    protected $casts = [
        'portal_enabled' => 'boolean',
        'can_order'      => 'boolean',
        'show_prices'    => 'boolean',
        'show_finance'   => 'boolean',
    ];

    public static function forCustomer($customerId)
    {
        return static::firstOrCreate(['customer_id' => $customerId]);
    }
}
