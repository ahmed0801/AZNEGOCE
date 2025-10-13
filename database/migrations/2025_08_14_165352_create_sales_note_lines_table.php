<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesNoteLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_note_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_note_id');
            $table->unsignedBigInteger('sales_invoice_id')->nullable();
            $table->unsignedBigInteger('sales_return_id')->nullable();
            $table->string('article_code')->nullable();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_price_ht', 15, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('total_ligne_ht', 15, 2);
            $table->decimal('total_ligne_ttc', 15, 2);
            $table->timestamps();

            $table->foreign('sales_note_id')->references('id')->on('sales_notes')->onDelete('cascade');
            $table->foreign('sales_invoice_id')->references('id')->on('invoices')->onDelete('set null');
            $table->foreign('sales_return_id')->references('id')->on('sales_returns')->onDelete('set null');
            $table->foreign('article_code')->references('code')->on('items')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_note_lines');
    }
}
