<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('main_category', function (Blueprint $table) {
            $table->string('is_product_list_already_sync')->after('status')->default('pending'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_category', function (Blueprint $table) {
           $table->dropColumn('is_product_list_already_sync');
        });
    }
};
