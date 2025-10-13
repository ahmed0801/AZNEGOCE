<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Index simples pour les filtres et recherches LIKE
            $table->index('code');
            $table->index('name');
            $table->index('barcode');
            $table->index('brand_id');
            $table->index('codefournisseur');

            // Index full-text pour les recherches textuelles optimisées
            $table->fullText(['name', 'code', 'barcode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Suppression des index en cas de rollback
            $table->dropIndex(['code']);
            $table->dropIndex(['name']);
            $table->dropIndex(['barcode']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['codefournisseur']);

            // Suppression de l'index full-text
            $table->dropFullText(['name', 'code', 'barcode']);
        });
    }
};