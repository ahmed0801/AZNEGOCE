<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/** Mode de réception par défaut : retrait sur place (les clients existants sont alignés ; modifiable ensuite par client). */
class DefaultFulfillmentPickup extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE customer_b2b_settings MODIFY fulfillment VARCHAR(10) NOT NULL DEFAULT 'pickup'");
        DB::table('customer_b2b_settings')->where('fulfillment', 'both')->update(['fulfillment' => 'pickup']);
    }

    public function down()
    {
        DB::statement("ALTER TABLE customer_b2b_settings MODIFY fulfillment VARCHAR(10) NOT NULL DEFAULT 'both'");
    }
}
