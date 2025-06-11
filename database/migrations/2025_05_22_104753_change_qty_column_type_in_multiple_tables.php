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


        Schema::table('demand_details', function (Blueprint $table) {
            $table->string('qty')->change();
        });
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->string('qty')->change();
        });
        Schema::table('inward_gatepass_details', function (Blueprint $table) {
            $table->string('qty')->change();
        });
         Schema::table('material_issue_note_details', function (Blueprint $table) {
            $table->string('qty')->change();
        });

        Schema::table('material_return_note_details', function (Blueprint $table) {
            $table->string('qty')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demand_details', function (Blueprint $table) {
            $table->bigInteger('qty')->change();
        });
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->bigInteger('qty')->change();
        });
         Schema::table('inward_gatepass_details', function (Blueprint $table) {
            $table->bigInteger('qty')->change();
        });
        Schema::table('material_issue_note_details', function (Blueprint $table) {
            $table->bigInteger('qty')->change();
        });

        Schema::table('material_return_note_details', function (Blueprint $table) {
            $table->bigInteger('qty')->change();
        });
    }
};
