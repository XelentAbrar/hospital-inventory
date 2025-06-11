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
        Schema::create('material_issue_notes', function (Blueprint $table) {
            $table->id();
            $table->string('min_no');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->longText('remarks')->nullable();

            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });


        Schema::create('material_issue_note_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_issue_note_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('material_issue_note_id')->references('id')->on('material_issue_notes')->onDelete('set null');
        });


        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->bigInteger('total_qty')->default(0);
            $table->bigInteger('opening_stock')->default(0);
            $table->bigInteger('used_stock')->default(0);
            $table->bigInteger('return_stock')->default(0);
            $table->bigInteger('current_stock')->virtualAs("(opening_stock + total_qty) - used_stock - return_stock");
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_issue_notes');
        Schema::dropIfExists('material_issue_note_details');
        Schema::dropIfExists('stocks');
    }
};
