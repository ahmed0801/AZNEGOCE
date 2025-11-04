<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendeurToSalesReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->string('vendeur')->nullable()->after('customer_id');
        });
    }

    public function down()
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->dropColumn('vendeur');
        });
    }
}
