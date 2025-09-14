<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDeliveryNoteIdNullableInSalesReturns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_note_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_note_id')->nullable(false)->change();
        });
    }
}
