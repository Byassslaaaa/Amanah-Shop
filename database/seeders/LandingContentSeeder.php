<?php

namespace Database\Seeders;

use App\Models\Content\LandingContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing da
        // LandingContent::truncate();
        
        // Create storage directory if not exists
        if (!Storage::disk('public')->exists('landing-content')) {
            Storage::disk('public')->makeDirectory('landing-content');
        }

        // Download and store images from unsplash
        $this->downloadImage('https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=1200&h=800&fit=crop&q=80', 'hero-bg.jpg');
        $this->downloadImage('https://images.unsplash.com/photo-1556740758-90de374c12ad?w=800&h=600&fit=crop&q=80', 'about-us.jpg');

        $contents = [
            [
                'key' => 'hero',
                'title' => 'Selamat Datang di Amanah Shop',
                'content' => 'Temukan dan beli produk berkualitas untuk kebutuhan rumah tangga Anda. Perabotan, pakaian, dan perlengkapan rumah dengan harga terjangkau.',
                'image' => 'landing-content/hero-bg.jpg',
                'data' => json_encode([
                    'button_text' => 'Mulai Berbelanja',
                    'button_link' => '/products',
                    'subtitle' => 'Produk Berkualitas untuk Rumah dan Gaya Hidup Anda'
                ]),
                'is_active' => true,
            ],
            [
                'key' => 'about-us',
                'title' => 'Tentang Amanah Shop',
                'content' => 'Amanah Shop adalah toko online yang menyediakan berbagai kebutuhan rumah tangga dan gaya hidup. Kami berkomitmen untuk menyediakan produk berkualitas dengan harga terjangkau dan pelayanan terbaik.',
                'image' => 'landing-content/about-us.jpg',
                'data' => json_encode([
                    'vision' => 'Menjadi toko online terpercaya untuk kebutuhan rumah tangga',
                    'mission' => 'Menyediakan produk berkualitas dengan harga terjangkau dan pelayanan prima',
                    'established' => '2024'
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($contents as $content) {
            LandingContent::create($content);
        }
    }

    private function downloadImage($url, $filename)
    {
        try {
            $imageContent = file_get_contents($url);
            if ($imageContent !== false) {
                Storage::disk('public')->put('landing-content/' . $filename, $imageContent);
                echo "Downloaded: $filename\n";
            }
        } catch (Exception $e) {
            echo "Failed to download $filename: " . $e->getMessage() . "\n";
        }
    }
}