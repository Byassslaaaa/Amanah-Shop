<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Inventory movements (purchases)
     */
    public function inventoryMovements()
    {
        return $this->hasMany(\App\Models\Inventory\InventoryMovement::class);
    }

    /**
     * Scope: Active suppliers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get total purchases from this supplier
     */
    public function getTotalPurchases()
    {
        return $this->inventoryMovements()
            ->where('type', 'in')
            ->sum('total_price');
    }
}
