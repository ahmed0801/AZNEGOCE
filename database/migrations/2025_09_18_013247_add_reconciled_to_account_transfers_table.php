<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReconciledToAccountTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('account_transfers', function (Blueprint $table) {
            $table->boolean('reconciled')->default(false)->after('notes');
        });
    }

    public function down()
    {
        Schema::table('account_transfers', function (Blueprint $table) {
            $table->dropColumn('reconciled');
        });
    }
}
