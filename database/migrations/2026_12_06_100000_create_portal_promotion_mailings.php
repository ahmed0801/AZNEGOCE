<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortalPromotionMailings extends Migration
{
    public function up()
    {
        Schema::create('portal_promotion_mailings', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('promotion_id')->index();
            $t->unsignedBigInteger('customer_id')->index();
            $t->string('email', 190);
            $t->string('status', 8);                 // sent | failed
            $t->string('error', 255)->nullable();
            $t->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_promotion_mailings');
    }
}
