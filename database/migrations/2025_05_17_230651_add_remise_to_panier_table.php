<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemiseToPanierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panier', function (Blueprint $table) {
            $table->decimal('remise', 10, 2)->nullable()->after('price'); // Champ remise nullable après le prix
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panier', function (Blueprint $table) {
            $table->dropColumn('remise');
        });
    }
}
