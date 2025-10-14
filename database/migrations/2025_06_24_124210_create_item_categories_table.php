<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_categories', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // String primary key with max length of 20
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });


        // ⚡ Insertion des catégories par défaut
        DB::table('item_categories')->insert([
            ['id' => 'NU01', 'name' => 'ACCESSOIRES AUTO', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU02', 'name' => 'ALIMENTATION DIESEL', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU03', 'name' => 'ALIMENTATION ESSENCE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU04', 'name' => 'CARROSSERIE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU05', 'name' => 'CHRONOTACHYGRAPHE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU06', 'name' => "CONSOMMABLES D'ATELIER", 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU07', 'name' => 'DOCUMENTATION & PUBLICITE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU08', 'name' => 'ECHAPPEMENT', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU09', 'name' => 'ECLAIRAGE & SIGNALISATION', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU10', 'name' => 'ELECTRICITE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU11', 'name' => 'EMBRAYAGE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU12', 'name' => "EQUIPEMENTS D'ATELIER", 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU13', 'name' => 'ESSUYAGE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU14', 'name' => 'FILTRATION', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU15', 'name' => 'FREINAGE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU16', 'name' => 'HYGIENE SECURITE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU17', 'name' => 'LIAISON AU SOL', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU18', 'name' => 'LIQUIDES ET LUBRIFIANTS', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU19', 'name' => 'MOTEURS ET DISTRIBUTION', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU20', 'name' => 'NETTOYAGE ET ENTRETIEN', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU21', 'name' => 'OUTILLAGE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU22', 'name' => 'PEINTURE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU23', 'name' => 'PNEUMATIQUE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU24', 'name' => 'SECURITE HABITACLE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU25', 'name' => 'THERMIQUE ET CLIMATISATION', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU26', 'name' => 'INDUSTRIE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU27', 'name' => 'PIECES DE REEMPLOI - ECLAIRAGE & SIGNALISATION', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'NU28', 'name' => 'PIECES DE REMPLOI - CARROSSERIE', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_categories');
    }
}
