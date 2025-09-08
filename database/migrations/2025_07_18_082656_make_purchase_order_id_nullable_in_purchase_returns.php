<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePurchaseOrderIdNullableInPurchaseReturns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->foreignId('purchase_order_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->foreignId('purchase_order_id')->nullable(false)->change();
        });
    }
}
