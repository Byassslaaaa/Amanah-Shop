<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualCreditPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'manual_credit_id',
        'payment_number',
        'installment_number',
        'amount_due',
        'amount_paid',
        'due_date',
        'paid_date',
        'status',
        'notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Relationship: Manual credit
     */
    public function manualCredit()
    {
        return $this->belongsTo(ManualCredit::class);
    }

    /**
     * Relationship: Verifier
     */
    public function verifier()
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by');
    }

    /**
     * Scope: Pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Overdue payments
     */
    public function scopeOverdue($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'overdue')
                ->orWhere(function ($sq) {
                    $sq->where('status', 'pending')
                        ->where('due_date', '<', now()->startOfDay());
                });
        });
    }

    /**
     * Mark as paid
     */
    public function markAsPaid($amount, $paidDate = null, $notes = null, $verifiedBy = null)
    {
        $this->update([
            'amount_paid' => $amount,
            'paid_date' => $paidDate ?? now(),
            'status' => $amount >= $this->amount_due ? 'paid' : 'partial',
            'notes' => $notes,
            'verified_at' => now(),
            'verified_by' => $verifiedBy ?? auth()->id(),
        ]);

        // Update parent credit balance
        $this->manualCredit->updateBalance();
    }

    /**
     * Generate unique payment number
     */
    public static function generatePaymentNumber()
    {
        $prefix = 'MCP';
        $date = date('Ymd');
        $lastPayment = self::whereDate('created_at', today())
            ->latest()
            ->first();

        $number = $lastPayment ? (intval(substr($lastPayment->payment_number, -4)) + 1) : 1;

        return $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
