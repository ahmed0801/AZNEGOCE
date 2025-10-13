<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDeliveryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_delivery_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('delivery_note_id')->nullable();
            $table->unsignedBigInteger('sales_return_id')->nullable();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('delivery_note_id')->references('id')->on('delivery_notes')->onDelete('cascade');
            $table->foreign('sales_return_id')->references('id')->on('sales_returns')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_delivery_notes');
    }
}
