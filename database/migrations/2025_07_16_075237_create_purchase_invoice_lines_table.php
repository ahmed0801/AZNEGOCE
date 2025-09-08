<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('purchase_invoice_lines', function (Blueprint $table) {
    $table->id();
    $table->foreignId('purchase_invoice_id')->constrained()->onDelete('cascade');
    $table->string('article_code')->nullable(); // Nullable pour factures libres
    $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('restrict');
    $table->decimal('quantity', 15, 2);
    $table->decimal('unit_price_ht', 15, 2);
    $table->decimal('remise', 5, 2)->default(0);
    $table->decimal('total_ligne_ht', 15, 2);
    $table->decimal('tva', 5, 2); // TVA figée par ligne
    $table->decimal('prix_ttc', 15, 2);
    $table->string('description')->nullable(); // Pour factures libres
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_invoice_lines');
    }
}
