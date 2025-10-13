<?php

use App\Models\ParametresAchat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametresAchatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametres_achat', function (Blueprint $table) {
            $table->id();
            $table->boolean('reception_obligatoire_validation')->default(0);
            $table->boolean('reception_obligatoire_retour')->default(1);
            $table->timestamps();
        });

        // Insérer les valeurs par défaut
        ParametresAchat::create([
            'reception_obligatoire_validation' => 0,
            'reception_obligatoire_retour' => 1,
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('parametres_achat');
    }
}
