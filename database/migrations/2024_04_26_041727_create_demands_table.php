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
        Schema::create('demands', function (Blueprint $table) {
            $table->id();
            $table->string('demand_no');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->longText('remarks')->nullable();

            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });


        Schema::create('demand_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('demand_id')->references('id')->on('demands')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demands');
        Schema::dropIfExists('demand_details');
    }
};
