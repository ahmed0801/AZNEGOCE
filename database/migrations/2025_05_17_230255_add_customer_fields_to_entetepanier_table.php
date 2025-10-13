<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerFieldsToEntetepanierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entetepanier', function (Blueprint $table) {
            $table->string('CustomerName')->nullable();
            $table->string('MatFiscale')->nullable();
            $table->string('VATCode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entetepanier', function (Blueprint $table) {
            $table->dropColumn(['CustomerName', 'MatFiscale', 'VATCode']);
        });
    }
}
