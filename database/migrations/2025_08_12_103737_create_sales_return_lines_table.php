<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up(): void
    {
        Schema::create('sales_return_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_id')->constrained('sales_returns')->onDelete('cascade');
            $table->string('article_code');
            $table->decimal('returned_quantity', 15, 2);
            $table->decimal('unit_price_ht', 15, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('total_ligne_ht', 15, 2);
            $table->timestamps();

            $table->foreign('article_code')->references('code')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_lines');
    }
}
