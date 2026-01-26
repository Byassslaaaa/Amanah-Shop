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
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_number')->unique(); // e.g., "PAY202601260001"
            $table->integer('installment_number'); // 1, 2, 3, etc.
            $table->decimal('amount_due', 10, 2); // Expected payment amount
            $table->decimal('amount_paid', 10, 2)->default(0); // Actual paid amount
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue', 'partial'])->default('pending');
            $table->text('payment_proof')->nullable(); // If manual upload/transfer
            $table->text('notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes for performance
            $table->index(['order_id', 'installment_number']);
            $table->index('due_date');
            $table->index('status');
            $table->index('paid_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};
