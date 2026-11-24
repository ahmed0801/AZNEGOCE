<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Portail B2B : commandes passées par les clients (photo figée des prix au moment de la commande). */
class CreatePortalOrders extends Migration
{
    public function up()
    {
        Schema::create('portal_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->nullable()->unique();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('b2b_user_id')->nullable();
            $table->string('status', 20)->default('pending');            // pending | validated | refused | cancelled
            $table->string('delivery_mode', 12)->default('delivery');    // delivery | pickup
            $table->string('po_ref', 60)->nullable();                    // référence de commande du client
            $table->text('note')->nullable();                            // message du client
            $table->text('internal_note')->nullable();                   // note interne (vendeur)
            $table->string('refuse_reason', 255)->nullable();
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('total_tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->unsignedInteger('lines_count')->default(0);
            $table->unsignedInteger('units')->default(0);
            $table->decimal('customer_solde', 14, 2)->nullable();        // situation du compte au moment de la commande
            $table->decimal('customer_plafond', 14, 2)->nullable();
            $table->boolean('auto_validated')->default(false);
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->string('erp_document_type', 30)->nullable();         // renseigné à la transformation en document ERP
            $table->unsignedBigInteger('erp_document_id')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::create('portal_order_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('item_id');
            $table->string('code', 80);
            $table->string('name', 255);
            $table->string('brand', 120)->nullable();
            $table->unsignedInteger('qty');
            $table->decimal('list_ht', 12, 4)->default(0);
            $table->decimal('discount', 6, 2)->default(0);
            $table->decimal('unit_ht', 12, 4)->default(0);
            $table->decimal('tva_rate', 5, 2)->default(0);
            $table->decimal('line_ht', 12, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_order_lines');
        Schema::dropIfExists('portal_orders');
    }
}
