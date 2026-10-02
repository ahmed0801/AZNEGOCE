<?php
// Migration : php artisan make:migration create_golda_import_settings_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('golda_import_settings', function (Blueprint $table) {
            $table->id();
            $table->string('code_marque')->unique(); // contient "FRAM|SOG" ou "DRI|LUF"
            $table->string('prefixe_tarif')->nullable(); // ← nullable
            $table->string('nom_marque');              // nom lisible
            $table->unsignedBigInteger('brand_id')->nullable(); // lien Brand
            $table->boolean('active')->default(true);  // inclus dans import
            $table->boolean('update_cost_price')->default(true);    // màj prix achat
            $table->boolean('update_sale_price')->default(false);   // màj prix vente
            $table->boolean('update_description')->default(false);  // màj désignation
            $table->boolean('update_barcode')->default(false);      // màj EAN
            $table->boolean('update_dimensions')->default(false);   // màj poids/dim
            $table->boolean('create_new_items')->default(true);     // créer nouveaux articles
            $table->boolean('deactivate_removed')->default(true);   // désactiver supprimés
            $table->decimal('margin_override', 5, 2)->nullable();   // marge spécifique (null = par famille)
            $table->string('supplier_code')->nullable();            // fournisseur lié (code)
            $table->integer('last_import_count')->default(0);
            $table->integer('last_deactivated_count')->default(0);
            $table->timestamp('last_imported_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('golda_import_settings'); }
};