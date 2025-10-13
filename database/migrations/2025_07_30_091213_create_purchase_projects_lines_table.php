<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProjectsLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('purchase_project_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_project_id')->constrained()->onDelete('cascade');
            $table->string('article_code');
            $table->integer('ordered_quantity');
            $table->decimal('unit_price_ht', 15, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('tva', 5, 2);
            $table->decimal('total_ligne_ht', 15, 2);
            $table->decimal('prix_ttc', 15, 2);
            $table->foreign('article_code')->references('code')->on('items')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_project_lines');
    }
}
