<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('name');      // Nom utilisateur
        $table->string('email');     // Email utilisateur
        $table->string('subject');   // Objet
        $table->text('message');     // Description du problème
        $table->enum('status', ['Ouvert', 'En cours', 'Clôturé'])->default('Ouvert');
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
        Schema::dropIfExists('tickets');
    }
}
