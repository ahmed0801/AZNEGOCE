<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Avoirs d'achat : « return_goods » (la validation de l'avoir fait ressortir la marchandise du stock via un retour
 * automatique) et le lien retour automatique → avoir qui l'a généré. Rejouable sans risque.
 */
class PreparePurchaseNotes extends Migration
{
    public function up()
    {
        Schema::table('purchase_notes', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_notes', 'return_goods')) $t->boolean('return_goods')->default(false);
        });
        Schema::table('purchase_returns', function (Blueprint $t) {
            if (!Schema::hasColumn('purchase_returns', 'purchase_note_id')) $t->unsignedBigInteger('purchase_note_id')->nullable()->index();
        });
    }

    public function down()
    {
        Schema::table('purchase_notes', function (Blueprint $t) {
            if (Schema::hasColumn('purchase_notes', 'return_goods')) $t->dropColumn('return_goods');
        });
        Schema::table('purchase_returns', function (Blueprint $t) {
            if (Schema::hasColumn('purchase_returns', 'purchase_note_id')) $t->dropColumn('purchase_note_id');
        });
    }
}
