
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanificationsTourneesTable extends Migration
{
    public function up()
    {
        Schema::create('planifications_tournees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Chauffeur
            $table->dateTime('datetime_planifie'); // Date et heure de la tournée
            $table->string('statut')->default('planifié'); // planifié, en_cours, terminé
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planifications_tournees');
    }
}
