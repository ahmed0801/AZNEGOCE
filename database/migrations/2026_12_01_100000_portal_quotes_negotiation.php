<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Devis portail : renégociation, historique des échanges, devis ERP visibles par le client (+ « vu »). */
class PortalQuotesNegotiation extends Migration
{
    public function up()
    {
        Schema::create('portal_quote_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id')->index();
            $table->string('actor', 8);                         // client | vendor
            $table->string('type', 20);                         // created | answered | negotiate | accepted | declined | cancelled
            $table->text('message')->nullable();
            $table->string('who', 120)->nullable();
            $table->timestamp('created_at')->nullable();
        });
        Schema::table('portal_quote_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('portal_quote_lines', 'target_price')) $table->decimal('target_price', 12, 4)->nullable();
            if (!Schema::hasColumn('portal_quote_lines', 'client_comment')) $table->string('client_comment', 255)->nullable();
        });
        Schema::table('portal_quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('portal_quotes', 'round')) $table->unsignedTinyInteger('round')->default(1);
        });
        Schema::table('b2b_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('b2b_settings', 'portal_show_erp_quotes')) $table->boolean('portal_show_erp_quotes')->default(true);
        });
        Schema::create('portal_erp_quote_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_order_id')->unique();
            $table->unsignedBigInteger('customer_id')->index();
            $table->timestamp('viewed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_erp_quote_views');
        Schema::dropIfExists('portal_quote_events');
        Schema::table('portal_quote_lines', function (Blueprint $table) {
            foreach (['target_price', 'client_comment'] as $c) if (Schema::hasColumn('portal_quote_lines', $c)) $table->dropColumn($c);
        });
        Schema::table('portal_quotes', function (Blueprint $table) { if (Schema::hasColumn('portal_quotes', 'round')) $table->dropColumn('round'); });
        Schema::table('b2b_settings', function (Blueprint $table) { if (Schema::hasColumn('b2b_settings', 'portal_show_erp_quotes')) $table->dropColumn('portal_show_erp_quotes'); });
    }
}
