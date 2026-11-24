<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Groupes remise : un type « article » ou « client ».
 *  - tous les groupes existants deviennent « article » (comportement ERP inchangé) ;
 *  - un groupe utilisé UNIQUEMENT par des clients passe « client » ;
 *  - un groupe utilisé par des articles ET des clients (ex. R0) est dupliqué : l'original reste « article »,
 *    la copie « <nom> (client) » est de type « client » et les clients y sont rattachés (mêmes taux).
 */
class AddTypeToDiscountGroups extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('discount_groups', 'type')) {
            Schema::table('discount_groups', function (Blueprint $table) {
                $table->string('type', 10)->default('article')->after('name');
            });
        }

        DB::transaction(function () {
            foreach (DB::table('discount_groups')->get() as $g) {
                $nbCust  = DB::table('customers')->where('discount_group_id', $g->id)->count();
                if ($nbCust === 0) continue;
                $nbItems = DB::table('items')->where('discount_group_id', $g->id)->count();

                if ($nbItems === 0) {
                    DB::table('discount_groups')->where('id', $g->id)->update(['type' => 'client']);
                    continue;
                }

                $copy = (array) $g;
                unset($copy['id']);
                $copy['name']       = $g->name . ' (client)';
                $copy['type']       = 'client';
                $copy['created_at'] = now();
                $copy['updated_at'] = now();
                $newId = DB::table('discount_groups')->insertGetId($copy);
                DB::table('customers')->where('discount_group_id', $g->id)->update(['discount_group_id' => $newId]);
            }
        });
    }

    public function down()
    {
        // Les copies « (client) » restent en place : seul le champ est retiré.
        if (Schema::hasColumn('discount_groups', 'type')) {
            Schema::table('discount_groups', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
}
