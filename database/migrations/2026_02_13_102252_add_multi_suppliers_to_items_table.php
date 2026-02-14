<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiSuppliersToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {


            // Ajout de la remise achat pour le fournisseur principal
            $table->decimal('remise_achat', 5, 2)
                  ->nullable()
                  ->default(0.00)
                  ->after('cost_price')
                  ->comment('Remise achat (%) pour le fournisseur principal');

            // Fournisseur secondaire 1 (optionnel)
            $table->string('codefournisseur_2')
                  ->nullable()
                  ->after('remise_achat')
                  ->comment('Code du deuxième fournisseur optionnel');

            $table->decimal('cost_price_2', 12, 4)
                  ->nullable()
                  ->default(0.00)
                  ->after('codefournisseur_2')
                  ->comment('Prix d\'achat chez le fournisseur 2');

            $table->decimal('remise_achat_2', 5, 2)
                  ->nullable()
                  ->default(0.00)
                  ->after('cost_price_2')
                  ->comment('Remise achat (%) pour le fournisseur 2');

            // Fournisseur secondaire 2 (optionnel)
            $table->string('codefournisseur_3')
                  ->nullable()
                  ->after('remise_achat_2')
                  ->comment('Code du troisième fournisseur optionnel');

            $table->decimal('cost_price_3', 12, 4)
                  ->nullable()
                  ->default(0.00)
                  ->after('codefournisseur_3')
                  ->comment('Prix d\'achat chez le fournisseur 3');

            $table->decimal('remise_achat_3', 5, 2)
                  ->nullable()
                  ->default(0.00)
                  ->after('cost_price_3')
                  ->comment('Remise achat (%) pour le fournisseur 3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Suppression des nouveaux champs (dans l'ordre inverse)
            $table->dropColumn([
                'remise_achat',
                'codefournisseur_2',
                'cost_price_2',
                'remise_achat_2',
                'codefournisseur_3',
                'cost_price_3',
                'remise_achat_3',
            ]);

        });
    }
};