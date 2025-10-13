
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInPurchaseOrdersAndDeliveryNotes extends Migration
{
    public function up()
    {
        // Mettre à jour les valeurs non valides ou NULL dans purchase_orders
        DB::table('purchase_orders')
            ->whereNotIn('status', ['brouillon', 'validée', 'clôturée'])
            ->orWhereNull('status')
            ->update(['status' => 'brouillon']);

        // Modifier la colonne status dans purchase_orders pour inclure 'reçu'
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('brouillon', 'validée', 'clôturée', 'reçu') DEFAULT 'brouillon'");

        // Mettre à jour les valeurs non valides ou NULL dans delivery_notes
        DB::table('delivery_notes')
            ->whereNotIn('status', ['en_attente', 'en_cours', 'annulé'])
            ->orWhereNull('status')
            ->update(['status' => 'en_cours']);

        // Modifier la colonne status dans delivery_notes pour inclure 'livré'
        DB::statement("ALTER TABLE delivery_notes MODIFY COLUMN status ENUM('en_attente', 'en_cours', 'livré', 'annulé') DEFAULT 'en_cours'");
    }

    public function down()
    {
        // Remplacer 'reçu' par 'brouillon' dans purchase_orders
        DB::table('purchase_orders')
            ->where('status', 'reçu')
            ->update(['status' => 'brouillon']);

        // Revenir aux valeurs ENUM d'origine pour purchase_orders
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('brouillon', 'validée', 'clôturée') DEFAULT 'brouillon'");

        // Remplacer 'livré' par 'en_cours' dans delivery_notes
        DB::table('delivery_notes')
            ->where('status', 'livré')
            ->update(['status' => 'en_cours']);

        // Revenir aux valeurs ENUM d'origine pour delivery_notes
        DB::statement("ALTER TABLE delivery_notes MODIFY COLUMN status ENUM('en_attente', 'en_cours', 'annulé') DEFAULT 'en_cours'");
    }
}
