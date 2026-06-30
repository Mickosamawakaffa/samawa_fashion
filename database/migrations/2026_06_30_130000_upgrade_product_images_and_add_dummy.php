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
        // Upgrade product_images table
        Schema::table('product_images', function (Blueprint $table) {
            $table->renameColumn('image', 'image_path');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('image_path');
            $table->integer('sort_order')->default(0)->after('is_primary');
        });

        // Add is_dummy to products table
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_dummy')->default(false)->after('is_new_arrival');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn(['is_primary', 'sort_order']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->renameColumn('image_path', 'image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_dummy');
        });
    }
};
