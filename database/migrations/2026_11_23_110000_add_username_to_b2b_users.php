<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Nom d'utilisateur de connexion au portail (en plus de l'e-mail). */
class AddUsernameToB2bUsers extends Migration
{
    public function up()
    {
        Schema::table('b2b_users', function (Blueprint $table) {
            $table->string('username', 60)->nullable()->unique()->after('email');
        });

        // comptes déjà créés : nom d'utilisateur = partie de l'e-mail avant @ (suffixe numérique si doublon)
        $used = [];
        foreach (DB::table('b2b_users')->orderBy('id')->get(['id', 'email']) as $u) {
            $base = preg_replace('/[^a-z0-9._-]/', '', strtolower(strstr($u->email, '@', true) ?: $u->email));
            if (strlen($base) < 3) $base = 'client' . $u->id;
            $name = $base; $i = 1;
            while (isset($used[$name])) { $i++; $name = $base . $i; }
            $used[$name] = true;
            DB::table('b2b_users')->where('id', $u->id)->update(['username' => $name]);
        }
    }

    public function down()
    {
        Schema::table('b2b_users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
}
