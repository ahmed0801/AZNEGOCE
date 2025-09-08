<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreatePlanificationTourneeDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('planification_tournee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planification_tournee_id')->constrained('planifications_tournees')->onDelete('cascade');
            $table->string('document_type'); // commande_achat, bon_livraison
            $table->unsignedBigInteger('document_id'); // ID du document
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planification_tournee_documents');
    }
}