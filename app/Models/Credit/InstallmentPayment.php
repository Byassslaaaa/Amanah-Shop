<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class InstallmentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_number',
        'installment_number',
        'amount_due',
        'amount_paid',
        'due_date',
        'paid_date',
        'status',
        'payment_proof',
        'notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'installment_number' => 'integer',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'verified_at' => 'datetime',
        'verified_by' => 'integer',
    ];

    /**
     * Relationship: Order this payment belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: User who verified this payment
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope: Get only pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get only paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: Get overdue payments
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
     * Check if this payment is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'pending' &&
               $this->due_date < now()->startOfDay();
    }

    /**
     * Check if fully paid
     */
    public function isFullyPaid()
    {
        return $this->status === 'paid' &&
               $this->amount_paid >= $this->amount_due;
    }

    /**
     * Check if partially paid
     */
    public function isPartiallyPaid()
    {
        return $this->status === 'partial' &&
               $this->amount_paid > 0 &&
               $this->amount_paid < $this->amount_due;
    }

    /**
     * Mark this payment as paid
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

        // Update order's total_paid and remaining_balance
        $this->order->updateCreditBalance();

        return $this;
    }

    /**
     * Get remaining amount to pay
     */
    public function getRemainingAmount()
    {
        return max(0, $this->amount_due - $this->amount_paid);
    }

    /**
     * Get days until due (negative if overdue)
     */
    public function getDaysUntilDue()
    {
        return now()->startOfDay()->diffInDays($this->due_date, false);
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'paid' => 'green',
            'pending' => 'yellow',
            'overdue' => 'red',
            'partial' => 'blue',
            default => 'gray'
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'paid' => 'Lunas',
            'pending' => 'Belum Bayar',
            'overdue' => 'Jatuh Tempo',
            'partial' => 'Dibayar Sebagian',
            default => 'Unknown'
        };
    }
}
