<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Credit\InstallmentPayment;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CheckOverduePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and mark overdue installment payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue installment payments...');

        $today = now()->startOfDay();

        // Find all pending installments that are past their due date
        $overdueInstallments = InstallmentPayment::where('status', 'pending')
            ->where('due_date', '<', $today)
            ->get();

        if ($overdueInstallments->isEmpty()) {
            $this->info('No overdue payments found.');
            return 0;
        }

        $this->info("Found {$overdueInstallments->count()} overdue payment(s).");

        DB::beginTransaction();

        try {
            $updatedCount = 0;
            $orderIds = [];

            foreach ($overdueInstallments as $installment) {
                // Mark as overdue
                $installment->update(['status' => 'overdue']);
                $updatedCount++;

                // Collect order IDs for batch update
                if (!in_array($installment->order_id, $orderIds)) {
                    $orderIds[] = $installment->order_id;
                }

                $this->line("- Installment #{$installment->payment_number} for Order #{$installment->order->order_number} marked as overdue");
            }

            // Update related orders payment status
            if (!empty($orderIds)) {
                Order::whereIn('id', $orderIds)
                    ->where('payment_status', 'installment_active')
                    ->update(['payment_status' => 'installment_overdue']);

                $this->info("Updated payment status for " . count($orderIds) . " order(s).");
            }

            DB::commit();

            $this->info("Successfully marked {$updatedCount} payment(s) as overdue.");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();

            $this->error('Error updating overdue payments: ' . $e->getMessage());

            return 1;
        }
    }
}
