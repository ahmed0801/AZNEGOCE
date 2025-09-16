<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountsToPaymentModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            $table->unsignedBigInteger('debit_account_id')->nullable()->after('type')->comment('General account to debit');
            $table->unsignedBigInteger('credit_account_id')->nullable()->after('debit_account_id')->comment('General account to credit');
            $table->foreign('debit_account_id')->references('id')->on('general_accounts')->onDelete('set null');
            $table->foreign('credit_account_id')->references('id')->on('general_accounts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            $table->dropForeign(['debit_account_id']);
            $table->dropForeign(['credit_account_id']);
            $table->dropColumn(['debit_account_id', 'credit_account_id']);
        });
    }
}
