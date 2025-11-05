<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('type', ['particulier', 'jobber', 'professionnel'])
                  ->default('particulier')
                  ->after('name'); // ou après un autre champ
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
