<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed']);
            $table->integer('value');
            $table->integer('min_purchase')->default(0);
            $table->integer('max_discount')->nullable(); // batas maksimal diskon untuk tipe percentage
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('flash_sale_price')->nullable()->after('price');
            $table->dateTime('flash_sale_start')->nullable()->after('flash_sale_price');
            $table->dateTime('flash_sale_end')->nullable()->after('flash_sale_start');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete()->after('payment_token');
            $table->integer('discount_amount')->default(0)->after('voucher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['voucher_id', 'discount_amount']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['flash_sale_price', 'flash_sale_start', 'flash_sale_end']);
        });

        Schema::dropIfExists('vouchers');
    }
};
