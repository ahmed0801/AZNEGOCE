<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToEntetepanierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entetepanier', function (Blueprint $table) {
            $table->enum('type', ['devis', 'brouillon', 'demain'])->default('devis')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entetepanier', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
