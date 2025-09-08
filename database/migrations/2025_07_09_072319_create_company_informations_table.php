<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCompanyInformationsTable extends Migration
{
    public function up()
    {
        Schema::create('company_informations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Test Company S.A.R.L');
            $table->string('address')->default('123 Rue Fictive, Tunis 1000');
            $table->string('phone')->default('+216 12 345 678');
            $table->string('email')->default('contact@testcompany.com');
            $table->string('matricule_fiscal')->default('1234567ABC000');
            $table->string('swift')->nullable()->default('TESTTNTT');
            $table->string('rib')->nullable()->default('123456789012');
            $table->string('iban')->nullable()->default('TN59 1234 5678 9012 3456 7890');
            $table->string('logo_path')->default('assets/img/test_logo.png');
            $table->timestamps();
        });

        // Insérer les informations de test par défaut
        DB::table('company_informations')->insert([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('company_informations');
    }
}