<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Portail B2B : références favorites (par client) et panier (par utilisateur). */
class CreatePortalCartFavorites extends Migration
{
    public function up()
    {
        Schema::create('portal_favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('item_id');
            $table->timestamp('created_at')->nullable();
            $table->unique(['customer_id', 'item_id']);
        });

        Schema::create('portal_cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('b2b_user_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();
            $table->unique(['b2b_user_id', 'item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_cart_items');
        Schema::dropIfExists('portal_favorites');
    }
}
