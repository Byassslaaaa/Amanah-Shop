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
            // Payment type
            $table->enum('payment_type', ['cash', 'credit'])->default('cash')->after('payment_method');

            // Credit-specific fields
            $table->foreignId('installment_plan_id')->nullable()->constrained()->nullOnDelete()->after('payment_type');
            $table->decimal('down_payment_amount', 10, 2)->default(0)->after('installment_plan_id');
            $table->decimal('principal_amount', 10, 2)->default(0)->after('down_payment_amount'); // Amount to be financed
            $table->decimal('interest_amount', 10, 2)->default(0)->after('principal_amount'); // Total interest
            $table->decimal('total_credit_amount', 10, 2)->default(0)->after('interest_amount'); // Principal + Interest
            $table->decimal('monthly_installment', 10, 2)->default(0)->after('total_credit_amount');
            $table->integer('installment_months')->default(0)->after('monthly_installment');
            $table->decimal('total_paid', 10, 2)->default(0)->after('installment_months'); // Sum of all installment payments
            $table->decimal('remaining_balance', 10, 2)->default(0)->after('total_paid');
            $table->timestamp('credit_approved_at')->nullable()->after('remaining_balance');
            $table->timestamp('fully_paid_at')->nullable()->after('credit_approved_at');

            // Indexes for performance
            $table->index('payment_type');
            $table->index('installment_plan_id');
        });

        // Update payment_status enum to include installment statuses
        // Note: MySQL doesn't support modifying enums directly, so we need to drop and recreate
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'refunded',
                'installment_active', // Credit with active payments
                'installment_overdue', // Credit with missed payments
                'installment_completed' // Credit fully paid
            ])->default('unpaid')->after('status');

            // Index for filtering
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original payment_status enum
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid')->after('status');
        });

        // Drop credit fields
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['installment_plan_id']);
            $table->dropIndex(['payment_type']);
            $table->dropIndex(['installment_plan_id']);
            $table->dropIndex(['payment_status']);

            $table->dropColumn([
                'payment_type',
                'installment_plan_id',
                'down_payment_amount',
                'principal_amount',
                'interest_amount',
                'total_credit_amount',
                'monthly_installment',
                'installment_months',
                'total_paid',
                'remaining_balance',
                'credit_approved_at',
                'fully_paid_at',
            ]);
        });
    }
};
