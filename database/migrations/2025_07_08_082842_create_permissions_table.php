<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // create_permissions_table.php

public function up()
{
    Schema::create('permissions', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // exemple: delete_invoice
        $table->string('label'); // exemple: Supprimer les factures
        $table->timestamps();
    });

    Schema::create('permission_user', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('permission_id')->constrained()->onDelete('cascade');
        $table->primary(['user_id', 'permission_id']);
    });


      // ⚡ Insertion des permissions par défaut
        DB::table('permissions')->insert([
            ['key' => 'delete_invoice', 'label' => 'Supprimer des factures', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'delete_article', 'label' => 'Supprimer des articles', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'delete_client', 'label' => 'Supprimer un client', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'delete_supplier', 'label' => 'Supprimer un fournisseur', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'edit_client', 'label' => 'Modifier un client', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'edit_supplier', 'label' => 'Modifier un fournisseur', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'edit_article', 'label' => 'Modifier un article', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'delete_bl', 'label' => 'Supprimer un BL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'edit_bl', 'label' => 'Modifier un BL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'make_payments', 'label' => 'Faire des règlements', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'adjust_stock', 'label' => 'Ajuster le stock', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'view_sales', 'label' => 'Consulter le chiffre d’affaires', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'sell', 'label' => 'Vendre', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'buy', 'label' => 'Acheter', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'receive', 'label' => 'Réceptionner', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'edit_settings', 'label' => 'Éditer les paramètres', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'edit_discount_groups', 'label' => 'Éditer les groupes remises', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'edit_accounting_plan', 'label' => 'Éditer le plan comptable', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }


}


