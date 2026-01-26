<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\BiteshipService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateShippingTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:update {--order_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-update shipping tracking status from Biteship API';

    protected $biteshipService;

    public function __construct(BiteshipService $biteshipService)
    {
        parent::__construct();
        $this->biteshipService = $biteshipService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting automatic tracking update...');

        // Get orders that are shipped but not yet delivered
        $query = Order::where('status', 'shipped')
            ->whereNotNull('shipping_resi')
            ->whereNotNull('shipping_courier')
            ->where(function($q) {
                $q->where('shipping_status', '!=', 'delivered')
                  ->orWhereNull('shipping_status');
            });

        // If specific order_id provided
        if ($orderId = $this->option('order_id')) {
            $query->where('id', $orderId);
        }

        $orders = $query->get();

        if ($orders->isEmpty()) {
            $this->warn('⚠️  No orders to update.');
            return 0;
        }

        $this->info("📦 Found {$orders->count()} order(s) to update.");

        $successCount = 0;
        $errorCount = 0;

        foreach ($orders as $order) {
            $this->line("Processing Order #{$order->order_number} (Resi: {$order->shipping_resi})...");

            try {
                // Get tracking data from Biteship
                $tracking = $this->biteshipService->track($order->shipping_resi);

                if (!$tracking['success']) {
                    $this->error("  ❌ Failed: {$tracking['message']}");
                    $errorCount++;

                    Log::warning('Tracking update failed', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'resi' => $order->shipping_resi,
                        'error' => $tracking['message']
                    ]);

                    continue;
                }

                // Parse tracking data
                $trackingData = $tracking['data'];
                $status = $this->biteshipService->parseStatus($trackingData);
                $history = $this->biteshipService->getTrackingHistory($trackingData);

                // Check if status changed
                $oldStatus = $order->shipping_status;
                $statusChanged = $oldStatus !== $status;

                // Update order
                $updateData = [
                    'shipping_status' => $status,
                    'shipping_history' => $history,
                    'tracking_updated_at' => now(),
                ];

                // If delivered, mark order as completed
                if ($status === 'delivered' && !$order->isDelivered()) {
                    $updateData['delivered_at'] = now();
                    $updateData['status'] = 'completed';
                    $updateData['completed_at'] = now();

                    $this->info("  🎉 Order DELIVERED!");
                }

                $order->update($updateData);

                if ($statusChanged) {
                    $this->info("  ✅ Status updated: {$oldStatus} → {$status}");
                } else {
                    $this->line("  ℹ️  Status unchanged: {$status}");
                }

                $successCount++;

                Log::info('Tracking updated successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'old_status' => $oldStatus,
                    'new_status' => $status,
                    'status_changed' => $statusChanged
                ]);

            } catch (\Exception $e) {
                $this->error("  ❌ Exception: {$e->getMessage()}");
                $errorCount++;

                Log::error('Tracking update exception', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->newLine();
        $this->info("✅ Update completed!");
        $this->info("   Success: {$successCount}");
        $this->info("   Failed: {$errorCount}");

        return 0;
    }
}
