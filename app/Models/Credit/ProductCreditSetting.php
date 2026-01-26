<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class ProductCreditSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'credit_enabled',
        'min_down_payment_percent',
        'max_down_payment_percent',
        'down_payment_required',
        'allowed_installment_plan_ids',
        'custom_interest_rate',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'credit_enabled' => 'boolean',
        'min_down_payment_percent' => 'decimal:2',
        'max_down_payment_percent' => 'decimal:2',
        'down_payment_required' => 'boolean',
        'allowed_installment_plan_ids' => 'array',
        'custom_interest_rate' => 'decimal:2',
    ];

    /**
     * Relationship: Product this setting belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get available installment plans for this product
     */
    public function getAvailablePlans()
    {
        if ($this->allowed_installment_plan_ids && count($this->allowed_installment_plan_ids) > 0) {
            return InstallmentPlan::whereIn('id', $this->allowed_installment_plan_ids)
                                  ->active()
                                  ->get();
        }

        // If no specific plans, return all active plans
        return InstallmentPlan::active()->get();
    }

    /**
     * Get interest rate to use (custom or from plan)
     */
    public function getInterestRate(InstallmentPlan $plan)
    {
        return $this->custom_interest_rate ?? $plan->interest_rate;
    }

    /**
     * Validate down payment amount
     */
    public function validateDownPayment($dpAmount, $productPrice)
    {
        if ($productPrice <= 0) {
            return ['valid' => false, 'message' => 'Harga produk tidak valid'];
        }

        $dpPercent = ($dpAmount / $productPrice) * 100;

        // Check if DP is required but not provided
        if ($this->down_payment_required && $dpAmount <= 0) {
            return [
                'valid' => false,
                'message' => "DP minimal {$this->min_down_payment_percent}% wajib untuk produk ini"
            ];
        }

        // Check minimum DP
        if ($this->down_payment_required && $dpPercent < $this->min_down_payment_percent) {
            return [
                'valid' => false,
                'message' => "DP minimal {$this->min_down_payment_percent}%"
            ];
        }

        // Check maximum DP
        if ($dpPercent > $this->max_down_payment_percent) {
            return [
                'valid' => false,
                'message' => "DP maksimal {$this->max_down_payment_percent}%"
            ];
        }

        return ['valid' => true, 'message' => null];
    }

    /**
     * Check if credit is enabled for this product
     */
    public function isCreditEnabled()
    {
        return $this->credit_enabled;
    }
}
