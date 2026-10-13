<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSubcategoriesTable extends Migration
{
    // Idempotente : relançable après une exécution partielle (MySQL ne fait pas de rollback sur le DDL)
    public function up()
    {
        if (!Schema::hasTable('item_subcategories')) {
            Schema::create('item_subcategories', function (Blueprint $table) {
                $table->id();
                $table->string('category_id', 20);
                $table->string('code', 30)->unique();          // ex: 0220 (Code_sousfamille_NU GOLDA)
                $table->string('name');
                $table->decimal('default_sale_margin', 6, 2)->nullable(); // null -> marge de la famille
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('item_categories')->onDelete('cascade');
                $table->index(['category_id', 'name']);
            });
        }

        if (!Schema::hasColumn('items', 'subcategory_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->unsignedBigInteger('subcategory_id')->nullable()->after('category_id');
                $table->index('subcategory_id');
            });
            try {
                Schema::table('items', function (Blueprint $table) {
                    $table->foreign('subcategory_id')->references('id')->on('item_subcategories')->nullOnDelete();
                });
            } catch (\Exception $e) {
                // la colonne et l'index existent ; la contrainte pourra être ajoutée à la main
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('items', 'subcategory_id')) {
            try {
                Schema::table('items', function (Blueprint $table) {
                    $table->dropForeign(['subcategory_id']);
                });
            } catch (\Exception $e) {
            }
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('subcategory_id');
            });
        }
        Schema::dropIfExists('item_subcategories');
    }
}