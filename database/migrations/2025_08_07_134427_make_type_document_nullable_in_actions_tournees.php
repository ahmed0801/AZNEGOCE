
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeTypeDocumentNullableInActionsTournees extends Migration
{
    public function up()
    {
        // Mettre à jour les enregistrements existants pour éviter les conflits
        DB::table('actions_tournees')
            ->whereNull('type_document')
            ->update(['type_document' => 'inconnu']);

        // Rendre la colonne type_document nullable
        DB::statement("ALTER TABLE actions_tournees MODIFY COLUMN type_document VARCHAR(255) NULL");
        
        // Rendre la colonne document_id nullable si nécessaire
        DB::statement("ALTER TABLE actions_tournees MODIFY COLUMN document_id BIGINT UNSIGNED NULL");
    }

    public function down()
    {
        // Restaurer une valeur par défaut pour type_document
        DB::table('actions_tournees')
            ->whereNull('type_document')
            ->update(['type_document' => 'inconnu']);

        // Restaurer la contrainte NOT NULL
        DB::statement("ALTER TABLE actions_tournees MODIFY COLUMN type_document VARCHAR(255) NOT NULL DEFAULT 'inconnu'");
        
        // Restaurer document_id comme NOT NULL si nécessaire
        DB::table('actions_tournees')
            ->whereNull('document_id')
            ->update(['document_id' => 0]);
        DB::statement("ALTER TABLE actions_tournees MODIFY COLUMN document_id BIGINT UNSIGNED NOT NULL DEFAULT 0");
    }
}
