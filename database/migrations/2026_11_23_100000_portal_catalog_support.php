<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Portail B2B : demande de réinitialisation de mot de passe + colonnes de recherche « métier » sur les articles.
 *
 * code_norm / search_refs sont des colonnes CALCULÉES par MySQL (mises à jour automatiquement quand un article change) :
 * référence sans espaces, tirets, points ni barres, en majuscules. Ainsi « 0 986 424 797 » trouve « 0986424797 ».
 * Nécessite MySQL 5.7+ / MariaDB 10.2+.
 */
class PortalCatalogSupport extends Migration
{
    private function norm($col)
    {
        $e = "COALESCE($col,'')";
        foreach ([' ', '-', '.', '/', '_'] as $ch) {
            $e = "REPLACE($e,'$ch','')";
        }
        return "UPPER($e)";
    }

    public function up()
    {
        Schema::table('b2b_users', function (Blueprint $table) {
            $table->timestamp('reset_requested_at')->nullable();
        });

        // Si ces colonnes ne peuvent pas être créées (version de MySQL ancienne), le portail fonctionne quand même
        // avec une recherche plus simple (le service détecte l'absence des colonnes).
        try {
            if (!Schema::hasColumn('items', 'code_norm')) {
                DB::statement('ALTER TABLE items ADD COLUMN code_norm VARCHAR(255) GENERATED ALWAYS AS (' . $this->norm('code') . ') STORED');
            }
            if (!Schema::hasColumn('items', 'search_refs')) {
                $parts = [];
                // volontairement SANS les codes fournisseurs : ils ne doivent jamais être cherchables par un client
                foreach (['code', 'Ref_TecDoc', 'barcode'] as $c) {
                    $parts[] = $this->norm($c);
                }
                DB::statement("ALTER TABLE items ADD COLUMN search_refs VARCHAR(1000) GENERATED ALWAYS AS (CONCAT_WS(' ', " . implode(', ', $parts) . ")) STORED");
            }
        } catch (\Throwable $e) {
            echo "ATTENTION : colonnes de recherche non créées (" . $e->getMessage() . ")\n";
        }
        // index sur la table d'équivalences (recherche dans les deux sens)
        try {
            Schema::table('item_equivalents', function (Blueprint $table) {
                $table->index('equivalent_item_id', 'item_equivalents_equiv_idx');
            });
        } catch (\Throwable $e) { /* l'index existe peut-être déjà */ }
    }

    public function down()
    {
        try { DB::statement('ALTER TABLE items DROP COLUMN search_refs'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE items DROP COLUMN code_norm'); } catch (\Throwable $e) {}
        Schema::table('b2b_users', function (Blueprint $table) {
            $table->dropColumn('reset_requested_at');
        });
    }
}
