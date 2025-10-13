<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteLinesTable extends Migration
{
    public function up()
    {
        Schema::create('quote_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->string('article_code');
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_price_ht', 15, 2);
            $table->decimal('unit_price_ttc', 15, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('total_ligne_ht', 15, 2);
            $table->decimal('total_ligne_ttc', 15, 2);
            $table->foreign('article_code')->references('code')->on('items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quote_lines');
    }
}