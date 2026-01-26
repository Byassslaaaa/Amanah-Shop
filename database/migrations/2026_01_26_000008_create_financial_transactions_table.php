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
        // Categories for financial transactions
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['income', 'expense']); // Pemasukan atau Pengeluaran
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Financial transactions (manual entry by admin)
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // FT202601260001
            $table->enum('type', ['income', 'expense']); // Pemasukan atau Pengeluaran
            $table->foreignId('category_id')->constrained('transaction_categories')->onDelete('restrict');

            $table->date('transaction_date');
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->text('notes')->nullable();

            // Reference (optional) - bisa link ke order, manual_credit, dll
            $table->string('reference_type')->nullable(); // App\Models\Order, App\Models\ManualCredit
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('payment_method')->nullable(); // Cash, Transfer, etc
            $table->string('receipt_number')->nullable(); // Nomor kwitansi/bukti
            $table->string('attachment')->nullable(); // File path untuk bukti transaksi

            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('transaction_categories');
    }
};
