<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // create_permissions_table.php

public function up()
{
    Schema::create('permissions', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // exemple: delete_invoice
        $table->string('label'); // exemple: Supprimer les factures
        $table->timestamps();
    });

    Schema::create('permission_user', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('permission_id')->constrained()->onDelete('cascade');
        $table->primary(['user_id', 'permission_id']);
    });
}

}
