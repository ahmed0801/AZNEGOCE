<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostPriceToStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::table('stock_movements', function (Blueprint $table) {
    $table->decimal('cost_price', 12, 2)->nullable()->after('quantity');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
Schema::table('stock_movements', function (Blueprint $table) {
    $table->dropColumn('cost_price');
});
    }
}
