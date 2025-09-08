<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierNameToStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('stock_movements', function (Blueprint $table) {
        $table->string('supplier_name')->nullable()->after('cost_price');
    });
}

public function down()
{
    Schema::table('stock_movements', function (Blueprint $table) {
        $table->dropColumn('supplier_name');
    });
}

}
