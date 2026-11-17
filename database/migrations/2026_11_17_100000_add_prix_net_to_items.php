<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrixNetToItems extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('items', 'prix_net')) {
            Schema::table('items', function (Blueprint $table) {
                // 0 = article normal ; 1 = prix net (prix protégés des imports Excel / GOLDA)
                $table->boolean('prix_net')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('items', 'prix_net')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('prix_net');
            });
        }
    }
}
