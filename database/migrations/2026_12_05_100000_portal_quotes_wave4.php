<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PortalQuotesWave4 extends Migration
{
    public function up()
    {
        Schema::create('portal_quote_files', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('quote_id')->index();
            $t->string('by', 10)->default('client');            // client | vendor
            $t->string('who', 120)->nullable();
            $t->string('name', 190);                            // nom affiché (nettoyé)
            $t->string('path', 255);                            // chemin dans storage/app (jamais public)
            $t->string('mime', 80);
            $t->unsignedInteger('size')->default(0);
            $t->timestamp('created_at')->nullable();
        });

        Schema::table('portal_quote_lines', function (Blueprint $t) {
            $t->boolean('accepted')->nullable()->after('client_comment');   // null = pas encore décidé, 1 = gardée, 0 = refusée par le client
        });

        Schema::table('portal_quotes', function (Blueprint $t) {
            $t->string('plate', 20)->nullable()->after('vin');
            $t->decimal('offered_ht', 12, 2)->nullable();       // total proposé avant refus de lignes
            $t->decimal('offered_ttc', 12, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('portal_quotes', function (Blueprint $t) { $t->dropColumn(['plate', 'offered_ht', 'offered_ttc']); });
        Schema::table('portal_quote_lines', function (Blueprint $t) { $t->dropColumn('accepted'); });
        Schema::dropIfExists('portal_quote_files');
    }
}
