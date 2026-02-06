<?php

namespace Database\Seeders\System;

use App\Models\System\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'admin_email',
                'value' => 'admin@amanah.shop',
                'description' => 'Email Admin untuk menerima pesan kontak'
            ],
            [
                'key' => 'admin_whatsapp',
                'value' => '081234567890',
                'description' => 'Nomor WhatsApp Admin untuk layanan pelanggan'
            ],
            // SMTP Email Configuration
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'description' => 'SMTP Host (contoh: smtp.gmail.com)'
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'description' => 'SMTP Port (587 untuk TLS, 465 untuk SSL)'
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'description' => 'Email username untuk SMTP'
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'description' => 'Password email atau App Password'
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'tls',
                'description' => 'Enkripsi SMTP (tls atau ssl)'
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'Amanah Shop',
                'description' => 'Nama pengirim email'
            ],
            // Shipping Origin Coordinates (for Biteship)
            [
                'key' => 'shipping_origin_latitude',
                'value' => '-6.200000',
                'description' => 'Latitude koordinat toko (untuk perhitungan ongkir)'
            ],
            [
                'key' => 'shipping_origin_longitude',
                'value' => '106.816666',
                'description' => 'Longitude koordinat toko (untuk perhitungan ongkir)'
            ],
            [
                'key' => 'shipping_origin_address',
                'value' => 'Jakarta, Indonesia',
                'description' => 'Alamat toko (untuk tampilan)'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'description' => $setting['description']]
            );
        }

        $this->command->info('Default settings have been created successfully!');
    }
}
