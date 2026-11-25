<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PromotionItemsAmount extends Migration
{
    public function up()
    {
        Schema::table('portal_promotion_items', function (Blueprint $t) {
            $t->decimal('amount', 12, 4)->nullable()->after('percent');   // remise en € HT sur le tarif (mode « amount »)
        });
    }

    public function down()
    {
        Schema::table('portal_promotion_items', function (Blueprint $t) {
            $t->dropColumn('amount');
        });
    }
}
