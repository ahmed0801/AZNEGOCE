<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasB2bToSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('has_b2b')->default(false)->after('code');
            $table->string('b2b_url')->nullable()->after('has_b2b'); // optionnel : pour stocker le lien
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['has_b2b', 'b2b_url']);
        });
    }
}
