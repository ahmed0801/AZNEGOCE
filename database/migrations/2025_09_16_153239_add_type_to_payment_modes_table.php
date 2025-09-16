<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToPaymentModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            $table->string('type')->after('name')->default('décaissement')->comment('Type of payment mode: décaissement or encaissement');

        });
    }

    public function down()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            $table->dropColumn('type');

        });
    }
}
