<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Village;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = [
            [
                'name' => 'Desa Sendangsari',
                'slug' => 'desa-sendangsari',
                'description' => 'Desa Sendangsari adalah desa di Kecamatan Pengasih, Kulon Progo, Daerah Istimewa Yogyakarta. Desa ini memiliki berbagai produk UMKM unggulan seperti hasil pertanian, kerajinan tangan, makanan olahan, dan produk lokal berkualitas tinggi yang siap dipasarkan secara online.',
                'address' => 'Desa Sendangsari, Kecamatan Pengasih',
                'province' => 'Daerah Istimewa Yogyakarta',
                'city' => 'Kulon Progo',
                'district' => 'Pengasih',
                'phone' => '0274773456',
                'email' => 'bumdes@sendangsari-kulonprogo.desa.id',
                'whatsapp' => '6282136547890',
                'status' => 'active',
            ],
        ];

        foreach ($villages as $village) {
            Village::create($village);
        }
    }
}
