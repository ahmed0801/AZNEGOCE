<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('purchase_invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
    $table->string('numdoc')->unique();
    $table->date('invoice_date');
    $table->string('status')->default('brouillon'); // brouillon, validée
    $table->decimal('total_ht', 15, 2)->default(0);
    $table->decimal('total_ttc', 15, 2)->default(0);
    $table->decimal('tva_rate', 5, 2); // Taux de TVA figé
    $table->text('notes')->nullable();
    $table->string('type'); // direct, groupée, libre
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
        Schema::dropIfExists('purchase_invoices');
    }
}
