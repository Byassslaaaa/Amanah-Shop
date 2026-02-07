<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders (no shop seeder - Amanah Shop is single shop)
        $this->call([
            \Database\Seeders\System\SettingSeeder::class,
            \Database\Seeders\Product\CategoryTypeSeeder::class,
            \Database\Seeders\Credit\InstallmentPlanSeeder::class,
            \Database\Seeders\Finance\TransactionCategorySeeder::class,
            // \Database\Seeders\SupplierSeeder::class, // Optional - uncomment jika butuh supplier
        ]);

        // Create SuperAdmin (owner of Amanah Shop)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@amanahshop.com',
            'password' => Hash::make('123'),
            'role' => 'superadmin',
            'phone' => '081234567890',
            'address' => 'Amanah Shop, Yogyakarta',
        ]);

        // Create Admin (staff Amanah Shop)
        User::create([
            'name' => 'Admin Amanah Shop',
            'email' => 'admin@amanahshop.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'phone' => '082136547891',
            'address' => 'Amanah Shop, Yogyakarta',
        ]);

        // Create regular users (customers)
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('123'),
            'role' => 'user',
            'phone' => '081234567899',
            'address' => 'Jl. Mawar No. 3, Jakarta',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('123'),
            'role' => 'user',
            'phone' => '081234567898',
            'address' => 'Jl. Melati No. 5, Bandung',
        ]);

        // Products will be created by ProductSeeder
        $this->call([
            ProductSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Amanah Shop - Database seeding completed!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Users: ' . User::count());
        $this->command->info('   - Installment Plans: ' . \App\Models\Credit\InstallmentPlan::count());
        $this->command->info('   - Transaction Categories: ' . \App\Models\Finance\TransactionCategory::count());

        // Only show supplier count if suppliers exist (seeder is optional)
        $supplierCount = \App\Models\Supplier::count();
        if ($supplierCount > 0) {
            $this->command->info('   - Suppliers: ' . $supplierCount . ' (opsional untuk inventory tracking)');
        }

        $this->command->info('   - Product Categories: ' . Category::count());
        $this->command->info('   - Products: ' . Product::count());
        $this->command->info('');
        $this->command->info('🔐 Login credentials:');
        $this->command->info('   SuperAdmin: superadmin@amanahshop.com / 123');
        $this->command->info('   Admin: admin@amanahshop.com / 123');
        $this->command->info('   User: budi@example.com / 123');
        $this->command->info('   User: siti@example.com / 123');
        $this->command->info('');
        $this->command->info('📱 WhatsApp Integration Ready!');
        $this->command->info('   - Admin phones: 081234567890, 082136547891');
        $this->command->info('   - Example: MASUK PROD0001 100 50000');
        $this->command->info('   - Supplier code opsional (untuk tracking supplier)');
    }
}
