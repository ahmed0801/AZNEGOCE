<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvaGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tva_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // Ex: "TVA 19%", "TVA réduite", "Exonéré"
            $table->decimal('rate', 5, 2);       // Ex: 19.00, 7.00, 0.00
            $table->string('code')->unique();    // Ex: TVA19, TVA7, EXO
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tva_groups');
    }
}
