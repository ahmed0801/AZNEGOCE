<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Articles à prix 0 dans le portail : autoriser / masquer le prix / interdire l'ajout au panier / les deux. */
class AddZeroPriceModeToB2bSettings extends Migration
{
    public function up()
    {
        Schema::table('b2b_settings', function (Blueprint $table) {
            $table->string('zero_price_mode', 10)->default('both');   // allow | hide | block | both
        });
    }

    public function down()
    {
        Schema::table('b2b_settings', function (Blueprint $table) { $table->dropColumn('zero_price_mode'); });
    }
}
