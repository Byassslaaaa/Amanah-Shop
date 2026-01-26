<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'type', // income / expense
        'category_id',
        'transaction_date',
        'amount',
        'description',
        'notes',
        'reference_type',
        'reference_id',
        'payment_method',
        'receipt_number',
        'attachment',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship: Category
     */
    public function category()
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    /**
     * Relationship: Creator
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope: Income transactions
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope: Expense transactions
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope: Date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope: This month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year);
    }

    /**
     * Generate unique transaction number
     */
    public static function generateTransactionNumber()
    {
        $prefix = 'FT';
        $date = date('Ymd');
        $lastTransaction = self::whereDate('created_at', today())
            ->latest()
            ->first();

        $number = $lastTransaction ? (intval(substr($lastTransaction->transaction_number, -4)) + 1) : 1;

        return $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
