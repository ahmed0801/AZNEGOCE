<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('delivery_note_id')->nullable();
            $table->unsignedBigInteger('sales_return_id')->nullable();
            $table->string('article_code');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price_ht', 15, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('total_ligne_ht', 15, 2);
            $table->decimal('total_ligne_ttc', 15, 2);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('delivery_note_id')->references('id')->on('delivery_notes')->onDelete('set null');
            $table->foreign('sales_return_id')->references('id')->on('sales_returns')->onDelete('set null');
            $table->foreign('article_code')->references('code')->on('items')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_lines');
    }
}
