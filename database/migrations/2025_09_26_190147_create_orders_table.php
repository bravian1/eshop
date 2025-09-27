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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses');
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses');
            $table->foreignId('delivery_option_id')->nullable()->constrained();
            $table->foreignId('delivery_zone_id')->nullable()->constrained();
            $table->foreignId('location_id')->nullable()->constrained();
            $table->unsignedInteger('delivery_price_cents');
            $table->unsignedSmallInteger('delivery_et_hours_min')->nullable();
            $table->unsignedSmallInteger('delivery_et_hours_max')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_intent_id')->nullable();
            $table->string('refund_status')->nullable();
            $table->unsignedInteger('refund_amount_cents')->nullable();
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
