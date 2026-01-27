<?php

namespace Database\Seeders\Product;

use App\Models\Product\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories - Amanah Shop (Toko Kebutuhan Rumah Tangga & Lifestyle)
        $categories = [
            ['name' => 'Perabotan', 'description' => 'Lemari, meja, kursi, rak, dan berbagai furniture rumah', 'image' => 'images/categories/perabotan.png'],
            ['name' => 'Perlengkapan Kamar Tidur', 'description' => 'Kasur, bantal, guling, sprei, selimut, dan perlengkapan tidur', 'image' => 'images/categories/kamar-tidur.png'],
            ['name' => 'Pakaian', 'description' => 'Pakaian pria, wanita, dan anak-anak', 'image' => 'images/categories/pakaian.png'],
            ['name' => 'Sepatu & Alas Kaki', 'description' => 'Sepatu formal, casual, sandal, dan berbagai alas kaki', 'image' => 'images/categories/sepatu.png'],
            ['name' => 'Keperluan Rumah Tangga', 'description' => 'Peralatan dapur, kebersihan, dan keperluan rumah tangga lainnya', 'image' => 'images/categories/rumah-tangga.png'],
            ['name' => 'Tekstil Rumah', 'description' => 'Karpet, tikar, gorden, taplak, dan tekstil rumah', 'image' => 'images/categories/tekstil.png'],
            ['name' => 'Aksesoris Rumah', 'description' => 'Hiasan dinding, vas bunga, jam dinding, dan aksesoris dekorasi', 'image' => 'images/categories/aksesoris.png'],
            ['name' => 'Lain-lain', 'description' => 'Produk lainnya yang tidak masuk kategori di atas', 'image' => 'images/categories/lain-lain.png'],
        ];

        // Insert new categories only if they don't exist
        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('Categories have been seeded successfully!');
    }
}
