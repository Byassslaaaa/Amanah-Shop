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
        Schema::table('orders', function (Blueprint $table) {
            // Change payment_status enum to include all statuses used in the code
            $table->enum('payment_status', [
                'unpaid',
                'pending',
                'paid',
                'failed',
                'expired',
                'cancelled',
                'refunded',
                'installment_active',
                'installment_overdue',
                'installment_completed'
            ])->default('unpaid')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to original enum
            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'refunded',
                'installment_active',
                'installment_overdue',
                'installment_completed'
            ])->default('unpaid')->change();
        });
    }
};
