<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountRatesToDiscountGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('discount_groups', function (Blueprint $table) {
            $table->decimal('discount_rate_jobber', 8, 2)->default(0);
            $table->decimal('discount_rate_professionnel', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discount_groups', function (Blueprint $table) {
            $table->dropColumn('discount_rate_jobber');
            $table->dropColumn('discount_rate_professionnel');
        });
    }
}
