<?php

namespace Database\Seeders\Credit;

use App\Models\Credit\InstallmentPlan;
use Illuminate\Database\Seeder;

class InstallmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => '3 Bulan',
                'months' => 3,
                'interest_rate' => 5.00, // 5% flat
                'description' => 'Cicilan 3 bulan dengan bunga flat 5%',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => '6 Bulan',
                'months' => 6,
                'interest_rate' => 8.00, // 8% flat
                'description' => 'Cicilan 6 bulan dengan bunga flat 8%',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => '12 Bulan',
                'months' => 12,
                'interest_rate' => 12.00, // 12% flat
                'description' => 'Cicilan 12 bulan dengan bunga flat 12%',
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => '24 Bulan',
                'months' => 24,
                'interest_rate' => 20.00, // 20% flat
                'description' => 'Cicilan 24 bulan dengan bunga flat 20%',
                'is_active' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            InstallmentPlan::create($plan);
        }

        $this->command->info('✅ Installment plans created! (3mo, 6mo, 12mo, 24mo)');
    }
}
