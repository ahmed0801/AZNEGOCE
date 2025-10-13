<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaymentsTableMakePayableNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Make payable_id and payable_type nullable
            $table->unsignedBigInteger('payable_id')->nullable()->change();
            $table->string('payable_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert to non-nullable (ensure no null values exist before applying)
            $table->unsignedBigInteger('payable_id')->nullable(false)->change();
            $table->string('payable_type')->nullable(false)->change();
        });
    }
}
