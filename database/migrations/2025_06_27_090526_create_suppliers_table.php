<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // Code client unique
        $table->string('name');
        $table->string('email')->nullable();
        $table->string('phone1')->nullable();
        $table->string('phone2')->nullable();
        $table->string('address')->nullable();
        $table->string('address_delivery')->nullable();
        $table->string('city')->nullable();
        $table->string('matfiscal')->nullable();
        $table->string('bank_no')->nullable();
        $table->string('country')->nullable();
        $table->decimal('solde', 12, 2)->default(0);
        $table->decimal('plafond', 12, 2)->default(0);
        $table->string('risque')->nullable();
        $table->foreignId('tva_group_id')->nullable()->constrained('tva_groups')->nullOnDelete()->default(0.00);
        $table->foreignId('discount_group_id')->nullable()->constrained('discount_groups')->nullOnDelete();
            $table->foreignId('payment_mode_id')->nullable()->constrained('payment_modes')->nullOnDelete();
    $table->foreignId('payment_term_id')->nullable()->constrained('payment_terms')->nullOnDelete();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
