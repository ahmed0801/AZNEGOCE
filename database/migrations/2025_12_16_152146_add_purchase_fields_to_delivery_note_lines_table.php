<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseFieldsToDeliveryNoteLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('delivery_note_lines', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->decimal('unit_coast', 10, 2)->nullable()->comment('Prix d\'achat HT unitaire réel');
            $table->decimal('discount_coast', 5, 2)->nullable()->default(0)->comment('Remise achat en %');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_note_lines', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['supplier_id', 'unit_coast', 'discount_coast']);
        });
    }
}
