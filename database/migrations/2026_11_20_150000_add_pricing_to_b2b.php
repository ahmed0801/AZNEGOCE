<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Tarification B2B : profil tarifaire des groupes client + garde-fou de marge. */
class AddPricingToB2b extends Migration
{
    public function up()
    {
        Schema::table('discount_groups', function (Blueprint $table) {
            // Pour un groupe « client » : quelle colonne de remise du groupe ARTICLE s'applique (general | jobber | professionnel).
            // NULL = on suit le type du client dans l'ERP (particulier / jobber / professionnel).
            $table->string('price_tier', 15)->nullable()->after('type');
        });

        Schema::table('b2b_settings', function (Blueprint $table) {
            $table->boolean('margin_guard')->default(true);
            $table->decimal('min_margin_percent', 5, 2)->default(0);   // marge minimale sur le coût d'achat net
        });
    }

    public function down()
    {
        Schema::table('b2b_settings', function (Blueprint $table) {
            $table->dropColumn(['margin_guard', 'min_margin_percent']);
        });
        Schema::table('discount_groups', function (Blueprint $table) {
            $table->dropColumn('price_tier');
        });
    }
}
