<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnLinesTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_return_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_return_id')->constrained()->onDelete('cascade');
            $table->string('article_code');
            $table->foreign('article_code')->references('code')->on('items')->onDelete('restrict');
            $table->decimal('returned_quantity', 10, 2);
            $table->decimal('unit_price_ht', 10, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('total_ligne_ht', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_return_lines');
    }
}