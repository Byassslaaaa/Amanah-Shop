<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'address',
        'province',
        'city',
        'district',
        'phone',
        'email',
        'whatsapp',
        'status',
        'origin_province_id',
        'origin_province_name',
        'origin_city_id',
        'origin_city_name',
        'origin_postal_code',
        'latitude',
        'longitude',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
