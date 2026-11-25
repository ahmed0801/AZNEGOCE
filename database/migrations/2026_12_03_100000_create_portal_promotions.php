<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortalPromotions extends Migration
{
    public function up()
    {
        Schema::create('portal_promotions', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('name', 150);
            $t->text('description')->nullable();
            $t->dateTime('starts_at');
            $t->dateTime('ends_at');
            $t->boolean('active')->default(true);
            $t->string('audience', 10)->default('all');      // all | groups
            $t->timestamps();
            $t->index(['active', 'starts_at', 'ends_at']);
        });

        Schema::create('portal_promotion_groups', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('promotion_id');
            $t->unsignedBigInteger('discount_group_id');
            $t->unique(['promotion_id', 'discount_group_id'], 'ppg_unique');
        });

        Schema::create('portal_promotion_items', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('promotion_id');
            $t->unsignedBigInteger('item_id');
            $t->string('mode', 8)->default('price');          // price (prix HT fixe) | percent (% sur le tarif)
            $t->decimal('promo_price', 12, 4)->nullable();
            $t->decimal('percent', 5, 2)->nullable();
            $t->unsignedInteger('max_qty')->nullable();       // null = illimité
            $t->unsignedInteger('sold_qty')->default(0);
            $t->timestamps();
            $t->unique(['promotion_id', 'item_id'], 'ppi_unique');
            $t->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_promotion_items');
        Schema::dropIfExists('portal_promotion_groups');
        Schema::dropIfExists('portal_promotions');
    }
}
