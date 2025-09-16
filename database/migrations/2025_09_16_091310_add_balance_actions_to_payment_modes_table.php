<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceActionsToPaymentModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            $table->enum('customer_balance_action', ['+', '-'])->nullable()->default('+')->after('name');
            $table->enum('supplier_balance_action', ['+', '-'])->nullable()->default('-')->after('customer_balance_action');
        });
    }

    public function down()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            $table->dropColumn(['customer_balance_action', 'supplier_balance_action']);
        });
    }
}
