<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panier', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->unsignedBigInteger('entetepanier_id'); // Référence à la table entetepanier
            $table->foreign('entetepanier_id')->references('id')->on('entetepanier')->onDelete('cascade'); // Clé étrangère
            $table->string('item_reference');
            $table->string('item_name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
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
        Schema::dropIfExists('panier');
    }
}
