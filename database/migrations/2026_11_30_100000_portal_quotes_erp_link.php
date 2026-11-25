<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Lien entre une demande de devis du portail et le devis créé dans l'ERP (commande vente au statut « Devis »). */
class PortalQuotesErpLink extends Migration
{
    public function up()
    {
        Schema::table('portal_quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('portal_quotes', 'sales_order_id')) $table->unsignedBigInteger('sales_order_id')->nullable()->after('answered_by');
            if (!Schema::hasColumn('portal_quotes', 'erp_numdoc')) $table->string('erp_numdoc', 40)->nullable()->after('sales_order_id');
        });
    }

    public function down()
    {
        Schema::table('portal_quotes', function (Blueprint $table) {
            foreach (['sales_order_id', 'erp_numdoc'] as $c) if (Schema::hasColumn('portal_quotes', $c)) $table->dropColumn($c);
        });
    }
}
