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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_code')->unique();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_price', 12, 2);
            $table->string('recipient_name');
            $table->text('shipping_address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('phone');
            $table->string('payment_method')->default('transfer');
            $table->string('payment_status')->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
