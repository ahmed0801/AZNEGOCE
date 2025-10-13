<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_notes', function (Blueprint $table) {
            $table->id();
            $table->string('numdoc')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->date('note_date');
            $table->date('due_date')->nullable();
            $table->string('status')->default('brouillon'); // brouillon, validée
            $table->boolean('paid')->default(false);
            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            $table->decimal('tva_rate', 5, 2);
            $table->text('notes')->nullable();
            $table->string('type'); // from_return, from_invoice
            $table->unsignedBigInteger('sales_invoice_id')->nullable();
            $table->unsignedBigInteger('sales_return_id')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
            $table->foreign('sales_invoice_id')->references('id')->on('invoices')->onDelete('set null');
            $table->foreign('sales_return_id')->references('id')->on('sales_returns')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_notes');
    }
}
