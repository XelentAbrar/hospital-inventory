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
            $table->string('min_qty')->nullable();
            $table->string('max_qty')->nullable();
            $table->unsignedBigInteger('item_category_id')->nullable();
            $table->unsignedBigInteger('item_type_id')->nullable();
            $table->unsignedBigInteger('item_salt_id')->nullable();
            
            $table->foreign('item_category_id')->references('id')->on('item_category')->onDelete('set null');
            $table->foreign('item_type_id')->references('id')->on('item_type')->onDelete('set null');
            $table->foreign('item_salt_id')->references('id')->on('salt_items')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['item_category_id']);
            $table->dropForeign(['item_type_id']);
            $table->dropForeign(['item_salt_id']);
            $table->dropColumn('min_qty');
            $table->dropColumn('max_qty');
            $table->dropColumn('item_category_id');
            $table->dropColumn('item_type_id');
            $table->dropColumn('item_salt_id');

        });
    }
};
