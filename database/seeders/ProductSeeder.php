<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Village;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada category dan village terlebih dahulu
        $categories = Category::all();
        $villages = Village::all();

        if ($categories->isEmpty() || $villages->isEmpty()) {
            $this->command->info('Silakan jalankan CategorySeeder dan VillageSeeder terlebih dahulu');
            return;
        }

        // Get categories by name for specific assignment
        $makananMinuman = $categories->where('name', 'Makanan & Minuman')->first();
        $pertanian = $categories->where('name', 'Pertanian')->first();
        $olehOleh = $categories->where('name', 'Oleh-oleh')->first();
        $kerajinan = $categories->where('name', 'Kerajinan Tangan')->first();
        $jasaKesehatan = $categories->where('name', 'Jasa Kesehatan')->first();

        $products = [
            // HALAMAN 1 - Produk Bunga Telang - UMKM Nani Eka Arisusanda
            ['name' => 'Keripik Telang Kemasan Souvenir 130 Gram', 'description' => 'Keripik telang dari UMKM Nani Eka Arisusanda', 'price' => 20000, 'stock' => 100, 'village_id' => 1, 'category_id' => $olehOleh->id, 'images' => ['images/products/keripik-telang-souvenir-130g.jpg']],
            ['name' => 'Telang Tubruk Kemasan Retail 30 Gram', 'description' => 'Teh bunga telang tubruk dari UMKM Nani Eka Arisusanda', 'price' => 25000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => ['images/products/telang-tubruk-retail-30g.jpg']],
            ['name' => 'Telang Tubruk Kemasan Souvenir 30 Gram', 'description' => 'Teh bunga telang tubruk kemasan souvenir dari UMKM Nani Eka Arisusanda', 'price' => 30000, 'stock' => 80, 'village_id' => 1, 'category_id' => $olehOleh->id, 'images' => ['images/products/telang-tubruk-souvenir-30g.jpg']],

            // Produk Garut - UMKM Bu Basinah
            ['name' => 'UMKM Emping Garut Bu Basinah', 'description' => 'Emping garut dari UMKM Bu Basinah', 'price' => 35000, 'stock' => 60, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => ['images/products/emping-garut-1kg.jpg']],
            ['name' => 'Tepung Garut', 'description' => 'Tepung garut berkualitas dari UMKM Bu Basinah', 'price' => 50000, 'stock' => 80, 'village_id' => 1, 'category_id' => $pertanian->id, 'images' => []],

            // Produk Mocaf & Olahan - UMKM Yuliana
            ['name' => 'Tepung Mocaf 400 gram', 'description' => 'Cocok untuk digunakan sebagai bahan untuk aneka olahan', 'price' => 20000, 'stock' => 150, 'village_id' => 1, 'category_id' => $pertanian->id, 'images' => ['images/products/tepung-mocaf-400g.jpg']],
            ['name' => 'Keripik Pisang 200 gram', 'description' => 'Nikmat rasanya bikin ketagihan', 'price' => 27000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => ['images/products/keripik-pisang-200g.jpg']],
            ['name' => 'Ceriping Pisang 150 Gram', 'description' => 'Ceriping pisang dari UMKM Yuliana', 'price' => 15000, 'stock' => 120, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => ['images/products/ceriping-pisang-150g.jpg']],
            ['name' => 'Stik Mocaf', 'description' => 'Enak, alami, sehat dan Renyah', 'price' => 15000, 'stock' => 90, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => ['images/products/stik-mocaf.jpg']],
            ['name' => 'Pati Garut 500 Gram', 'description' => 'Pati garut dari UMKM Yuliana', 'price' => 26000, 'stock' => 70, 'village_id' => 1, 'category_id' => $pertanian->id, 'images' => ['images/products/pati-garut-500g.jpg']],
            ['name' => 'Mie Maio-Ku (Mie Rebus)', 'description' => 'Mie rebus Maio-Ku dari UMKM Yuliana', 'price' => 10000, 'stock' => 200, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => ['images/products/mie-maio-ku-rebus.jpg']],
            ['name' => 'Mie Maio-Ku (Mie Goreng)', 'description' => 'Mie goreng Maio-Ku dari UMKM Yuliana', 'price' => 10000, 'stock' => 200, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => ['images/products/mie-maio-ku-goreng.jpg']],
            ['name' => 'Tepung Ubi Jalar Ungu 500 Gram', 'description' => 'Tepung ubi jalar ungu dari UMKM Yuliana', 'price' => 20000, 'stock' => 85, 'village_id' => 1, 'category_id' => $pertanian->id, 'images' => ['images/products/tepung-ubi-jalar-ungu-500g.jpg']],

            // Produk Tepung & Teh - UMKM Kemin
            ['name' => 'Tepung Ubi Ungu', 'description' => 'Tepung ubi ungu dari UMKM Kemin', 'price' => 36000, 'stock' => 60, 'village_id' => 1, 'category_id' => $pertanian->id, 'images' => []],
            ['name' => 'Tepung Buah Pisang Original', 'description' => 'Tepung buah pisang original dari UMKM Kemin', 'price' => 30000, 'stock' => 50, 'village_id' => 1, 'category_id' => $pertanian->id, 'images' => []],
            ['name' => 'Teh Bunga Telang', 'description' => 'Teh bunga telang dari UMKM Kemin', 'price' => 20000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'images' => []],

            // HALAMAN 2
            ['name' => 'Tepung Mocaf', 'description' => 'Tepung mocaf dari UMKM Kemin', 'price' => 20000, 'stock' => 100, 'village_id' => 1, 'category_id' => $pertanian->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Empek Empek Cucum', 'description' => 'Empek empek cucum dari UMKM Niken Fransiska', 'price' => 5000, 'stock' => 150, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Krimpying', 'description' => 'Krimpying dari UMKM Dadiyem', 'price' => 22000, 'stock' => 80, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Sabut Kelapa Berdikari', 'description' => 'Sabut kelapa berdikari dari UMKM Isgiyanto', 'price' => 4500, 'stock' => 100, 'village_id' => 1, 'category_id' => $kerajinan->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Kreyeng', 'description' => 'Kerajinan kreyeng dari UMKM Sagi', 'price' => 15000, 'stock' => 50, 'village_id' => 1, 'category_id' => $kerajinan->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Kandang Ayam', 'description' => 'Kandang ayam dari UMKM Sagi', 'price' => 70000, 'stock' => 30, 'village_id' => 1, 'category_id' => $pertanian->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Terapi Pijit Cidera', 'description' => 'Layanan terapi pijit cidera dari UMKM Suyanti', 'price' => 40000, 'stock' => 50, 'village_id' => 1, 'category_id' => $jasaKesehatan->id, 'type' => 'jasa', 'images' => ['images/products/terapi-pijit-cidera.jpg']],
            ['name' => 'Pijat Bayi', 'description' => 'Layanan pijat bayi dari UMKM Suyanti', 'price' => 35000, 'stock' => 50, 'village_id' => 1, 'category_id' => $jasaKesehatan->id, 'type' => 'jasa', 'images' => ['images/products/pijat-bayi.jpg']],
            ['name' => 'Jamu Racikan Tradisional', 'description' => 'Jamu racikan tradisional dari UMKM Suyanti', 'price' => 13000, 'stock' => 100, 'village_id' => 1, 'category_id' => $jasaKesehatan->id, 'type' => 'barang', 'images' => ['images/products/jamu-racikan-tradisional.jpg']],
            ['name' => 'Nasi Box UMKM Kayati', 'description' => 'Nasi box dari UMKM Kayati', 'price' => 15000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/nasi-box-kayati.jpg']],
            ['name' => 'Snack Box UMKM Kayati', 'description' => 'Snack box dari UMKM Kayati', 'price' => 5000, 'stock' => 150, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/snack-box-kayati.jpg']],
            ['name' => 'Jajanan Pasar UMKM Kayati', 'description' => 'Jajanan pasar dari UMKM Kayati', 'price' => 1000, 'stock' => 200, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/jajanan-pasar-kayati.jpg']],
            ['name' => 'Aneka Sayur Matang', 'description' => 'Aneka sayur matang dari UMKM Kuswantinah', 'price' => 5000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Kacang Bawang', 'description' => 'Kacang bawang dari UMKM Sri Rokimah', 'price' => 50000, 'stock' => 60, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/kacang-bawang.jpg']],
            ['name' => 'Peyek Ser', 'description' => 'Peyek ser dari UMKM Sri Rokimah', 'price' => 50000, 'stock' => 60, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/peyek-ser.jpg']],
            ['name' => 'Criping Pisang Tanduk', 'description' => 'Criping pisang tanduk dari UMKM Patmini', 'price' => 10000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/criping-pisang-tanduk.jpg']],

            // HALAMAN 3
            ['name' => 'Catring', 'description' => 'Catering dari RR. Murniningsih S.Psi', 'price' => 13000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/catering.jpg']],
            ['name' => 'Kripik Telang', 'description' => 'Kripik telang dari UMKM Puji Lestari', 'price' => 17000, 'stock' => 80, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/kripik-telang-puji.jpg']],
            ['name' => 'Krispi Bangged (Bunga Pisang)', 'description' => 'Krispi bunga pisang dari UMKM Febriyanti', 'price' => 25000, 'stock' => 70, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/krispi-bunga-pisang.jpg']],
            ['name' => 'Wedang Uwuh', 'description' => 'Wedang uwuh dari UMKM Febriyanti', 'price' => 25000, 'stock' => 80, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/wedang-uwuh.jpg']],
            ['name' => 'Nasi Kuning', 'description' => 'Nasi kuning dari UMKM Purmiyati', 'price' => 4000, 'stock' => 150, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/nasi-kuning.jpg']],
            ['name' => 'Aneka Jajanan Angkringan', 'description' => 'Aneka jajanan angkringan dari UMKM Triyani', 'price' => 2000, 'stock' => 200, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/jajanan-angkringan.jpg']],
            ['name' => 'Es Capur', 'description' => 'Es capur dari UMKM Triyani', 'price' => 5000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/es-capur.jpg']],
            ['name' => 'Aneka Kue Lebaran', 'description' => 'Aneka kue lebaran dari UMKM Seniyem', 'price' => 25000, 'stock' => 60, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/kue-lebaran.jpg']],
            ['name' => 'Buket Hempers', 'description' => 'Buket hampers dari UMKM Seniyem', 'price' => 20000, 'stock' => 50, 'village_id' => 1, 'category_id' => $olehOleh->id, 'type' => 'barang', 'images' => ['images/products/buket-hampers.jpg']],
            ['name' => 'Biofarmaka Botol', 'description' => 'Biofarmaka botol dari UMKM Sutinem', 'price' => 7000, 'stock' => 120, 'village_id' => 1, 'category_id' => $jasaKesehatan->id, 'type' => 'barang', 'images' => ['images/products/biofarmaka-botol.jpg']],
            ['name' => 'Biofarmaka Instan', 'description' => 'Biofarmaka instan dari UMKM Sutinem', 'price' => 12000, 'stock' => 100, 'village_id' => 1, 'category_id' => $jasaKesehatan->id, 'type' => 'barang', 'images' => ['images/products/biofarmaka-instan.jpg']],
            ['name' => 'Kripik Pisang Kepok', 'description' => 'Kripik pisang kepok dari UMKM Martini', 'price' => 15000, 'stock' => 90, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Geblek Tempe', 'description' => 'Geblek tempe dari UMKM Sarinem', 'price' => 10000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/geblek-tempe.jpg']],
            ['name' => 'Bakso Daging Ayam dan Sapi', 'description' => 'Bakso daging ayam dan sapi dari UMKM Purwaningsih', 'price' => 50000, 'stock' => 70, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/bakso-daging.jpg']],
            ['name' => 'Kreni Daging Ayam', 'description' => 'Kreni daging ayam dari UMKM Purwaningsih', 'price' => 100000, 'stock' => 50, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/kreni-daging-ayam.jpg']],
            ['name' => 'Tahu Bakso', 'description' => 'Tahu bakso dari UMKM Purwaningsih', 'price' => 1500, 'stock' => 150, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/tahu-bakso.jpg']],

            // HALAMAN 4
            ['name' => 'Telur Bebek Asin', 'description' => 'Telur bebek asin dari UMKM Sugiyanti', 'price' => 3000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/telur-bebek-asin.jpg']],
            ['name' => 'Toko Kelontong', 'description' => 'Produk toko kelontong dari UMKM Suparilah', 'price' => 2000, 'stock' => 200, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => []],
            ['name' => 'Nasi Box Roro Rumpoko Wati', 'description' => 'Nasi box dari Roro Rumpoko Wati', 'price' => 12000, 'stock' => 100, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/nasi-box-roro.jpg']],
            ['name' => 'Snack Box Roro Rumpoko Wati', 'description' => 'Snack box dari Roro Rumpoko Wati', 'price' => 5000, 'stock' => 150, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/snack-box-roro.jpg']],
            ['name' => 'Jajanan Pasar Roro Rumpoko Wati', 'description' => 'Jajanan pasar dari Roro Rumpoko Wati', 'price' => 1000, 'stock' => 200, 'village_id' => 1, 'category_id' => $makananMinuman->id, 'type' => 'barang', 'images' => ['images/products/jajanan-pasar-roro.jpg']],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => \Illuminate\Support\Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'village_id' => $product['village_id'],
                'category_id' => $product['category_id'],
                'type' => $product['type'] ?? 'barang',
                'images' => $product['images'] ?? [],
                'whatsapp_number' => null,
                'status' => 'active',
            ]);
        }

        $this->command->info('✅ ' . count($products) . ' produk berhasil dibuat untuk Desa Sendangsari!');
        $this->command->info('📦 Produk UMKM: Keripik Telang, Tepung Garut, Tepung Mocaf, Keripik Pisang, dan produk lokal lainnya');
    }
}
