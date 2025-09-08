
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
 public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('brand_id');
            $table->string('brand_name');
            $table->integer('model_id');
            $table->string('model_name');
            $table->integer('engine_id');
            $table->string('engine_description');
            $table->integer('linkage_target_id');
            $table->string('license_plate');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
