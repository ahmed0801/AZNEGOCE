<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryNotesTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('numclient')->nullable();
            $table->date('delivery_date');
            $table->string('status')->default('en_cours');
            $table->decimal('total_delivered', 15, 2)->default(0);
            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            $table->decimal('tva_rate', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('numdoc')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_notes');
    }
}