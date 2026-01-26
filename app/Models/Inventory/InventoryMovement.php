<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\User;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'notes',
        'document_number',
        'unit_price',
        'total_price',
        'created_by',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'supplier_id' => 'integer',
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'reference_id' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_by' => 'integer',
    ];

    /**
     * Relationship: Product this movement belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: User who created this movement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: Supplier (for stock-in)
     */
    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class);
    }

    /**
     * Scope: Get only stock-in movements
     */
    public function scopeStockIn($query)
    {
        return $query->where('type', 'in');
    }

    /**
     * Scope: Get only stock-out movements
     */
    public function scopeStockOut($query)
    {
        return $query->where('type', 'out');
    }

    /**
     * Scope: Get movements for a specific product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope: Get movements within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Static method: Record a new inventory movement
     *
     * @param int $productId
     * @param string $type ('in' or 'out')
     * @param int $quantity
     * @param mixed $reference (model instance that triggered this movement, e.g., Order)
     * @param string|null $notes
     * @param array $additionalData (supplier_id, document_number, unit_price, etc)
     * @return InventoryMovement
     */
    public static function record($productId, $type, $quantity, $reference = null, $notes = null, $additionalData = [])
    {
        $product = Product::findOrFail($productId);

        $data = [
            'product_id' => $productId,
            'type' => $type,
            'quantity' => abs($quantity),
            'stock_before' => $product->stock,
            'stock_after' => $product->stock + ($type === 'in' ? $quantity : -$quantity),
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ];

        // Merge additional data (supplier_id, document_number, unit_price, total_price)
        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }

        $movement = self::create($data);

        return $movement;
    }

    /**
     * Get human-readable reference type
     */
    public function getReferenceTypeLabel()
    {
        if (!$this->reference_type) {
            return 'Manual';
        }

        return match($this->reference_type) {
            'App\Models\Order' => 'Penjualan',
            'adjustment' => 'Penyesuaian',
            'return' => 'Retur',
            'purchase' => 'Pembelian',
            default => 'Lainnya'
        };
    }

    /**
     * Get type label in Indonesian
     */
    public function getTypeLabel()
    {
        return $this->type === 'in' ? 'Masuk' : 'Keluar';
    }

    /**
     * Get type color for UI
     */
    public function getTypeColor()
    {
        return $this->type === 'in' ? 'green' : 'red';
    }
}
