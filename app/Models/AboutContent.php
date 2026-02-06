<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AboutContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'vision',
        'mission',
        'years_operating',
        'happy_customers',
        'products_sold',
        'team_members',
        'product_variants',
        'is_active',
    ];

    protected $casts = [
        'years_operating' => 'integer',
        'happy_customers' => 'integer',
        'products_sold' => 'integer',
        'team_members' => 'integer',
        'product_variants' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active about content
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return null;
    }

    /**
     * Get image as data URI (for blob storage)
     */
    public function getImageDataUri()
    {
        if (!$this->image) {
            return null;
        }

        if (Storage::disk('public')->exists($this->image)) {
            $content = Storage::disk('public')->get($this->image);
            // Detect mime type from file extension
            $extension = pathinfo($this->image, PATHINFO_EXTENSION);
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            $mimeType = $mimeTypes[strtolower($extension)] ?? 'image/jpeg';
            return 'data:' . $mimeType . ';base64,' . base64_encode($content);
        }

        return null;
    }
}
