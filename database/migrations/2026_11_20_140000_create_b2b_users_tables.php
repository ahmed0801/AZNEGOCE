<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Comptes de connexion du portail B2B + réglages B2B par client. */
class CreateB2bUsersTables extends Migration
{
    public function up()
    {
        Schema::create('b2b_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('name', 120);
            $table->string('email', 150)->unique();
            $table->string('phone', 50)->nullable();
            $table->string('password');
            $table->boolean('must_change_password')->default(true);
            $table->string('status', 12)->default('active');      // active | suspended
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('customer_b2b_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique();
            $table->boolean('portal_enabled')->default(true);       // interrupteur d'accès pour ce client
            $table->boolean('can_order')->default(true);            // false = consultation du catalogue uniquement
            $table->string('validation_mode', 10)->default('inherit'); // inherit | manual | auto
            $table->boolean('show_prices')->default(true);
            $table->text('note')->nullable();                       // note interne
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_b2b_settings');
        Schema::dropIfExists('b2b_users');
    }
}
