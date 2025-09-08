<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateTypeEnumInStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remplacer la colonne ENUM par une nouvelle liste de valeurs
        DB::statement("ALTER TABLE stock_movements MODIFY type ENUM(
            'achat', 
            'vente', 
            'ajustement', 
            'transfert', 
            'inventaire', 
            'retour_achat', 
            'retour_vente'
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revenir à l'ancien ENUM si besoin
        DB::statement("ALTER TABLE stock_movements MODIFY type ENUM(
            'achat', 
            'vente', 
            'ajustement', 
            'transfert', 
            'inventaire'
        )");
    }
}
