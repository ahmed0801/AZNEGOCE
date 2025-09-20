<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->string('messagefacturevente')->default("Veuillez trouver ci-joint votre facture de vente.");
$table->string('messagefactureachat')->default("Veuillez trouver ci-joint votre facture d\'achat.");
$table->string('messageavoirvente')->default("Veuillez trouver ci-joint votre avoir de vente.");
$table->string('messageavoirachat')->default("Veuillez trouver ci-joint votre avoir d\'achat.");
$table->string('messagedeliverynote')->default("Veuillez trouver ci-joint votre bon de livraison.");
$table->string('messageconfirmationdelivraison')->default("Confirmation de votre livraison.");
$table->string('messagedemanderetourachat')->default("Demande de retour achat.");

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
        Schema::dropIfExists('email_messages');
    }
}
