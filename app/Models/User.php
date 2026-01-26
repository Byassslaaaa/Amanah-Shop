<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Order;
use App\Models\Order\Cart;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'village_id',
        'phone',
        'address',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }


    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product\Product::class, 'favorites')->withTimestamps();
    }
}
