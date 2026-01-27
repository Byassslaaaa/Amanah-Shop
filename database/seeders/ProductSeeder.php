<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada category terlebih dahulu
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->info('Silakan jalankan CategorySeeder terlebih dahulu');
            return;
        }

        // Get categories by name for specific assignment
        $perabotan = $categories->where('name', 'Perabotan')->first();
        $perlengkapanKamarTidur = $categories->where('name', 'Perlengkapan Kamar Tidur')->first();
        $pakaian = $categories->where('name', 'Pakaian')->first();
        $sepatuAlasKaki = $categories->where('name', 'Sepatu & Alas Kaki')->first();
        $keperluanRumahTangga = $categories->where('name', 'Keperluan Rumah Tangga')->first();
        $tekstilRumah = $categories->where('name', 'Tekstil Rumah')->first();
        $aksesorisRumah = $categories->where('name', 'Aksesoris Rumah')->first();
        $lainLain = $categories->where('name', 'Lain-lain')->first();

        $products = [
            // PERABOTAN (Kategori 1)
            ['name' => 'Lemari Pakaian 2 Pintu', 'description' => 'Lemari pakaian 2 pintu kayu jati, ukuran 120x180x50 cm, dengan cermin di pintu', 'price' => 2500000, 'stock' => 10, 'category_id' => $perabotan->id ?? 1, 'images' => []],
            ['name' => 'Lemari Pakaian 3 Pintu', 'description' => 'Lemari pakaian 3 pintu kayu mahoni, ukuran 150x180x50 cm, dengan laci bawah', 'price' => 3200000, 'stock' => 8, 'category_id' => $perabotan->id ?? 1, 'images' => []],
            ['name' => 'Meja Belajar Minimalis', 'description' => 'Meja belajar minimalis dengan 2 laci, ukuran 100x50x75 cm, cocok untuk kamar tidur', 'price' => 850000, 'stock' => 15, 'category_id' => $perabotan->id ?? 1, 'images' => []],
            ['name' => 'Kursi Kantor Ergonomis', 'description' => 'Kursi kantor dengan sandaran tinggi, adjustable height, material kulit sintetis', 'price' => 950000, 'stock' => 20, 'category_id' => $perabotan->id ?? 1, 'images' => []],
            ['name' => 'Rak Buku Kayu Jati', 'description' => 'Rak buku 5 tingkat kayu jati, ukuran 80x150x30 cm, finishing natural', 'price' => 1250000, 'stock' => 12, 'category_id' => $perabotan->id ?? 1, 'images' => []],
            ['name' => 'Meja Makan Set 6 Kursi', 'description' => 'Set meja makan kayu jati dengan 6 kursi, ukuran meja 150x80 cm', 'price' => 4500000, 'stock' => 5, 'category_id' => $perabotan->id ?? 1, 'images' => []],
            ['name' => 'Bufet TV Minimalis', 'description' => 'Bufet TV minimalis kayu mahoni, ukuran 120x40x50 cm, dengan 2 laci', 'price' => 1800000, 'stock' => 10, 'category_id' => $perabotan->id ?? 1, 'images' => []],
            ['name' => 'Rak Sepatu 5 Tingkat', 'description' => 'Rak sepatu kayu 5 tingkat, muat 15 pasang sepatu, ukuran 60x100x25 cm', 'price' => 450000, 'stock' => 25, 'category_id' => $perabotan->id ?? 1, 'images' => []],

            // PERLENGKAPAN KAMAR TIDUR (Kategori 2)
            ['name' => 'Kasur Spring Bed Single', 'description' => 'Kasur spring bed single 90x200 cm, ketebalan 20 cm, dengan pillow top', 'price' => 1500000, 'stock' => 15, 'category_id' => $perlengkapanKamarTidur->id ?? 2, 'images' => []],
            ['name' => 'Kasur Spring Bed Queen', 'description' => 'Kasur spring bed queen 160x200 cm, ketebalan 25 cm, memory foam', 'price' => 3500000, 'stock' => 10, 'category_id' => $perlengkapanKamarTidur->id ?? 2, 'images' => []],
            ['name' => 'Bantal Tidur Silikon', 'description' => 'Bantal tidur silikon anti alergi, ukuran 40x60 cm, lembut dan empuk', 'price' => 85000, 'stock' => 50, 'category_id' => $perlengkapanKamarTidur->id ?? 2, 'images' => []],
            ['name' => 'Guling Silikon 100cm', 'description' => 'Guling silikon panjang 100 cm, diameter 20 cm, anti kempes', 'price' => 95000, 'stock' => 40, 'category_id' => $perlengkapanKamarTidur->id ?? 2, 'images' => []],
            ['name' => 'Sprei Katun Jepang Queen', 'description' => 'Sprei katun jepang queen size 160x200, motif minimalis, adem dan lembut', 'price' => 250000, 'stock' => 30, 'category_id' => $perlengkapanKamarTidur->id ?? 2, 'images' => []],
            ['name' => 'Selimut Bulu Halus', 'description' => 'Selimut bulu halus ukuran 150x200 cm, hangat dan lembut', 'price' => 180000, 'stock' => 35, 'category_id' => $perlengkapanKamarTidur->id ?? 2, 'images' => []],
            ['name' => 'Bed Cover Set King', 'description' => 'Bed cover set king size dengan 2 sarung bantal dan 2 sarung guling', 'price' => 350000, 'stock' => 20, 'category_id' => $perlengkapanKamarTidur->id ?? 2, 'images' => []],

            // TEKSTIL RUMAH (Kategori 3)
            ['name' => 'Karpet Bulu Korea 150x200', 'description' => 'Karpet bulu korea lembut, ukuran 150x200 cm, berbagai warna', 'price' => 350000, 'stock' => 25, 'category_id' => $tekstilRumah->id ?? 3, 'images' => []],
            ['name' => 'Karpet Bulu Korea 200x300', 'description' => 'Karpet bulu korea jumbo, ukuran 200x300 cm, tebal dan empuk', 'price' => 650000, 'stock' => 15, 'category_id' => $tekstilRumah->id ?? 3, 'images' => []],
            ['name' => 'Sajadah Turki Premium', 'description' => 'Sajadah turki premium dengan motif klasik, lembut dan tebal', 'price' => 120000, 'stock' => 40, 'category_id' => $tekstilRumah->id ?? 3, 'images' => []],
            ['name' => 'Tikar Rotan Pandan', 'description' => 'Tikar rotan pandan alami, ukuran 180x200 cm, sejuk dan awet', 'price' => 85000, 'stock' => 30, 'category_id' => $tekstilRumah->id ?? 3, 'images' => []],
            ['name' => 'Karpet Lantai Anti Slip', 'description' => 'Karpet lantai anti slip untuk kamar mandi, ukuran 60x80 cm', 'price' => 45000, 'stock' => 50, 'category_id' => $tekstilRumah->id ?? 3, 'images' => []],

            // PAKAIAN (Kategori 3)
            ['name' => 'Kemeja Formal Lengan Panjang', 'description' => 'Kemeja formal pria lengan panjang, bahan katun stretch, berbagai ukuran', 'price' => 150000, 'stock' => 40, 'category_id' => $pakaian->id ?? 4, 'images' => []],
            ['name' => 'Kemeja Casual Batik', 'description' => 'Kemeja batik casual lengan pendek, motif modern, cocok untuk santai', 'price' => 180000, 'stock' => 35, 'category_id' => $pakaian->id ?? 4, 'images' => []],
            ['name' => 'Celana Jeans Slim Fit', 'description' => 'Celana jeans pria slim fit, bahan denim stretch, nyaman dipakai', 'price' => 220000, 'stock' => 30, 'category_id' => $pakaian->id ?? 4, 'images' => []],
            ['name' => 'Celana Chino Formal', 'description' => 'Celana chino formal pria, bahan katun premium, cocok untuk kerja', 'price' => 195000, 'stock' => 35, 'category_id' => $pakaian->id ?? 4, 'images' => []],
            ['name' => 'Jaket Bomber Pria', 'description' => 'Jaket bomber pria model terbaru, bahan parasut, hangat dan stylish', 'price' => 250000, 'stock' => 25, 'category_id' => $pakaian->id ?? 4, 'images' => []],
            ['name' => 'Kaos Oblong Cotton Combed', 'description' => 'Kaos oblong pria cotton combed 30s, adem dan nyaman', 'price' => 55000, 'stock' => 60, 'category_id' => $pakaian->id ?? 4, 'images' => []],

            // PAKAIAN WANITA
            ['name' => 'Blouse Kerja Wanita', 'description' => 'Blouse kerja wanita lengan panjang, bahan sifon, elegan dan nyaman', 'price' => 135000, 'stock' => 40, 'category_id' => $pakaian->id ?? 5, 'images' => []],
            ['name' => 'Rok Plisket Panjang', 'description' => 'Rok plisket panjang wanita, bahan ceruti, cocok untuk formal', 'price' => 125000, 'stock' => 35, 'category_id' => $pakaian->id ?? 5, 'images' => []],
            ['name' => 'Dress Casual Motif', 'description' => 'Dress casual wanita motif cantik, bahan katun, nyaman untuk santai', 'price' => 165000, 'stock' => 30, 'category_id' => $pakaian->id ?? 5, 'images' => []],
            ['name' => 'Gamis Syari Premium', 'description' => 'Gamis syari premium dengan jilbab, bahan wolfis, adem dan jatuh', 'price' => 280000, 'stock' => 25, 'category_id' => $pakaian->id ?? 5, 'images' => []],
            ['name' => 'Jilbab Segiempat Voal', 'description' => 'Jilbab segiempat bahan voal, ukuran 110x110 cm, berbagai warna', 'price' => 45000, 'stock' => 80, 'category_id' => $pakaian->id ?? 5, 'images' => []],
            ['name' => 'Celana Kulot Wanita', 'description' => 'Celana kulot wanita bahan scuba, nyaman dan cocok untuk berbagai acara', 'price' => 115000, 'stock' => 35, 'category_id' => $pakaian->id ?? 5, 'images' => []],

            // SEPATU & ALAS KAKI (Kategori 4)
            ['name' => 'Sepatu Formal Pria Kulit', 'description' => 'Sepatu formal pria kulit asli, cocok untuk kerja dan acara formal', 'price' => 350000, 'stock' => 20, 'category_id' => $sepatuAlasKaki->id ?? 6, 'images' => []],
            ['name' => 'Sepatu Casual Sneakers', 'description' => 'Sepatu casual sneakers pria, nyaman untuk sehari-hari', 'price' => 280000, 'stock' => 30, 'category_id' => $sepatuAlasKaki->id ?? 6, 'images' => []],
            ['name' => 'Sepatu Wanita Hak Tinggi', 'description' => 'Sepatu wanita hak tinggi 7cm, cocok untuk pesta dan acara formal', 'price' => 295000, 'stock' => 25, 'category_id' => $sepatuAlasKaki->id ?? 6, 'images' => []],
            ['name' => 'Sepatu Wanita Flat Shoes', 'description' => 'Sepatu wanita flat shoes, nyaman dan cocok untuk kerja', 'price' => 175000, 'stock' => 35, 'category_id' => $sepatuAlasKaki->id ?? 6, 'images' => []],
            ['name' => 'Sandal Jepit Karet', 'description' => 'Sandal jepit karet berkualitas, nyaman dan anti slip', 'price' => 35000, 'stock' => 100, 'category_id' => $sepatuAlasKaki->id ?? 6, 'images' => []],
            ['name' => 'Sandal Gunung Outdoor', 'description' => 'Sandal gunung outdoor untuk hiking dan traveling', 'price' => 185000, 'stock' => 40, 'category_id' => $sepatuAlasKaki->id ?? 6, 'images' => []],
            ['name' => 'Sandal Selop Rumah', 'description' => 'Sandal selop rumah empuk dan nyaman, cocok untuk di dalam rumah', 'price' => 45000, 'stock' => 80, 'category_id' => $sepatuAlasKaki->id ?? 6, 'images' => []],
        ];

        $productCount = 0;
        foreach ($products as $index => $productData) {
            $sku = 'PROD' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $slug = Str::slug($productData['name']) . '-' . strtolower($sku);

            $product = Product::create(array_merge($productData, [
                'sku' => $sku,
                'slug' => $slug,
                'status' => 'active',
                'whatsapp_number' => '081234567890',
            ]));

            $productCount++;
        }

        $this->command->info("✅ {$productCount} produk berhasil dibuat untuk Amanah Shop!");
        $this->command->info('📦 Produk Amanah Shop: Perabotan, Kasur, Karpet, Tekstil, Pakaian, Sepatu, Aksesoris Rumah');
    }
}
