<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add account_id column, nullable to maintain compatibility with existing payments
            $table->unsignedBigInteger('account_id')->nullable()->after('supplier_id');
            // Optional: Add foreign key constraint
            $table->foreign('account_id')->references('id')->on('general_accounts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });
    }
}
