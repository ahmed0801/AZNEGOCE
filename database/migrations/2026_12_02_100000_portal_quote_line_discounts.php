<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Devis portail : tarif HT + remise % par ligne (comme dans l'ERP). unit_ht reste le prix net unitaire. */
class PortalQuoteLineDiscounts extends Migration
{
    public function up()
    {
        Schema::table('portal_quote_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('portal_quote_lines', 'list_ht')) $table->decimal('list_ht', 12, 4)->nullable();
            if (!Schema::hasColumn('portal_quote_lines', 'remise')) $table->decimal('remise', 5, 2)->default(0);
        });
        // lignes existantes : tarif = ancien prix, sans remise
        \DB::table('portal_quote_lines')->whereNull('list_ht')->whereNotNull('unit_ht')->update(['list_ht' => \DB::raw('unit_ht')]);
    }

    public function down()
    {
        Schema::table('portal_quote_lines', function (Blueprint $table) {
            foreach (['list_ht', 'remise'] as $c) if (Schema::hasColumn('portal_quote_lines', $c)) $table->dropColumn($c);
        });
    }
}
