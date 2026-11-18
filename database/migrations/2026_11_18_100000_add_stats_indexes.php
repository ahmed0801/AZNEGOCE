<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index qui accélèrent les statistiques de vente (période, jointure BL ↔ lignes, stock).
 * Chaque index est créé séparément et ignoré s'il existe déjà.
 */
class AddStatsIndexes extends Migration
{
    protected $indexes = [
        ['delivery_notes', ['delivery_date'], 'dn_delivery_date_idx'],
        ['delivery_notes', ['numclient'], 'dn_numclient_idx'],
        ['delivery_note_lines', ['delivery_note_id', 'article_code'], 'dnl_note_article_idx'],
        ['stocks', ['item_id'], 'stocks_item_id_idx'],
    ];

    public function up()
    {
        foreach ($this->indexes as $i) {
            try {
                Schema::table($i[0], function (Blueprint $t) use ($i) { $t->index($i[1], $i[2]); });
            } catch (\Throwable $e) {
                // index déjà présent (ou table absente) : on continue
            }
        }
    }

    public function down()
    {
        foreach ($this->indexes as $i) {
            try {
                Schema::table($i[0], function (Blueprint $t) use ($i) { $t->dropIndex($i[2]); });
            } catch (\Throwable $e) {
            }
        }
    }
}
