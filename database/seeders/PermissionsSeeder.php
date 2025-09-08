<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
// PermissionsSeeder.php

public function run()
{
    $permissions = [
        ['key' => 'delete_invoice', 'label' => 'Supprimer des factures'],
        ['key' => 'delete_article', 'label' => 'Supprimer des articles'],
        ['key' => 'delete_client', 'label' => 'Supprimer un client'],
        ['key' => 'delete_supplier', 'label' => 'Supprimer un fournisseur'],
        ['key' => 'edit_client', 'label' => 'Modifier un client'],
        ['key' => 'edit_supplier', 'label' => 'Modifier un fournisseur'],
        ['key' => 'edit_article', 'label' => 'Modifier un article'],
        ['key' => 'delete_bl', 'label' => 'Supprimer un BL'],
        ['key' => 'edit_bl', 'label' => 'Modifier un BL'],
        ['key' => 'make_payments', 'label' => 'Faire des règlements'],
        ['key' => 'adjust_stock', 'label' => 'Ajuster le stock'],
        ['key' => 'view_sales', 'label' => 'Consulter le chiffre d’affaires'],
        ['key' => 'sell', 'label' => 'Vendre'],
        ['key' => 'buy', 'label' => 'Acheter'],
        ['key' => 'receive', 'label' => 'Réceptionner'],
        ['key' => 'edit_settings', 'label' => 'Éditer les paramètres de magasin, article et stock'],
        ['key' => 'edit_discount_groups', 'label' => 'Éditer les groupes remises'],
        ['key' => 'edit_accounting_plan', 'label' => 'Éditer le plan comptable'],
    ];

    foreach ($permissions as $perm) {
        \App\Models\Permission::firstOrCreate($perm);
    }
}

}
