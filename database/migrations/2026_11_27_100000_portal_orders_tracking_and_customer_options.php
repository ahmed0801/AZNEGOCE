<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * - réception autorisée par client (livraison / retrait / les deux) + accès à la situation financière ;
 * - suivi des modifications faites par le vendeur sur une commande avant acceptation (quantités, prix, lignes retirées).
 */
class PortalOrdersTrackingAndCustomerOptions extends Migration
{
    public function up()
    {
        Schema::table('customer_b2b_settings', function (Blueprint $table) {
            $table->string('fulfillment', 10)->default('both')->after('validation_mode');   // delivery | pickup | both
            $table->boolean('show_finance')->default(true)->after('show_prices');
        });
        Schema::table('portal_order_lines', function (Blueprint $table) {
            $table->unsignedInteger('orig_qty')->nullable();
            $table->decimal('orig_unit_ht', 12, 4)->nullable();
            $table->boolean('removed')->default(false);
        });
        Schema::table('portal_orders', function (Blueprint $table) {
            $table->boolean('modified')->default(false);
            $table->string('vendor_message', 500)->nullable();
            $table->decimal('orig_total_ttc', 12, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('portal_orders', function (Blueprint $table) { $table->dropColumn(['modified', 'vendor_message', 'orig_total_ttc']); });
        Schema::table('portal_order_lines', function (Blueprint $table) { $table->dropColumn(['orig_qty', 'orig_unit_ht', 'removed']); });
        Schema::table('customer_b2b_settings', function (Blueprint $table) { $table->dropColumn(['fulfillment', 'show_finance']); });
    }
}
