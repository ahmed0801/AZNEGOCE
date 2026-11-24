<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/** Recherche du portail plus rapide : index du stock par article (sous-requête de stock) et tri par référence. */
class AddPortalSearchSpeedIndexes extends Migration
{
    public function up()
    {
        foreach ([
            'portal_stocks_item_idx'   => 'stocks (item_id)',
            'portal_items_active_code' => 'items (is_active, code)',
        ] as $name => $def) {
            try { DB::statement("CREATE INDEX $name ON $def"); } catch (\Throwable $e) { /* déjà présent */ }
        }
    }

    public function down()
    {
        foreach (['stocks' => 'portal_stocks_item_idx', 'items' => 'portal_items_active_code'] as $table => $n) {
            try { DB::statement("DROP INDEX $n ON $table"); } catch (\Throwable $e) {}
        }
    }
}
