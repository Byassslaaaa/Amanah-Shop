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
        // Update existing categories with types instead of truncating
        // Set all existing categories to barang type by default
        Category::whereNull('type')->orWhere('type', '')->update(['type' => 'barang']);

        // Categories for Barang (Products)
        $barangCategories = [
            ['name' => 'Makanan & Minuman', 'type' => 'barang', 'description' => 'Produk makanan dan minuman lokal', 'image' => 'images/categories/makanan-minuman.png'],
            ['name' => 'Kerajinan Tangan', 'type' => 'barang', 'description' => 'Kerajinan tangan khas daerah', 'image' => 'images/categories/kerajinan-tangan.png'],
            ['name' => 'Pakaian & Aksesoris', 'type' => 'barang', 'description' => 'Pakaian dan aksesoris tradisional', 'image' => 'images/categories/fashion.png'],
            ['name' => 'Pertanian', 'type' => 'barang', 'description' => 'Hasil pertanian dan produk organik', 'image' => 'images/categories/pertanian.png'],
            ['name' => 'Perikanan', 'type' => 'barang', 'description' => 'Hasil perikanan dan olahan ikan', 'image' => 'images/categories/peternakan.png'],
            ['name' => 'Oleh-oleh', 'type' => 'barang', 'description' => 'Souvenir dan oleh-oleh khas desa', 'image' => 'images/categories/oleh-oleh.png'],
        ];

        // Categories for Jasa (Services)
        $jasaCategories = [
            ['name' => 'Jasa Pertanian', 'type' => 'jasa', 'description' => 'Layanan konsultasi dan pengolahan pertanian'],
            ['name' => 'Jasa Konstruksi', 'type' => 'jasa', 'description' => 'Layanan konstruksi dan renovasi'],
            ['name' => 'Jasa Transportasi', 'type' => 'jasa', 'description' => 'Layanan transportasi lokal'],
            ['name' => 'Jasa Pendidikan', 'type' => 'jasa', 'description' => 'Layanan pendidikan dan pelatihan'],
            ['name' => 'Jasa Kesehatan', 'type' => 'jasa', 'description' => 'Layanan kesehatan dan pengobatan tradisional'],
            ['name' => 'Jasa Digital', 'type' => 'jasa', 'description' => 'Layanan digital dan teknologi'],
        ];

        // Insert new categories only if they don't exist
        foreach ($barangCategories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        foreach ($jasaCategories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('Categories with types have been seeded successfully!');
    }
}
