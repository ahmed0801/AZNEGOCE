<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSouchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
    {
        Schema::create('souches', function (Blueprint $table) {
            $table->id();
            
            $table->string('code')->unique();  // code unique pour chaque souche, ex: 'FACTURE_VENTE'
            $table->string('name');             // nom descriptif, ex: 'Facture Vente'
            $table->string('prefix')->nullable();   // préfixe du numéro (ex: 'FV-')
            $table->integer('last_number')->default(0);  // dernier numéro utilisé
            $table->integer('number_length')->default(6); // longueur du numéro (ex: 6 => 000001)
            $table->string('suffix')->nullable();   // suffixe optionnel
            $table->string('type');             // type de document (ex: 'facture_vente', 'devis', 'bon_livraison', etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('souches');
    }
}
