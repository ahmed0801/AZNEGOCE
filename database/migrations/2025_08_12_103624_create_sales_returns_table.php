<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
Schema::create('sales_returns', function (Blueprint $table) {
    $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT
    $table->foreignId('delivery_note_id')->constrained()->onDelete('cascade');
    $table->foreignId('customer_id')->constrained()->onDelete('restrict');
    $table->string('numdoc')->unique();
    $table->dateTime('return_date');
    $table->enum('type', ['total', 'partiel']);
    $table->decimal('total_ht', 15, 2)->default(0);
    $table->decimal('total_ttc', 15, 2)->default(0);
    $table->decimal('tva_rate', 5, 2)->default(0);
    $table->text('notes')->nullable();
    $table->boolean('invoiced')->default(false);
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('sales_returns');
    }
}
