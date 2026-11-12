<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddArticleCodeIndexesForCompanions extends Migration
{
    private $targets = [
        'delivery_note_lines' => 'dnl_article_code_idx',
    ];

    public function up()
    {
        foreach ($this->targets as $table => $name) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'article_code')) continue;
            $exists = DB::select("SHOW INDEX FROM `$table` WHERE Column_name = 'article_code'");
            if (!empty($exists)) continue;
            DB::statement("ALTER TABLE `$table` ADD INDEX `$name` (`article_code`)");
        }
    }

    public function down()
    {
        foreach ($this->targets as $table => $name) {
            if (!Schema::hasTable($table)) continue;
            $has = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = '$name'");
            if (!empty($has)) DB::statement("ALTER TABLE `$table` DROP INDEX `$name`");
        }
    }
}
