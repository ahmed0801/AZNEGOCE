<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable'); // Polymorphic: links to Invoice or PurchaseInvoice
            $table->unsignedBigInteger('customer_id')->nullable(); // Optional link to customer
            $table->unsignedBigInteger('supplier_id')->nullable(); // Optional link to supplier
            $table->decimal('amount', 10, 2); // Payment amount in TND
            $table->date('payment_date'); // Date of payment
            $table->string('payment_mode'); // e.g., virement, chèque, espèces
            $table->string('reference')->nullable(); // Payment reference (e.g., cheque number)
            $table->string('lettrage_code')->nullable()->index(); // Lettrage code for accounting
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
