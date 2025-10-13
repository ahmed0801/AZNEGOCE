<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreatePurchaseNotesTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('numdoc')->unique();
            $table->date('note_date');
            $table->string('status')->default('brouillon');
            $table->string('type')->default('direct');
            $table->decimal('total_ht', 10, 2)->default(0);
            $table->decimal('total_ttc', 10, 2)->default(0);
            $table->decimal('tva_rate', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('purchase_return_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_notes');
    }
}