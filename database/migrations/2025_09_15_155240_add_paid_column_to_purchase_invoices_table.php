<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidColumnToPurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->boolean('paid')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn('paid');
        });
    }
}
