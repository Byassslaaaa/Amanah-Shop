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
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "3 Bulan", "6 Bulan"
            $table->integer('months'); // 3, 6, 12, 24
            $table->decimal('interest_rate', 5, 2); // e.g., 5.00, 8.00, 12.00, 20.00 (flat rate percentage)
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0); // For sorting
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
