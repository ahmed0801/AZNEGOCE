<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPointedToDeliveryNoteLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('delivery_note_lines', function (Blueprint $table) {
        $table->boolean('pointed')->default(false);
    });
}

public function down()
{
    Schema::table('delivery_note_lines', function (Blueprint $table) {
        $table->dropColumn('pointed');
    });
}
}
