<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTvaRateAndSupplierIdToPurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable()->after('purchase_order_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->decimal('tva_rate', 5, 2)->nullable()->after('total_ttc');
        });
    }

    public function down()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
            $table->dropColumn('tva_rate');
        });
    }
}
