<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountGroupIdToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('discount_group_id')
                  ->nullable()                    // Peut être null (pas de groupe assigné)
                  ->default(1)                    // Par défaut, on assigne le groupe ID 1
                  ->constrained('discount_groups') // Clé étrangère vers discount_groups
                  ->onDelete('set null');         // Si on supprime le groupe, met à null
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['discount_group_id']);
            $table->dropColumn('discount_group_id');
        });
    }
}
