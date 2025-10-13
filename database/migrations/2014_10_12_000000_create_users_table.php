<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;       // ⚡ Ajout
use Illuminate\Support\Facades\Hash;     // ⚡ Ajout

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });



            // ⚡ Création du compte admin par défaut
        DB::table('users')->insert([
            'name' => 'Administrateur',
            'email' => 'admin@az.com',
            'role' => 'admin',
            'password' => Hash::make('00000000'), // Hash du mot de passe
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
    }

