<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Retours d'achat : statut (pour pouvoir annuler un retour) et TVA par ligne.
 * Rejouable sans risque : chaque colonne n'est ajoutée que si elle n'existe pas.
 */
class PreparePurchaseReturns extends Migration
{
    public function up()
    {
        Schema::table('purchase_returns', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_returns', 'status')) $t->string('status', 20)->default('validée');
        });
        Schema::table('purchase_return_lines', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_return_lines', 'tva')) $t->decimal('tva', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('purchase_returns', function (Blueprint $t) {
            if (Schema::hasColumn('purchase_returns', 'status')) $t->dropColumn('status');
        });
        Schema::table('purchase_return_lines', function (Blueprint $t) {
            if (Schema::hasColumn('purchase_return_lines', 'tva')) $t->dropColumn('tva');
        });
    }
}
