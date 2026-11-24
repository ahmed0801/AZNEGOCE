<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Lien entre une commande portail et les documents créés dans l'ERP (commande vente, BL, facture). */
class AddErpLinkToPortalOrders extends Migration
{
    public function up()
    {
        Schema::table('portal_orders', function (Blueprint $table) {
            $table->string('erp_numdoc', 40)->nullable()->after('erp_document_id');          // n° de la commande vente
            $table->unsignedBigInteger('erp_delivery_note_id')->nullable()->after('erp_numdoc');
            $table->unsignedBigInteger('erp_invoice_id')->nullable()->after('erp_delivery_note_id');
            $table->string('erp_invoice_numdoc', 40)->nullable()->after('erp_invoice_id');
            $table->text('erp_error')->nullable()->after('erp_invoice_numdoc');              // dernière erreur de création ERP
        });
    }

    public function down()
    {
        Schema::table('portal_orders', function (Blueprint $table) {
            $table->dropColumn(['erp_numdoc', 'erp_delivery_note_id', 'erp_invoice_id', 'erp_invoice_numdoc', 'erp_error']);
        });
    }
}
