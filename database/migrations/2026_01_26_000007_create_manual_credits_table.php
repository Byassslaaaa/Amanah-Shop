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
        Schema::create('manual_credits', function (Blueprint $table) {
            $table->id();
            $table->string('credit_number')->unique(); // MC202601260001
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('description'); // Keterangan pinjaman untuk apa

            // Loan details
            $table->decimal('loan_amount', 12, 2); // Jumlah pinjaman
            $table->decimal('down_payment', 12, 2)->default(0); // DP/Uang muka
            $table->decimal('principal_amount', 12, 2); // Pokok (loan - dp)
            $table->decimal('interest_rate', 5, 2)->default(0); // % bunga per tahun
            $table->decimal('interest_amount', 12, 2)->default(0); // Total bunga
            $table->decimal('total_amount', 12, 2); // Total yang harus dibayar
            $table->integer('installment_months'); // Lama cicilan (bulan)
            $table->decimal('monthly_installment', 12, 2); // Cicilan per bulan

            $table->decimal('total_paid', 12, 2)->default(0);
            $table->decimal('remaining_balance', 12, 2);

            $table->enum('status', ['active', 'completed', 'overdue', 'cancelled'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });

        // Manual credit installment payments
        Schema::create('manual_credit_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manual_credit_id')->constrained()->onDelete('cascade');
            $table->string('payment_number')->unique(); // MCP202601260001
            $table->integer('installment_number'); // 1, 2, 3...
            $table->decimal('amount_due', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_credit_payments');
        Schema::dropIfExists('manual_credits');
    }
};
