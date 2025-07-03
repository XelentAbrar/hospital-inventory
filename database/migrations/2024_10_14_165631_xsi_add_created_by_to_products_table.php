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
        // Adding columns to the products table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Adding columns to the inward_gatepass table
        Schema::table('inward_gatepass', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Adding columns to the material_issue_notes table
        Schema::table('material_issue_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Adding columns to the material_return_notes table
        Schema::table('material_return_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Adding columns to the demands table
        Schema::table('demands', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
        Schema::table('goods_receipt_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Adding columns to the purchase_orders table
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping columns from the products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Dropping columns from the inward_gatepass table
        Schema::table('inward_gatepass', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Dropping columns from the material_issue_notes table
        Schema::table('material_issue_notes', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Dropping columns from the material_return_notes table
        Schema::table('material_return_notes', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Dropping columns from the demands table
        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });
        Schema::table('goods_receipt_notes', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Dropping columns from the purchase_orders table
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
