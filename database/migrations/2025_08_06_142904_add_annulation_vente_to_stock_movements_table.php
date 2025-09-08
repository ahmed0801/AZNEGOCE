<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddAnnulationVenteToStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update any invalid or NULL type values to a default valid value
        DB::table('stock_movements')
            ->whereNotIn('type', ['achat', 'vente', 'ajustement', 'transfert', 'inventaire'])
            ->orWhereNull('type')
            ->update(['type' => 'ajustement']);

        // Modify the type column to include annulation_vente
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN type ENUM('achat', 'vente', 'ajustement', 'transfert', 'inventaire', 'annulation_expedition')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove any rows with type 'annulation_vente' to avoid issues when reverting
        DB::table('stock_movements')
            ->where('type', 'annulation_vente')
            ->update(['type' => 'ajustement']);

        // Revert the type column to original ENUM values
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN type ENUM('achat', 'vente', 'ajustement', 'transfert', 'inventaire')");
    }
}
?>