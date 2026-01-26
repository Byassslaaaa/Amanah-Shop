<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district',
        'postal_code',
        'full_address',
        'is_default',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk alamat default
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Set alamat ini sebagai default dan unset yang lain
     */
    public function setAsDefault()
    {
        // Unset semua default untuk user ini
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Set ini sebagai default
        $this->update(['is_default' => true]);
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        return "{$this->full_address}, {$this->district}, {$this->city_name}, {$this->province_name} {$this->postal_code}";
    }
}
