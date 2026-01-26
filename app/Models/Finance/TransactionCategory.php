<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // income / expense
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Transactions
     */
    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'category_id');
    }

    /**
     * Scope: Income categories
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope: Expense categories
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope: Active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
