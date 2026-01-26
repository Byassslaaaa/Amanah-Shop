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
                'title' => 'Selamat Datang di BUMDes Marketplace',
                'content' => 'Temukan dan beli produk lokal berkualitas langsung dari desa. Dukung ekonomi desa dan nikmati produk segar dari petani dan pengrajin lokal.',
                'image' => 'landing-content/hero-bg.jpg',
                'data' => json_encode([
                    'button_text' => 'Mulai Berbelanja',
                    'button_link' => '/products',
                    'subtitle' => 'Produk Lokal Berkualitas dari Desa untuk Anda'
                ]),
                'is_active' => true,
            ],
            [
                'key' => 'about-us',
                'title' => 'Tentang BUMDes Kami',
                'content' => 'BUMDes (Badan Usaha Milik Desa) adalah lembaga ekonomi desa yang dikelola oleh masyarakat dan pemerintahan desa. Kami berkomitmen untuk membangun ekonomi desa yang mandiri dan berkelanjutan melalui produk-produk unggulan lokal.',
                'image' => 'landing-content/about-us.jpg',
                'data' => json_encode([
                    'vision' => 'Menjadi pusat ekonomi desa yang mandiri dan berkelanjutan',
                    'mission' => 'Memberdayakan masyarakat desa melalui usaha produktif dan inovatif',
                    'established' => '2020'
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