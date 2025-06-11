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
        Schema::create('inward_gatepass', function (Blueprint $table) {
            $table->id();
            $table->string('gatepass_no');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->longText('remarks')->nullable();

            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });


        Schema::create('inward_gatepass_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inward_gatepass_id')->nullable();
            $table->unsignedBigInteger('purchase_order_detail_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->double('tax_rate', 11,2)->nullable();
            $table->double('price', 11,2)->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('purchase_order_detail_id')->references('id')->on('purchase_order_details')->onDelete('set null');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inward_gatepass');
        Schema::dropIfExists('inward_gatepass_details');
    }
};
