<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = 'products';

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('external_unique_id')->unique();
            $table->string('product_code')->nullable()->index();
            $table->string('category_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('brand_name')->nullable()->index();
            $table->string('price')->nullable()->index();
            $table->json('price_json')->nullable();
            $table->string('colour')->nullable()->index();
            $table->string('url')->nullable()->index();
            $table->string('status')->default('active')->nullable();
            $table->json('additional_images_urls')->nullable();
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
