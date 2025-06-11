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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign([
                'category_id',
            ]);
            $table->dropForeign([
                'brand_id',
            ]);
            $table->dropForeign([
                'tax_id',
            ]);
            $table->dropForeign([
                'warehouse_id',
            ]);
            $table->dropColumn([
                'product_type',
                'barcode_type',
                'gallery_images',
                'category_id',
                'brand_id',
                'tax_id',
                'tax_method',
                'warehouse_id',
                'warehouse_rack',
                'mgf_date',
                'exp_date',
                'selling_price',
                'min_stock',
                'max_stock',
                'description',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('product_type', ['Standard', 'Variation', 'Digital', 'Service'])->default('Standard')->nullable()->after('slug');
            $table->enum('barcode_type', ['Code25', 'Code39', 'Code128', 'EAN8', 'EAN13', 'UPC-A', 'UPC-E'])->default('Code128')->nullable()->after('product_code');
            $table->text('gallery_images')->nullable()->after('featured_image');
            $table->unsignedBigInteger('category_id')->nullable()->after('gallery_images');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedBigInteger('brand_id')->nullable()->after('category_id');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('tax_id')->nullable()->after('brand_id');
            $table->foreign('tax_id')->references('id')->on('taxes');
            $table->enum('tax_method', ['Exclusive', 'Inclusive'])->default('Exclusive')->nullable()->after('tax_id');
            $table->unsignedBigInteger('warehouse_id')->nullable()->after('tax_method');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->string('warehouse_rack')->nullable()->after('warehouse_id');
            $table->date('mgf_date')->nullable()->after('warehouse_rack');
            $table->date('exp_date')->nullable()->after('mgf_date');
            $table->decimal('selling_price', 10, 2)->nullable()->after('opening_stock');
            $table->integer('min_stock')->nullable()->after('selling_price');
            $table->integer('max_stock')->nullable()->after('min_stock');
            $table->text('description')->nullable()->after('short_description');
        });
    }
};
