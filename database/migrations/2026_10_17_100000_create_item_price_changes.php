<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Historique des changements de prix de vente décidés depuis l'assistant d'achat (traçabilité + retour arrière). */
class CreateItemPriceChanges extends Migration
{
    public function up()
    {
        if (Schema::hasTable('item_price_changes')) return;
        Schema::create('item_price_changes', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('item_id')->index();
            $t->string('article_code', 60)->index();
            $t->decimal('old_sale_price', 12, 2)->default(0);
            $t->decimal('new_sale_price', 12, 2)->default(0);
            $t->decimal('net_cost', 12, 4)->nullable();
            $t->string('strategy', 30)->nullable();
            $t->string('source', 40)->default('achat');
            $t->unsignedBigInteger('user_id')->nullable();
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_price_changes');
    }
}
