<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidationComptableToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->enum('validation_comptable', ['en_attente', 'validé', 'refusé'])
              ->default('en_attente')
              ->after('reconciled');
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn('validation_comptable');
    });
}
}
