<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Console\Command;

class TestMidtrans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:test
                            {action=info : Action to perform (info|create-token|check-status|webhook-test)}
                            {--order= : Order ID or Order Number to test with}
                            {--user= : User ID for creating test order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Midtrans payment integration';

    protected $midtransService;

    /**
     * Create a new command instance.
     */
    public function __construct(MidtransService $midtransService)
    {
        parent::__construct();
        $this->midtransService = $midtransService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        $this->info("=== Midtrans Payment Gateway Test ===\n");

        switch ($action) {
            case 'info':
                $this->showConfiguration();
                break;

            case 'create-token':
                $this->testCreateToken();
                break;

            case 'check-status':
                $this->testCheckStatus();
                break;

            case 'webhook-test':
                $this->testWebhook();
                break;

            default:
                $this->error("Unknown action: {$action}");
                $this->info("\nAvailable actions:");
                $this->info("  info          - Show Midtrans configuration");
                $this->info("  create-token  - Create payment token for an order");
                $this->info("  check-status  - Check payment status of an order");
                $this->info("  webhook-test  - Simulate webhook notification");
                break;
        }

        return 0;
    }

    /**
     * Show Midtrans configuration
     */
    protected function showConfiguration()
    {
        $this->info("Midtrans Configuration:");
        $this->table(
            ['Key', 'Value'],
            [
                ['Merchant ID', config('services.midtrans.merchant_id') ?: '(not set)'],
                ['Client Key', config('services.midtrans.client_key') ? substr(config('services.midtrans.client_key'), 0, 20) . '...' : '(not set)'],
                ['Server Key', config('services.midtrans.server_key') ? substr(config('services.midtrans.server_key'), 0, 20) . '...' : '(not set)'],
                ['Environment', config('services.midtrans.is_production') ? 'Production' : 'Sandbox'],
                ['3D Secure', config('services.midtrans.is_3ds') ? 'Enabled' : 'Disabled'],
                ['Sanitized', config('services.midtrans.is_sanitized') ? 'Yes' : 'No'],
            ]
        );

        // Check if configuration is complete
        $isConfigured = config('services.midtrans.client_key') &&
                       config('services.midtrans.server_key');

        if ($isConfigured) {
            $this->info("\n✓ Midtrans is configured properly");
        } else {
            $this->warn("\n⚠ Midtrans configuration incomplete!");
            $this->info("Please set these environment variables in your .env file:");
            $this->info("  MIDTRANS_MERCHANT_ID=your_merchant_id");
            $this->info("  MIDTRANS_CLIENT_KEY=your_client_key");
            $this->info("  MIDTRANS_SERVER_KEY=your_server_key");
            $this->info("  MIDTRANS_IS_PRODUCTION=false (for testing)");
        }

        // Show available routes
        $this->info("\nAvailable Routes:");
        $this->table(
            ['Route Name', 'URL'],
            [
                ['user.payment.show', route('user.payment.show', ['order' => 'ORDER_ID'])],
                ['user.payment.finish', route('user.payment.finish')],
                ['user.payment.notification', route('user.payment.notification') ?? 'Not configured'],
            ]
        );

        // Show recent orders
        $this->info("\nRecent Orders:");
        $orders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get(['id', 'order_number', 'user_id', 'total_amount', 'payment_status', 'created_at']);

        if ($orders->count() > 0) {
            $this->table(
                ['ID', 'Order Number', 'User', 'Amount', 'Payment Status', 'Created'],
                $orders->map(function ($order) {
                    return [
                        $order->id,
                        $order->order_number,
                        $order->user->name ?? 'N/A',
                        'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                        $order->payment_status,
                        $order->created_at->format('Y-m-d H:i'),
                    ];
                })->toArray()
            );
        } else {
            $this->warn("No orders found in database");
        }
    }

    /**
     * Test creating payment token
     */
    protected function testCreateToken()
    {
        $orderId = $this->option('order');

        if (!$orderId) {
            $this->error("Please specify order ID or order number with --order option");
            $this->info("Example: php artisan midtrans:test create-token --order=1");
            $this->info("         php artisan midtrans:test create-token --order=ORD-20250131-001");
            return;
        }

        // Find order
        $order = is_numeric($orderId)
            ? Order::find($orderId)
            : Order::where('order_number', $orderId)->first();

        if (!$order) {
            $this->error("Order not found: {$orderId}");
            return;
        }

        // Load relationships
        $order->load(['user', 'items.product', 'shippingAddress']);

        $this->info("Order Details:");
        $this->table(
            ['Field', 'Value'],
            [
                ['Order Number', $order->order_number],
                ['User', $order->user->name . ' (' . $order->user->email . ')'],
                ['Total Amount', 'Rp ' . number_format($order->total_amount, 0, ',', '.')],
                ['Shipping Cost', 'Rp ' . number_format($order->shipping_cost, 0, ',', '.')],
                ['Items Count', $order->items->count()],
                ['Payment Status', $order->payment_status],
            ]
        );

        if ($this->confirm('Create Midtrans payment token?', true)) {
            try {
                $this->info("\nCreating payment token...");
                $snapToken = $this->midtransService->createTransaction($order);

                // Update order
                $order->update([
                    'midtrans_snap_token' => $snapToken,
                    'midtrans_order_id' => $order->order_number,
                ]);

                $this->info("\n✓ Payment token created successfully!");
                $this->info("\nSnap Token: " . $snapToken);

                $paymentUrl = route('user.payment.show', $order);
                $this->info("\nPayment URL:");
                $this->line($paymentUrl);

                if (config('services.midtrans.is_production')) {
                    $this->warn("\n⚠ You are using PRODUCTION environment!");
                } else {
                    $this->info("\n📝 Test Card Numbers (Sandbox):");
                    $this->table(
                        ['Card Number', 'CVV', 'Exp', 'Type', 'Result'],
                        [
                            ['4811 1111 1111 1114', '123', '12/26', 'Visa', 'Success'],
                            ['5211 1111 1111 1117', '123', '12/26', 'Mastercard', 'Success'],
                            ['4911 1111 1111 1113', '123', '12/26', 'Visa', 'Challenge by FDS'],
                            ['4411 1111 1111 1118', '123', '12/26', 'Visa', 'Denied by Bank'],
                        ]
                    );
                }

            } catch (\Exception $e) {
                $this->error("\n✗ Error creating payment token:");
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Test checking payment status
     */
    protected function testCheckStatus()
    {
        $orderId = $this->option('order');

        if (!$orderId) {
            $this->error("Please specify order ID or order number with --order option");
            $this->info("Example: php artisan midtrans:test check-status --order=1");
            return;
        }

        // Find order
        $order = is_numeric($orderId)
            ? Order::find($orderId)
            : Order::where('order_number', $orderId)->first();

        if (!$order) {
            $this->error("Order not found: {$orderId}");
            return;
        }

        $this->info("Checking payment status for order: {$order->order_number}");

        try {
            $status = $this->midtransService->getTransactionStatus($order->order_number);

            $this->info("\n✓ Transaction Status Retrieved:");
            $this->table(
                ['Field', 'Value'],
                [
                    ['Order ID', $status->order_id ?? 'N/A'],
                    ['Transaction ID', $status->transaction_id ?? 'N/A'],
                    ['Transaction Status', $status->transaction_status ?? 'N/A'],
                    ['Transaction Time', $status->transaction_time ?? 'N/A'],
                    ['Payment Type', $status->payment_type ?? 'N/A'],
                    ['Gross Amount', isset($status->gross_amount) ? 'Rp ' . number_format($status->gross_amount, 0, ',', '.') : 'N/A'],
                    ['Fraud Status', $status->fraud_status ?? 'N/A'],
                ]
            );

            $this->info("\nCurrent Order Status in Database:");
            $this->table(
                ['Field', 'Value'],
                [
                    ['Payment Status', $order->payment_status],
                    ['Order Status', $order->status],
                    ['Snap Token', $order->midtrans_snap_token ? 'Set' : 'Not Set'],
                    ['Transaction ID', $order->midtrans_transaction_id ?? 'N/A'],
                ]
            );

        } catch (\Exception $e) {
            $this->error("\n✗ Error checking status:");
            $this->error($e->getMessage());
            $this->info("\nPossible reasons:");
            $this->info("  - Transaction not found in Midtrans");
            $this->info("  - Invalid server key");
            $this->info("  - Order has not been paid yet");
        }
    }

    /**
     * Test webhook notification
     */
    protected function testWebhook()
    {
        $this->info("Webhook Testing Guide:");
        $this->info("\nTo test Midtrans webhook notifications, you need to:");

        $this->info("\n1. Configure webhook URL in Midtrans Dashboard:");
        $webhookUrl = route('user.payment.notification');
        $this->line("   " . $webhookUrl);

        $this->info("\n2. For local testing, use ngrok or similar tool:");
        $this->line("   ngrok http 8000");
        $this->line("   Then update webhook URL to: https://your-ngrok-url.ngrok.io/payment/notification");

        $this->info("\n3. Make a test payment and check the logs");

        $this->info("\n4. You can also manually test with curl:");

        $order = Order::latest()->first();
        if ($order) {
            $this->info("\nSample curl command (replace with actual data from Midtrans):");
            $samplePayload = json_encode([
                'transaction_time' => now()->toIso8601String(),
                'transaction_status' => 'settlement',
                'transaction_id' => 'test-' . uniqid(),
                'status_message' => 'Success',
                'status_code' => '200',
                'signature_key' => 'dummy_signature',
                'payment_type' => 'credit_card',
                'order_id' => $order->order_number,
                'merchant_id' => config('services.midtrans.merchant_id'),
                'gross_amount' => (string) $order->total_amount,
                'fraud_status' => 'accept',
                'currency' => 'IDR',
            ], JSON_PRETTY_PRINT);

            $this->line("\ncurl -X POST " . $webhookUrl);
            $this->line("  -H 'Content-Type: application/json'");
            $this->line("  -d '" . $samplePayload . "'");
        }

        $this->warn("\n⚠ Note: The signature_key in the sample above is dummy.");
        $this->info("For real testing, use actual payments or Midtrans simulator.");
    }
}
