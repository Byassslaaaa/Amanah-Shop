<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'shipping_address_id',
        'total_amount',
        'shipping_cost',
        'shipping_service',
        'shipping_etd',
        'shipping_tracking_number',
        'shipping_courier',
        'shipping_resi',
        'shipping_status',
        'shipping_history',
        'status',
        'payment_status',
        'payment_method',
        'payment_type',
        'payment_proof',
        'customer_notes',
        'admin_notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'tracking_updated_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_transaction_status',
        'midtrans_snap_token',
        // Credit fields
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
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'tracking_updated_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'shipping_history' => 'array',
        // Credit casts
        'down_payment_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_credit_amount' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'credit_approved_at' => 'datetime',
        'fully_paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(\App\Models\Credit\InstallmentPlan::class);
    }

    public function installmentPayments()
    {
        return $this->hasMany(\App\Models\Credit\InstallmentPayment::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeCredit($query)
    {
        return $query->where('payment_type', 'credit');
    }

    public function scopeCash($query)
    {
        return $query->where('payment_type', 'cash');
    }

    public function scopeActiveInstallments($query)
    {
        return $query->where('payment_type', 'credit')
                     ->where('payment_status', 'installment_active');
    }

    /**
     * Check if this is a credit order
     */
    public function isCreditOrder()
    {
        return $this->payment_type === 'credit';
    }

    /**
     * Update credit balance after payment
     * ⚠️ CRITICAL: Uses pessimistic locking to prevent race conditions
     */
    public function updateCreditBalance()
    {
        if (!$this->isCreditOrder()) {
            return;
        }

        // ⚠️ ATOMIC: Wrap in transaction with pessimistic lock
        \DB::transaction(function () {
            // Lock this order for update to prevent concurrent modifications
            $order = self::lockForUpdate()->find($this->id);

            // Recalculate total paid with fresh data
            $totalPaid = $order->installmentPayments()->sum('amount_paid');
            $remaining = $order->total_credit_amount - $totalPaid;

            // Update balance
            $order->update([
                'total_paid' => $totalPaid,
                'remaining_balance' => max(0, $remaining),
            ]);

            // Update payment status if fully paid
            if ($remaining <= 0) {
                $order->update([
                    'payment_status' => 'installment_completed',
                    'fully_paid_at' => now(),
                ]);
            }

            // Refresh current model instance
            $this->refresh();
        });
    }

    /**
     * Check for overdue payments
     */
    public function checkOverduePayments()
    {
        if (!$this->isCreditOrder()) {
            return false;
        }

        $hasOverdue = $this->installmentPayments()
                           ->where('status', 'pending')
                           ->where('due_date', '<', now()->startOfDay())
                           ->exists();

        if ($hasOverdue && $this->payment_status !== 'installment_overdue') {
            $this->update(['payment_status' => 'installment_overdue']);
        }

        return $hasOverdue;
    }

    /**
     * Get credit progress percentage
     */
    public function getCreditProgressPercent()
    {
        if (!$this->isCreditOrder() || $this->total_credit_amount <= 0) {
            return 0;
        }

        return ($this->total_paid / $this->total_credit_amount) * 100;
    }

    /**
     * Check if order has tracking info
     */
    public function hasTracking()
    {
        return !empty($this->shipping_resi) && !empty($this->shipping_courier);
    }

    /**
     * Check if order is shipped
     */
    public function isShipped()
    {
        return !empty($this->shipped_at);
    }

    /**
     * Check if order is delivered
     */
    public function isDelivered()
    {
        return $this->shipping_status === 'delivered' && !empty($this->delivered_at);
    }

    /**
     * Get tracking URL for external website
     */
    public function getTrackingUrl()
    {
        if (!$this->hasTracking()) {
            return null;
        }

        $urls = [
            'jne' => 'https://www.jne.co.id/id/tracking/trace',
            'jnt' => 'https://www.jet.co.id/track',
            'sicepat' => 'https://www.sicepat.com/checkAwb',
            'tiki' => 'https://www.tiki.id/id/tracking',
            'pos' => 'https://www.posindonesia.co.id/id/tracking',
            'ninja' => 'https://www.ninjaxpress.co/id-id/tracking',
            'anteraja' => 'https://www.anteraja.id/tracking',
        ];

        return $urls[strtolower($this->shipping_courier)] ?? null;
    }

    /**
     * Get status badge color
     */
    public function getShippingStatusColor()
    {
        return match($this->shipping_status) {
            'delivered' => 'green',
            'in_transit' => 'blue',
            'on_process' => 'yellow',
            'failed' => 'red',
            default => 'gray'
        };
    }
}
