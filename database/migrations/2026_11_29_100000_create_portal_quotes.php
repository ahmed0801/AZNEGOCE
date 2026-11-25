<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Portail B2B : demandes de devis (saisie libre, véhicule, pièces) + mode du portail (complet / devis seulement). */
class CreatePortalQuotes extends Migration
{
    public function up()
    {
        Schema::table('b2b_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('b2b_settings', 'portal_mode')) $table->string('portal_mode', 10)->default('full');   // full | quotes
        });

        Schema::create('portal_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->nullable()->unique();                 // DEV-000001
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('b2b_user_id')->nullable();
            $table->string('status', 12)->default('new');                       // new | processing | answered | accepted | declined | cancelled
            $table->unsignedBigInteger('vehicle_id')->nullable();               // véhicule déjà enregistré chez le client
            $table->string('vehicle_text', 255)->nullable();                    // saisie libre ou plaque d'immatriculation
            $table->string('vin', 30)->nullable();                              // n° de châssis (facultatif)
            $table->text('note')->nullable();                                   // message du client
            $table->text('vendor_message')->nullable();                         // réponse visible par le client
            $table->text('internal_note')->nullable();
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('total_tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->unsignedInteger('lines_count')->default(0);
            $table->unsignedBigInteger('answered_by')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('decided_at')->nullable();                        // accepté / refusé par le client
            $table->date('valid_until')->nullable();                            // validité de l'offre
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::create('portal_quote_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id')->index();
            $table->unsignedBigInteger('item_id')->nullable();                  // article du catalogue si le client l'a choisi
            $table->string('reference', 80)->nullable();
            $table->string('designation', 255);
            $table->string('brand', 120)->nullable();
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('unit_ht', 12, 4)->nullable();                      // rempli par le vendeur
            $table->decimal('tva_rate', 5, 2)->default(0);
            $table->decimal('line_ht', 12, 2)->default(0);
            $table->string('availability', 12)->nullable();                     // stock | delay | unavailable
            $table->string('delay_text', 60)->nullable();                       // « sous 48 h »…
            $table->string('vendor_ref', 80)->nullable();                       // référence proposée si différente
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_quote_lines');
        Schema::dropIfExists('portal_quotes');
        Schema::table('b2b_settings', function (Blueprint $table) {
            if (Schema::hasColumn('b2b_settings', 'portal_mode')) $table->dropColumn('portal_mode');
        });
    }
}
