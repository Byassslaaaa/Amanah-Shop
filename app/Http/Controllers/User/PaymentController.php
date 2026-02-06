<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Show payment page with Midtrans Snap
     */
    public function show(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini');
        }

        // Jika order sudah dibayar, redirect ke detail order
        if ($order->payment_status === 'paid') {
            return redirect()->route('user.orders.show', $order)
                ->with('info', 'Order ini sudah dibayar');
        }

        // Jika belum ada snap token, generate
        if (!$order->midtrans_snap_token) {
            try {
                $snapToken = $this->midtransService->createTransaction($order);
                $order->update([
                    'midtrans_snap_token' => $snapToken,
                    'midtrans_order_id' => $order->order_number,
                ]);
            } catch (\Exception $e) {
                return redirect()->route('user.orders.show', $order)
                    ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
            }
        }

        return view('user.payment.show', compact('order'));
    }

    /**
     * Verify Midtrans webhook signature
     */
    protected function verifyMidtransSignature(Request $request): bool
    {
        $serverKey = config('services.midtrans.server_key');
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        // Generate signature
        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($mySignature, $signatureKey);
    }

    /**
     * Handle payment notification from Midtrans (Webhook)
     */
    public function notification(Request $request)
    {
        try {
            // ⚠️ CRITICAL: Verify webhook signature to prevent fake notifications
            if (!$this->verifyMidtransSignature($request)) {
                \Log::warning('Invalid Midtrans webhook signature detected', [
                    'ip' => $request->ip(),
                    'order_id' => $request->order_id,
                    'headers' => $request->headers->all(),
                ]);

                return response()->json([
                    'message' => 'Invalid signature'
                ], 403);
            }

            // Log incoming notification
            \Log::info('Midtrans Notification Received', [
                'body' => $request->all(),
            ]);

            $notification = $this->midtransService->handleNotification();

            // Log processed notification data
            \Log::info('Midtrans Notification Processed', [
                'notification' => $notification,
            ]);

            // Find order by order number (handle -DP suffix for down payment)
            $orderNumber = $notification['order_number'];

            // Remove -DP suffix if present (for down payment transactions)
            if (str_ends_with($orderNumber, '-DP')) {
                $orderNumber = substr($orderNumber, 0, -3);
            }

            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                \Log::error('Order not found for notification', [
                    'original_order_number' => $notification['order_number'],
                    'normalized_order_number' => $orderNumber,
                ]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // ⚠️ IDEMPOTENCY CHECK: Prevent double-processing
            if ($order->payment_status === 'paid' && $notification['payment_status'] === 'paid') {
                \Log::info('Payment already processed (idempotency check)', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'transaction_id' => $notification['transaction_id'],
                ]);

                return response()->json([
                    'message' => 'Payment already processed'
                ], 200);
            }

            // Log before update
            \Log::info('Updating order payment status', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_payment_status' => $order->payment_status,
                'new_payment_status' => $notification['payment_status'],
            ]);

            // Update order payment status
            $order->update([
                'midtrans_transaction_id' => $notification['transaction_id'],
                'midtrans_transaction_status' => $notification['transaction_status'],
                'payment_status' => $notification['payment_status'],
                'paid_at' => $notification['payment_status'] === 'paid' ? now() : null,
            ]);

            // Update order status based on payment status
            if ($notification['payment_status'] === 'paid') {
                $order->update([
                    'status' => 'processing', // Set to processing, not completed
                ]);

                \Log::info('Order payment successful', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status,
                    'status' => $order->status,
                ]);
            } elseif (in_array($notification['payment_status'], ['failed', 'expired', 'cancelled'])) {
                $order->update(['status' => 'cancelled']);

                \Log::info('Order payment failed/cancelled', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status,
                ]);
            }

            return response()->json(['message' => 'Notification handled successfully']);

        } catch (\Exception $e) {
            \Log::error('Midtrans Notification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error handling notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle finish redirect from Midtrans
     */
    public function finish(Request $request)
    {
        $orderNumber = $request->order_id;

        // Remove -DP suffix if present (for down payment transactions)
        if (str_ends_with($orderNumber, '-DP')) {
            $orderNumber = substr($orderNumber, 0, -3);
        }

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->route('user.orders.index')
                ->with('error', 'Order tidak ditemukan');
        }

        // Check transaction status dari Midtrans
        try {
            \Log::info('Checking payment status on finish', [
                'order_number' => $orderNumber,
                'current_payment_status' => $order->payment_status,
            ]);

            $status = $this->midtransService->getTransactionStatus($orderNumber);

            \Log::info('Midtrans status response', [
                'order_number' => $orderNumber,
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
            ]);

            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? null;
            $paymentStatus = 'pending';

            // Handle payment status berdasarkan transaction_status
            if ($transactionStatus == 'capture') {
                // For credit card, check fraud status
                if ($fraudStatus == 'accept' || $fraudStatus == null) {
                    $paymentStatus = 'paid';
                } elseif ($fraudStatus == 'challenge') {
                    $paymentStatus = 'pending';
                }
            } elseif ($transactionStatus == 'settlement') {
                $paymentStatus = 'paid';
            } elseif ($transactionStatus == 'pending') {
                $paymentStatus = 'pending';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $paymentStatus = 'failed';
            }

            // Update order
            $order->update([
                'midtrans_transaction_id' => $status->transaction_id ?? null,
                'midtrans_transaction_status' => $transactionStatus,
                'payment_status' => $paymentStatus,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
            ]);

            \Log::info('Order payment status updated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $paymentStatus,
                'transaction_status' => $transactionStatus,
            ]);

            // Update order status based on payment status
            if ($paymentStatus === 'paid') {
                $order->update([
                    'status' => 'processing', // Set to processing, not completed
                ]);

                \Log::info('Order status updated to processing', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);

                return redirect()->route('user.orders.show', $order)
                    ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
            } elseif ($paymentStatus === 'pending') {
                return redirect()->route('user.orders.show', $order)
                    ->with('info', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.');
            } else {
                return redirect()->route('user.orders.show', $order)
                    ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            \Log::error('Error checking payment status on finish', [
                'order_number' => $orderNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('user.orders.show', $order)
                ->with('error', 'Gagal memeriksa status pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $status = $this->midtransService->getTransactionStatus($order->order_number);

            return response()->json([
                'success' => true,
                'transaction_status' => $status->transaction_status,
                'payment_status' => $order->payment_status,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
