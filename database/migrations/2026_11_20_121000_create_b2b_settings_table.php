<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Paramètres du portail B2B (une seule ligne). */
class CreateB2bSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('b2b_settings', function (Blueprint $table) {
            $table->id();

            // Statut
            $table->boolean('portal_enabled')->default(false);
            $table->string('maintenance_message', 255)->nullable();

            // Identité affichée sur le portail
            $table->string('company_name', 150)->nullable();
            $table->string('tagline', 200)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->string('primary_color', 7)->default('#1E2D4A');
            $table->text('welcome_message')->nullable();

            // Coordonnées
            $table->string('phone', 50)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 150)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('opening_hours', 255)->nullable();
            $table->string('legal_footer', 255)->nullable();

            // Commandes
            $table->boolean('auto_validate')->default(false);          // false = un vendeur valide chaque commande
            $table->decimal('min_order_amount', 12, 2)->default(0);
            $table->boolean('block_over_credit')->default(true);       // refuse la commande si le plafond client est dépassé
            $table->boolean('allow_particulier')->default(false);

            // Stock & prix
            $table->boolean('show_prices')->default(true);
            $table->boolean('show_stock')->default(true);
            $table->string('stock_display', 12)->default('threshold'); // exact | threshold | hidden
            $table->unsignedInteger('stock_low_threshold')->default(5);

            // Impression automatique
            $table->boolean('auto_print')->default(false);
            $table->string('print_document', 20)->default('order');    // order | delivery_note
            $table->string('printer_name', 100)->nullable();
            $table->string('printer_ip', 45)->nullable();
            $table->unsignedSmallInteger('printer_port')->default(9100);
            $table->string('paper_width', 10)->default('80mm');        // 80mm | 58mm | A4
            $table->unsignedTinyInteger('print_copies')->default(1);

            // Notifications
            $table->boolean('notify_new_order')->default(true);
            $table->string('notification_email', 150)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('b2b_settings');
    }
}
