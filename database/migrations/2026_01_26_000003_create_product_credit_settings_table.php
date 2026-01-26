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
        Schema::create('product_credit_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->boolean('credit_enabled')->default(true); // Can this product be purchased on credit?
            $table->decimal('min_down_payment_percent', 5, 2)->default(0); // Minimum DP percentage (0-100)
            $table->decimal('max_down_payment_percent', 5, 2)->default(50); // Maximum DP percentage (0-100)
            $table->boolean('down_payment_required')->default(false); // Is DP mandatory?
            $table->json('allowed_installment_plan_ids')->nullable(); // Array of plan IDs, null = all allowed
            $table->decimal('custom_interest_rate', 5, 2)->nullable(); // Override global rate if set
            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('credit_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_credit_settings');
    }
};
