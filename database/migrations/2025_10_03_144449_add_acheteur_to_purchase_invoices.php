<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcheteurToPurchaseInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
        $table->string('acheteur')->nullable()->after('numdoc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
         $table->dropColumn('acheteur');
        });
    }
}
