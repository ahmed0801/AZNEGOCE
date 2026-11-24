<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/** Index pour les filtres du catalogue portail (marque, famille/sous-famille). Sans effet s'ils existent déjà. */
class AddCatalogIndexes extends Migration
{
    public function up()
    {
        foreach ([
            'portal_items_brand_idx'  => 'items (is_active, brand_id)',
            'portal_items_family_idx' => 'items (is_active, category_id, subcategory_id)',
        ] as $name => $def) {
            try { DB::statement("CREATE INDEX $name ON $def"); } catch (\Throwable $e) { /* déjà présent */ }
        }
    }

    public function down()
    {
        foreach (['portal_items_brand_idx', 'portal_items_family_idx'] as $n) {
            try { DB::statement("DROP INDEX $n ON items"); } catch (\Throwable $e) {}
        }
    }
}
