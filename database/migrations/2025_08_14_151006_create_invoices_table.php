<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('numdoc')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('status')->default('brouillon'); // brouillon, validée
            $table->boolean('paid')->default(false);
            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            $table->decimal('tva_rate', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
