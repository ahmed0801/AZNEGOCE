<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndNumclientToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('type')->nullable()->after('numdoc'); // Add type column (direct, groupée, libre)
            $table->string('numclient')->nullable()->after('customer_id'); // Add numclient column for customer code
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('numclient');
        });
    }
}
