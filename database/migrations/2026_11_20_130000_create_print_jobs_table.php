<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** File d'impression (lue par l'agent installé au magasin) + jeton de l'agent. */
class CreatePrintJobsTable extends Migration
{
    public function up()
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->default('order');          // test | order | delivery_note
            $table->string('title', 150)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable(); // ex : id de la commande
            $table->string('printer_ip', 45);
            $table->unsignedSmallInteger('printer_port')->default(9100);
            $table->unsignedTinyInteger('copies')->default(1);
            $table->longText('payload');                            // octets ESC/POS en base64
            $table->string('status', 12)->default('pending');       // pending | printing | done | failed
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('error', 255)->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'id']);
        });

        Schema::table('b2b_settings', function (Blueprint $table) {
            $table->string('agent_token', 64)->nullable();
            $table->timestamp('agent_last_seen')->nullable();
        });
    }

    public function down()
    {
        Schema::table('b2b_settings', function (Blueprint $table) {
            $table->dropColumn(['agent_token', 'agent_last_seen']);
        });
        Schema::dropIfExists('print_jobs');
    }
}
