<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMagasinAndEmplacementToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->unsignedBigInteger('store_id')->nullable()->after('stock_max'); // magasin
        $table->string('location')->nullable()->after('store_id');              // emplacement
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn(['store_id', 'location']);
    });
}

}
