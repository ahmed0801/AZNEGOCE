<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Mode de remise d'un groupe client : profile (colonne des groupes articles) ou fixed (taux fixe, le plus avantageux des deux). */
class AddPriceModeToDiscountGroups extends Migration
{
    public function up()
    {
        Schema::table('discount_groups', function (Blueprint $table) {
            $table->string('price_mode', 10)->default('profile')->after('price_tier');
        });
    }

    public function down()
    {
        Schema::table('discount_groups', function (Blueprint $table) {
            $table->dropColumn('price_mode');
        });
    }
}
