<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValideeAtToPlanificationsTourneesTable extends Migration
{
    public function up()
    {
        Schema::table('planifications_tournees', function (Blueprint $table) {
            $table->timestamp('validee_at')->nullable()->after('statut');
        });
    }

    public function down()
    {
        Schema::table('planifications_tournees', function (Blueprint $table) {
            $table->dropColumn('validee_at');
        });
    }
}
