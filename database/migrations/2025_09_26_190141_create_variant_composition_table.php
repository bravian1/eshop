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
        Schema::create('variant_composition', function (Blueprint $table) {
            $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_axis_value_id')->constrained()->cascadeOnDelete();
            $table->primary(['variant_id', 'variant_axis_value_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_composition');
    }
};
