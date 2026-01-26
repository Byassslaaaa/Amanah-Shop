<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP001',
                'name' => 'PT Sumber Pangan Jaya',
                'contact_person' => 'Budi Santoso',
                'phone' => '021-12345678',
                'email' => 'budi@sumberpangan.com',
                'address' => 'Jl. Raya Industri No. 123, Jakarta Timur',
                'notes' => 'Supplier beras dan tepung-tepungan',
                'is_active' => true,
            ],
            [
                'code' => 'SUP002',
                'name' => 'CV Mitra Makmur',
                'contact_person' => 'Siti Rahayu',
                'phone' => '0274-567890',
                'email' => 'siti@mitramakmur.com',
                'address' => 'Jl. Malioboro No. 45, Yogyakarta',
                'notes' => 'Supplier produk keripik dan snack',
                'is_active' => true,
            ],
            [
                'code' => 'SUP003',
                'name' => 'UD Sejahtera Abadi',
                'contact_person' => 'Ahmad Hidayat',
                'phone' => '0361-234567',
                'email' => 'ahmad@sejahtera.co.id',
                'address' => 'Jl. Gatot Subroto No. 88, Denpasar',
                'notes' => 'Supplier bumbu dan rempah',
                'is_active' => true,
            ],
            [
                'code' => 'SUP004',
                'name' => 'Toko Grosir Berkah',
                'contact_person' => 'Ibu Yanti',
                'phone' => '0341-456789',
                'email' => 'yanti@berkah.com',
                'address' => 'Jl. Pasar Besar No. 12, Malang',
                'notes' => 'Supplier bahan baku jajanan pasar',
                'is_active' => true,
            ],
            [
                'code' => 'SUP005',
                'name' => 'PT Agro Nusantara',
                'contact_person' => 'Pak Joko',
                'phone' => '0251-789012',
                'email' => 'joko@agronusantara.co.id',
                'address' => 'Jl. Raya Bogor KM 35, Bogor',
                'notes' => 'Supplier produk pertanian organik',
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        $this->command->info('✅ ' . count($suppliers) . ' supplier berhasil dibuat!');
        $this->command->info('📋 Supplier: PT Sumber Pangan Jaya, CV Mitra Makmur, UD Sejahtera Abadi, dll');
    }
}
