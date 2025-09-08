
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTourneesTable extends Migration
{
    public function up()
    {
        Schema::create('actions_tournees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planification_tournee_id')->constrained('planifications_tournees')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Utilisateur ayant effectué l'action
            $table->string('type_action'); // scan, mise_a_jour_statut
            $table->string('type_document'); // commande_achat, bon_livraison
            $table->unsignedBigInteger('document_id'); // ID de la commande ou du bon
            $table->string('code_article')->nullable(); // Article scanné, si applicable
            $table->integer('quantite')->nullable(); // Quantité scannée
            $table->string('statut')->nullable(); // Nouveau statut, si applicable
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actions_tournees');
    }
}