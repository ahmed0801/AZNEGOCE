<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
      public function up()
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->foreignId('reception_id')->nullable()->constrained('receptions')->onDelete('set null');
            $table->date('invoice_date');
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->enum('payment_status', ['impayée', 'partielle', 'payée'])->default('impayée');
            $table->timestamps();
        });

        Schema::create('purchase_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_invoice_id')->constrained('purchase_invoices')->onDelete('cascade');
            $table->string('article_code');
            $table->integer('quantity');
            $table->decimal('unit_price_ht', 10, 2);
            $table->decimal('remise', 5, 2)->default(0);
            $table->decimal('tva_rate', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('article_code')->references('code')->on('items');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_invoice_lines');
        Schema::dropIfExists('purchase_invoices');
    }
}
