<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->date('reception_date');
            $table->enum('status', ['en_cours', 'reçu', 'partiel'])->default('en_cours');
            $table->decimal('total_received', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('reception_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->constrained('receptions')->onDelete('cascade');
            $table->string('article_code');
            $table->integer('received_quantity');
            $table->timestamps();

            $table->foreign('article_code')->references('code')->on('items');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reception_lines');
        Schema::dropIfExists('receptions');
    }
}
