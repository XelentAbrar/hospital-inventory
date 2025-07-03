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
        Schema::create('material_return_notes', function (Blueprint $table) {
            $table->id();
            $table->string('mrn_no');
            $table->timestamp('date')->nullable();
            $table->longText('remarks')->nullable();

            $table->timestamps();
        });


        Schema::create('material_return_note_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_return_note_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('material_return_note_id')->references('id')->on('material_return_notes')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_return_notes');
        Schema::dropIfExists('material_return_note_details');
    }
};
