<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddExpedieToStatusInDeliveryNotes extends Migration
{
    public function up()
    {
        // Mettre à jour les valeurs non valides ou NULL
        DB::table('delivery_notes')
            ->whereNotIn('status', ['en_attente', 'en_cours', 'livré', 'expédié', 'annulé'])
            ->orWhereNull('status')
            ->update(['status' => 'en_cours']);

        // Modifier l'ENUM pour ajouter "expédié"
        DB::statement("
            ALTER TABLE delivery_notes 
            MODIFY COLUMN status ENUM('en_attente', 'en_cours', 'livré', 'expédié', 'annulé') 
            DEFAULT 'en_cours'
        ");
    }

    public function down()
    {
        // Remplacer "expédié" par "en_cours"
        DB::table('delivery_notes')
            ->where('status', 'expédié')
            ->update(['status' => 'en_cours']);

        // Revenir à l'ENUM sans "expédié"
        DB::statement("
            ALTER TABLE delivery_notes 
            MODIFY COLUMN status ENUM('en_attente', 'en_cours', 'livré', 'annulé') 
            DEFAULT 'en_cours'
        ");
    }
}
