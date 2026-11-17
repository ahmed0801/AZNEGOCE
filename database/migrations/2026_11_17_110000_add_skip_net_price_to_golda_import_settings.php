<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkipNetPriceToGoldaImportSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('golda_import_settings', 'skip_net_price')) {
            Schema::table('golda_import_settings', function (Blueprint $table) {
                // true = les articles "prix net" ne voient pas leurs prix modifiés par l'import GOLDA
                $table->boolean('skip_net_price')->default(true);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('golda_import_settings', 'skip_net_price')) {
            Schema::table('golda_import_settings', function (Blueprint $table) {
                $table->dropColumn('skip_net_price');
            });
        }
    }
}
