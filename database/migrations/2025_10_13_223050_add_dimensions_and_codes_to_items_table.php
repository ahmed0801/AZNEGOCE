<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('Poids', 10, 3)->nullable()->after('cost_price');
            $table->decimal('Hauteur', 10, 3)->nullable()->after('Poids');
            $table->decimal('Longueur', 10, 3)->nullable()->after('Hauteur');
            $table->decimal('Largeur', 10, 3)->nullable()->after('Longueur');
            $table->string('Ref_TecDoc', 100)->nullable()->after('Largeur');
            $table->string('Code_pays', 10)->nullable()->after('Ref_TecDoc');
            $table->string('Code_douane', 50)->nullable()->after('Code_pays');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['Poids', 'Hauteur', 'Longueur', 'Largeur', 'Ref_TecDoc', 'Code_pays', 'Code_douane']);
        });
    }
};
