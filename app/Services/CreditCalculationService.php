<?php

namespace App\Services;

use App\Models\Credit\InstallmentPlan;
use App\Models\Credit\InstallmentPayment;
use Carbon\Carbon;

class CreditCalculationService
{
    /**
     * Calculate flat interest amount
     * Formula: Interest = Principal × (Rate / 100)
     *
     * @param float $principal
     * @param float $interestRate
     * @return float
     */
    public function calculateFlatInterest($principal, $interestRate)
    {
        return $principal * ($interestRate / 100);
    }

    /**
     * Calculate monthly installment amount
     * Formula: Monthly = (Principal + Interest) / Months
     *
     * @param float $principal
     * @param float $interestRate
     * @param int $months
     * @return float
     */
    public function calculateMonthlyInstallment($principal, $interestRate, $months)
    {
        $interest = $this->calculateFlatInterest($principal, $interestRate);
        $total = $principal + $interest;
        return $total / $months;
    }

    /**
     * Calculate complete credit details
     *
     * @param float $orderTotal Total order amount (products + shipping)
     * @param float $downPayment Down payment amount
     * @param int $installmentPlanId Installment plan ID
     * @param float|null $customInterestRate Optional custom rate (overrides plan rate)
     * @return array
     */
    public function calculateCredit($orderTotal, $downPayment, $installmentPlanId, $customInterestRate = null)
    {
        $plan = InstallmentPlan::findOrFail($installmentPlanId);

        // Calculate principal (amount to be financed)
        $principal = $orderTotal - $downPayment;

        // Use custom rate if provided, otherwise use plan rate
        $interestRate = $customInterestRate ?? $plan->interest_rate;

        // Calculate interest (flat rate)
        $interest = $this->calculateFlatInterest($principal, $interestRate);

        // Total credit amount (principal + interest)
        $totalCredit = $principal + $interest;

        // Monthly installment
        $monthlyPayment = $totalCredit / $plan->months;

        return [
            'down_payment' => round($downPayment, 2),
            'principal' => round($principal, 2),
            'interest_rate' => $interestRate,
            'interest_amount' => round($interest, 2),
            'total_credit' => round($totalCredit, 2),
            'monthly_installment' => round($monthlyPayment, 2),
            'installment_months' => $plan->months,
            'plan_name' => $plan->name,
            'plan_id' => $plan->id,
        ];
    }

    /**
     * Generate installment payment schedule
     *
     * @param int $orderId
     * @param float $monthlyAmount
     * @param int $months
     * @param Carbon|string|null $firstDueDate
     * @return array
     */
    public function generateSchedule($orderId, $monthlyAmount, $months, $firstDueDate = null)
    {
        $schedule = [];
        $dueDate = $firstDueDate ? Carbon::parse($firstDueDate) : now()->addMonth()->startOfDay();

        for ($i = 1; $i <= $months; $i++) {
            $schedule[] = [
                'order_id' => $orderId,
                'payment_number' => $this->generatePaymentNumber($i),
                'installment_number' => $i,
                'amount_due' => round($monthlyAmount, 2),
                'amount_paid' => 0,
                'due_date' => $dueDate->copy()->toDateString(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $dueDate->addMonth();
        }

        return $schedule;
    }

    /**
     * Generate unique payment number
     *
     * @param int $sequence
     * @return string
     */
    protected function generatePaymentNumber($sequence)
    {
        return 'PAY' . date('Ymd') . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Validate down payment amount
     *
     * @param float $downPayment
     * @param float $orderTotal
     * @param float $minPercent
     * @param float $maxPercent
     * @param bool $required
     * @return array ['valid' => bool, 'message' => string|null]
     */
    public function validateDownPayment($downPayment, $orderTotal, $minPercent = 0, $maxPercent = 100, $required = false)
    {
        if ($orderTotal <= 0) {
            return ['valid' => false, 'message' => 'Total pesanan tidak valid'];
        }

        $dpPercent = ($downPayment / $orderTotal) * 100;

        // Check if DP is required but not provided
        if ($required && $downPayment <= 0) {
            return [
                'valid' => false,
                'message' => "DP minimal {$minPercent}% wajib untuk produk ini"
            ];
        }

        // Check minimum DP
        if ($required && $dpPercent < $minPercent) {
            return [
                'valid' => false,
                'message' => "DP minimal {$minPercent}% dari total pesanan"
            ];
        }

        // Check maximum DP (can't be more than 100% obviously)
        if ($dpPercent > $maxPercent) {
            return [
                'valid' => false,
                'message' => "DP maksimal {$maxPercent}% dari total pesanan"
            ];
        }

        // DP can't exceed order total
        if ($downPayment > $orderTotal) {
            return [
                'valid' => false,
                'message' => 'DP tidak boleh melebihi total pesanan'
            ];
        }

        return ['valid' => true, 'message' => null];
    }

    /**
     * Calculate example credit scenario (for UI preview)
     *
     * @param float $productPrice
     * @param float $downPaymentPercent (0-100)
     * @param int $months
     * @param float $interestRate
     * @return array
     */
    public function calculateExample($productPrice, $downPaymentPercent, $months, $interestRate)
    {
        $downPayment = ($productPrice * $downPaymentPercent) / 100;
        $principal = $productPrice - $downPayment;
        $interest = $this->calculateFlatInterest($principal, $interestRate);
        $totalCredit = $principal + $interest;
        $monthly = $totalCredit / $months;

        return [
            'product_price' => round($productPrice, 2),
            'down_payment_percent' => $downPaymentPercent,
            'down_payment' => round($downPayment, 2),
            'principal' => round($principal, 2),
            'interest_rate' => $interestRate,
            'interest_amount' => round($interest, 2),
            'total_credit' => round($totalCredit, 2),
            'monthly_installment' => round($monthly, 2),
            'months' => $months,
        ];
    }
}
