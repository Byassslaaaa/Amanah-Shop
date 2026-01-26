<?php

namespace Database\Seeders\Finance;

use Illuminate\Database\Seeder;
use App\Models\Finance\TransactionCategory;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Income Categories
            [
                'name' => 'Penjualan Online',
                'type' => 'income',
                'description' => 'Pendapatan dari penjualan melalui website',
                'is_active' => true,
            ],
            [
                'name' => 'Penjualan Offline',
                'type' => 'income',
                'description' => 'Pendapatan dari penjualan langsung/toko',
                'is_active' => true,
            ],
            [
                'name' => 'Pembayaran Cicilan',
                'type' => 'income',
                'description' => 'Pendapatan dari pembayaran cicilan customer',
                'is_active' => true,
            ],
            [
                'name' => 'Bunga Pinjaman',
                'type' => 'income',
                'description' => 'Pendapatan bunga dari kredit customer',
                'is_active' => true,
            ],
            [
                'name' => 'Lain-lain',
                'type' => 'income',
                'description' => 'Pendapatan lainnya',
                'is_active' => true,
            ],

            // Expense Categories
            [
                'name' => 'Pembelian Barang',
                'type' => 'expense',
                'description' => 'Pengeluaran untuk pembelian stok barang',
                'is_active' => true,
            ],
            [
                'name' => 'Gaji Karyawan',
                'type' => 'expense',
                'description' => 'Pengeluaran gaji dan tunjangan karyawan',
                'is_active' => true,
            ],
            [
                'name' => 'Sewa Tempat',
                'type' => 'expense',
                'description' => 'Biaya sewa gedung/toko',
                'is_active' => true,
            ],
            [
                'name' => 'Listrik & Air',
                'type' => 'expense',
                'description' => 'Biaya utilitas',
                'is_active' => true,
            ],
            [
                'name' => 'Transport & Pengiriman',
                'type' => 'expense',
                'description' => 'Biaya pengiriman dan transportasi',
                'is_active' => true,
            ],
            [
                'name' => 'Perawatan & Perbaikan',
                'type' => 'expense',
                'description' => 'Biaya maintenance dan perbaikan',
                'is_active' => true,
            ],
            [
                'name' => 'Promosi & Marketing',
                'type' => 'expense',
                'description' => 'Biaya iklan dan promosi',
                'is_active' => true,
            ],
            [
                'name' => 'Operasional Lainnya',
                'type' => 'expense',
                'description' => 'Pengeluaran operasional lainnya',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TransactionCategory::create($category);
        }
    }
}
