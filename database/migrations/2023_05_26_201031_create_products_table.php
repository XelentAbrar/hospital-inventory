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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('product_type', ['Standard', 'Variation', 'Digital', 'Service'])->default('Standard')->nullable();
            $table->string('product_code')->unique()->nullable();
            $table->enum('barcode_type', ['Code25', 'Code39', 'Code128', 'EAN8', 'EAN13', 'UPC-A', 'UPC-E'])->default('Code128')->nullable();
            $table->string('featured_image')->nullable();
            $table->text('gallery_images')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->enum('tax_method', ['Exclusive', 'Inclusive'])->default('Exclusive')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('warehouse_rack')->nullable();
            $table->date('mgf_date')->nullable();
            $table->date('exp_date')->nullable();
            $table->unsignedBigInteger('uom_id');
            $table->integer('opening_stock')->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->integer('min_stock')->nullable();
            $table->integer('max_stock')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('tax_id')->references('id')->on('taxes');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('uom_id')->references('id')->on('uoms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
