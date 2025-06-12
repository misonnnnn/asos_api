<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = 'main_category';

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('external_unique_id')->unique();
            $table->string('main_category_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('parent_category_id')->index();
            $table->string('sub_category_id')->index();
            $table->string('child_category_id')->index();
            $table->string('status')->default('active')->nullable();
            $table->json('extra_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
