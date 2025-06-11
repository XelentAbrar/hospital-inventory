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
        Schema::create('goods_receipt_notes', function (Blueprint $table) {
            $table->id();
            $table->string('goods_receipt_no');
            $table->unsignedBigInteger('inward_gatepass_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->longText('remarks')->nullable();

            $table->timestamps();
            $table->foreign('inward_gatepass_id')->references('id')->on('inward_gatepass')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_notes');
    }
};
