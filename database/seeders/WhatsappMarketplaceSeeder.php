<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\User;
use Illuminate\Support\Str;

class WhatsappMarketplaceSeeder extends Seeder
{
    public function run()
    {
        // Create categories if they don't exist
        $categories = [
            [
                'name' => 'Makanan & Minuman',
                'type' => 'barang',
                'description' => 'Produk makanan dan minuman lokal'
            ],
            [
                'name' => 'Kerajinan Tangan',
                'type' => 'barang',
                'description' => 'Hasil kerajinan tangan dari warga desa'
            ],
            [
                'name' => 'Pertanian',
                'type' => 'barang',
                'description' => 'Hasil pertanian dan perkebunan'
            ],
            [
                'name' => 'Jasa Layanan',
                'type' => 'jasa',
                'description' => 'Berbagai jasa layanan dari warga'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }

        // Get categories for products
        $makananCategory = Category::where('name', 'Makanan & Minuman')->first();
        $kerajinanCategory = Category::where('name', 'Kerajinan Tangan')->first();
        $pertanianCategory = Category::where('name', 'Pertanian')->first();
        $jasaCategory = Category::where('name', 'Jasa Layanan')->first();

        // Sample products with WhatsApp numbers
        $products = [
            [
                'name' => 'Beras Premium Organik 5kg',
                'description' => 'Beras premium organik langsung dari petani lokal. Tanpa pestisida dan bahan kimia berbahaya. Tekstur pulen dan aroma wangi alami.',
                'price' => 75000,
                'stock' => 50,
                'category_id' => $pertanianCategory->id,
                'type' => 'barang',
                'whatsapp_number' => '081234567890',
                'status' => 'active'
            ],
            [
                'name' => 'Kerupuk Udang Homemade',
                'description' => 'Kerupuk udang buatan rumahan dengan resep turun temurun. Renyah, gurih, dan terbuat dari udang asli tanpa pengawet.',
                'price' => 25000,
                'stock' => 30,
                'category_id' => $makananCategory->id,
                'type' => 'barang',
                'whatsapp_number' => '082345678901',
                'status' => 'active'
            ],
            [
                'name' => 'Tas Anyaman Pandan',
                'description' => 'Tas cantik dari anyaman pandan asli. Ramah lingkungan, tahan lama, dan cocok untuk berbagai acara. Tersedia berbagai warna.',
                'price' => 150000,
                'stock' => 15,
                'category_id' => $kerajinanCategory->id,
                'type' => 'barang',
                'whatsapp_number' => '083456789012',
                'status' => 'active'
            ],
            [
                'name' => 'Madu Hutan Murni 500ml',
                'description' => 'Madu hutan asli dari lebah liar. 100% murni tanpa campuran gula atau bahan lainnya. Khasiat alami untuk kesehatan.',
                'price' => 85000,
                'stock' => 25,
                'category_id' => $pertanianCategory->id,
                'type' => 'barang',
                'whatsapp_number' => '084567890123',
                'status' => 'active'
            ],
            [
                'name' => 'Jasa Catering Harian',
                'description' => 'Layanan catering untuk kebutuhan sehari-hari. Menu beragam, fresh, dan higienis. Cocok untuk kantor, acara keluarga, atau kebutuhan harian.',
                'price' => 20000,
                'stock' => 999,
                'category_id' => $jasaCategory->id,
                'type' => 'jasa',
                'whatsapp_number' => '085678901234',
                'status' => 'active'
            ],
            [
                'name' => 'Sayuran Organik Mix 2kg',
                'description' => 'Paket sayuran organik segar campuran. Terdiri dari bayam, kangkung, sawi, dan tomat. Langsung dari kebun tanpa pestisida.',
                'price' => 35000,
                'stock' => 40,
                'category_id' => $pertanianCategory->id,
                'type' => 'barang',
                'whatsapp_number' => '086789012345',
                'status' => 'active'
            ],
            [
                'name' => 'Kopi Robusta Bubuk 250gr',
                'description' => 'Kopi robusta asli dari perkebunan lokal. Disangrai dengan teknik traditional untuk cita rasa yang khas dan aroma yang menggugah.',
                'price' => 45000,
                'stock' => 35,
                'category_id' => $makananCategory->id,
                'type' => 'barang',
                'whatsapp_number' => '087890123456',
                'status' => 'active'
            ],
            [
                'name' => 'Jasa Massage Traditional',
                'description' => 'Layanan pijat traditional untuk relaksasi dan kesehatan. Menggunakan teknik turun temurun dengan minyak herbal alami.',
                'price' => 50000,
                'stock' => 999,
                'category_id' => $jasaCategory->id,
                'type' => 'jasa',
                'whatsapp_number' => '088901234567',
                'status' => 'active'
            ]
        ];

        foreach ($products as $productData) {
            // Generate slug
            $productData['slug'] = Str::slug($productData['name']) . '-' . Str::random(6);
            
            Product::create($productData);
        }

        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@bumdes.com'],
            [
                'name' => 'Admin BUMDes',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Kantor BUMDes Desa Maju'
            ]
        );

        echo "WhatsApp Marketplace seeder completed!\n";
        echo "Products created with WhatsApp integration\n";
        echo "Admin login: admin@bumdes.com / password\n";
    }
}