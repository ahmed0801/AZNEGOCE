<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultSaleMarginToItemCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('item_categories', function (Blueprint $table) {
            $table->decimal('default_sale_margin', 5, 2)->default(30.00)->after('description');
            // 30.00% par défaut
        });
    }

    public function down()
    {
        Schema::table('item_categories', function (Blueprint $table) {
            $table->dropColumn('default_sale_margin');
        });
    }
}
