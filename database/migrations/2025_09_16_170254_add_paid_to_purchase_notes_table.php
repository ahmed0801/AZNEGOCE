<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToPurchaseNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_notes', function (Blueprint $table) {
            $table->boolean('paid')->default(false)->after('status')->comment('Indicates if the purchase note is fully paid');
        });
    }

    public function down()
    {
        Schema::table('purchase_notes', function (Blueprint $table) {
            $table->dropColumn('paid');
        });
    }
}
