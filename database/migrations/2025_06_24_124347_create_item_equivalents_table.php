<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemEquivalentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::create('item_equivalents', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Juste les colonnes d'identifiants
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('equivalent_item_id');

            $table->text('reason')->nullable();
            $table->timestamps();

            // Optionnel : éviter les doublons
            $table->unique(['item_id', 'equivalent_item_id']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('item_equivalents');
    }
}
