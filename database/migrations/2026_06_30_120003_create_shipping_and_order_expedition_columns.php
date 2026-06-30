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
        // 1. Modify products table weight column
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'weight')) {
                $table->integer('weight')->default(500)->change();
            } else {
                $table->integer('weight')->default(500);
            }
        });

        // 2. Add columns to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier')->nullable()->after('payment_status');
            $table->string('courier_service')->nullable()->after('courier');
            $table->integer('shipping_cost')->default(0)->after('courier_service');
            $table->string('tracking_number')->nullable()->after('shipping_cost');
            $table->string('estimated_delivery')->nullable()->after('tracking_number');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('processing_at')->nullable()->after('created_at');
        });

        // 3. Create shipping_addresses table
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('phone');
            $table->text('address_line');
            $table->integer('province_id');
            $table->string('province_name');
            $table->integer('city_id');
            $table->string('city_name');
            $table->string('district'); // kecamatan
            $table->string('postal_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier',
                'courier_service',
                'shipping_cost',
                'tracking_number',
                'estimated_delivery',
                'delivered_at',
                'processing_at'
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 8, 2)->default(0)->change();
            }
        });
    }
};
