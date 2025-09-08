<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoiceLinesTable extends Migration
{
    public function up()
    {
        Schema::create('sales_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->constrained()->onDelete('cascade');
            $table->string('article_code')->nullable();
            $table->foreignId('sales_order_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_price_ht', 15, 2);
            $table->decimal('unit_price_ttc', 15, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('total_ligne_ht', 15, 2);
            $table->decimal('total_ligne_ttc', 15, 2);
            $table->decimal('tva', 5, 2);
            $table->text('description')->nullable();
            $table->foreign('article_code')->references('code')->on('items')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_invoice_lines');
    }
}