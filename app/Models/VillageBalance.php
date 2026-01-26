<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_id',
        'total_balance',
        'available_balance',
        'pending_balance',
        'total_withdrawn',
    ];

    protected $casts = [
        'total_balance' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    /**
     * Relationship: Village
     */
    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Add earning from order (98% of product total after 2% platform fee)
     */
    public function addEarning($amount, $orderId, $description = null)
    {
        $platformFee = $amount * 0.02; // 2% platform fee
        $netAmount = $amount - $platformFee;

        $this->increment('total_balance', $netAmount);
        $this->increment('available_balance', $netAmount);

        // Create transaction record
        VillageTransaction::create([
            'transaction_number' => 'TRX' . time() . rand(1000, 9999),
            'village_id' => $this->village_id,
            'order_id' => $orderId,
            'type' => 'earning',
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'description' => $description ?? 'Pendapatan dari order',
        ]);

        return $this;
    }

    /**
     * Lock balance for withdrawal request
     */
    public function lockBalance($amount)
    {
        if ($this->available_balance < $amount) {
            throw new \Exception('Saldo tidak mencukupi');
        }

        $this->decrement('available_balance', $amount);
        $this->increment('pending_balance', $amount);

        return $this;
    }

    /**
     * Release locked balance (when withdrawal rejected)
     */
    public function releaseBalance($amount)
    {
        $this->increment('available_balance', $amount);
        $this->decrement('pending_balance', $amount);

        return $this;
    }

    /**
     * Complete withdrawal (when withdrawal completed)
     */
    public function completeWithdrawal($amount, $withdrawalId)
    {
        $this->decrement('pending_balance', $amount);
        $this->decrement('total_balance', $amount);
        $this->increment('total_withdrawn', $amount);

        // Create transaction record
        VillageTransaction::create([
            'transaction_number' => 'TRX' . time() . rand(1000, 9999),
            'village_id' => $this->village_id,
            'order_id' => null,
            'type' => 'withdrawal',
            'amount' => $amount,
            'platform_fee' => 0,
            'net_amount' => $amount,
            'description' => 'Penarikan saldo #' . $withdrawalId,
        ]);

        return $this;
    }
}
