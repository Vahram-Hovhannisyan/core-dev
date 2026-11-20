<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core_units', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code')->unique();          // pcs, m2, pack
            $table->json('name');
            $table->string('type');                    // count, area, length, weight, volume
            $table->timestamps();
        });

        Schema::create('core_brands', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('core_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('parent_id')->nullable();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('core_categories', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('core_categories')
                ->nullOnDelete();
        });

        Schema::create('core_products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('brand_id')->nullable()->constrained('core_brands')->nullOnDelete();
            $table->foreignUlid('base_unit_id')->constrained('core_units');
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('attributes')->nullable();    // гибкие поля, сюда пишут плагины
            $table->string('status')->default('draft'); // draft, active, archived
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('core_category_product', function (Blueprint $table) {
            $table->foreignUlid('category_id')->constrained('core_categories')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('core_products')->cascadeOnDelete();
            $table->primary(['category_id', 'product_id']);
        });

        Schema::create('core_product_variants', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('product_id')->constrained('core_products')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->json('name')->nullable();
            $table->json('attributes')->nullable();    // цвет, размер и т. д.
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_product_variants');
        Schema::dropIfExists('core_category_product');
        Schema::dropIfExists('core_products');
        Schema::dropIfExists('core_categories');
        Schema::dropIfExists('core_brands');
        Schema::dropIfExists('core_units');
    }
};
