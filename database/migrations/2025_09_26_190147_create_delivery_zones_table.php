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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_option_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['radius', 'polygon', 'postal-list', 'station-list']);
            $table->json('geo_json');
            $table->unsignedInteger('base_price_cents');
            $table->unsignedSmallInteger('price_per_kg_cents')->nullable();
            $table->unsignedSmallInteger('price_per_item_cents')->nullable();
            $table->unsignedInteger('free_above_cents')->nullable();
            $table->unsignedSmallInteger('eta_hours_min');
            $table->unsignedSmallInteger('eta_hours_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
