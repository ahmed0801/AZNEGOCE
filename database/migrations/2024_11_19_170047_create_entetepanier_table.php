<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntetepanierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entetepanier', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('user_id'); // Identifiant client (ex: CL0025)
            $table->string('status')->default('en attente'); // Statut du panier
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
        Schema::dropIfExists('entetepanier');
    }
}
