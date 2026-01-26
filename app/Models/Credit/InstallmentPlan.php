<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class InstallmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'months',
        'interest_rate',
        'description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'months' => 'integer',
        'interest_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Relationship: Orders using this plan
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope: Get only active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->orderBy('display_order')
                     ->orderBy('months');
    }

    /**
     * Calculate flat interest amount
     * Formula: Interest = Principal × (Rate / 100)
     */
    public function calculateInterest($principal)
    {
        return $principal * ($this->interest_rate / 100);
    }

    /**
     * Calculate monthly payment amount
     * Formula: Monthly = (Principal + Interest) / Months
     */
    public function calculateMonthlyPayment($principal)
    {
        $interest = $this->calculateInterest($principal);
        $total = $principal + $interest;
        return $total / $this->months;
    }

    /**
     * Get total amount to be paid (principal + interest)
     */
    public function getTotalAmount($principal)
    {
        return $principal + $this->calculateInterest($principal);
    }
}
