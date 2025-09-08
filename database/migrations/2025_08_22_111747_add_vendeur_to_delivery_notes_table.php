<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendeurToDeliveryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->string('vendeur')->nullable()->after('numdoc');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->dropColumn('vendeur');
        });
    }
}
