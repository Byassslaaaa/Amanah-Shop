<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualCredit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'credit_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'description',
        'installment_plan_id',
        'loan_amount',
        'down_payment',
        'principal_amount',
        'interest_amount',
        'total_amount',
        'monthly_installment',
        'installment_months',
        'total_paid',
        'remaining_balance',
        'status',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relationship: Installment plan
     */
    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    /**
     * Relationship: Payments
     */
    public function payments()
    {
        return $this->hasMany(ManualCreditPayment::class);
    }

    /**
     * Relationship: Creator
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope: Active credits
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Overdue credits
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Update remaining balance
     */
    public function updateBalance()
    {
        $this->total_paid = $this->payments()->sum('amount_paid');
        $this->remaining_balance = $this->total_amount - $this->total_paid;

        if ($this->remaining_balance <= 0) {
            $this->status = 'completed';
            $this->end_date = now();
        }

        $this->save();
    }

    /**
     * Generate unique credit number
     */
    public static function generateCreditNumber()
    {
        $prefix = 'MC';
        $date = date('Ymd');
        $lastCredit = self::whereDate('created_at', today())
            ->latest()
            ->first();

        $number = $lastCredit ? (intval(substr($lastCredit->credit_number, -4)) + 1) : 1;

        return $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
