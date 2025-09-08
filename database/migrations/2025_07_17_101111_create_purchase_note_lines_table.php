<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreatePurchaseNoteLinesTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_note_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_note_id')->constrained()->onDelete('cascade');
            $table->string('article_code')->nullable();
            $table->foreignId('purchase_return_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price_ht', 10, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('tva', 5, 2)->default(0);
            $table->decimal('total_ligne_ht', 10, 2);
            $table->decimal('prix_ttc', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_note_lines');
    }
}
