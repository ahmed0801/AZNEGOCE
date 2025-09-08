<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRetourVenteToStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
                    DB::statement("ALTER TABLE stock_movements MODIFY COLUMN type ENUM('achat','retour_achat','retour_vente', 'vente', 'ajustement', 'transfert', 'inventaire', 'annulation_expedition')");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
                   // Remove any rows with type 'annulation_vente' to avoid issues when reverting
        DB::table('stock_movements')
            ->where('type', 'retour_achat')
            ->update(['type' => 'ajustement']);

        // Revert the type column to original ENUM values
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN type ENUM('achat', 'vente', 'ajustement', 'transfert', 'inventaire')");
        });
    }
}
