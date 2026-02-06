<?php

namespace App\Http\Controllers\User\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Order\Cart;
use App\Models\ShippingAddress;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product', 'installmentPlan'])
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini');
        }

        // Auto-check payment status jika menggunakan Midtrans dan statusnya belum paid
        if ($order->payment_method === 'midtrans' &&
            $order->payment_status !== 'paid' &&
            $order->midtrans_order_id) {

            try {
                $midtransService = new MidtransService();
                $status = $midtransService->getTransactionStatus($order->order_number);

                $transactionStatus = $status->transaction_status;
                $fraudStatus = $status->fraud_status ?? null;
                $paymentStatus = 'pending';

                // Handle payment status
                if ($transactionStatus == 'capture') {
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

                // Update order jika ada perubahan status
                if ($order->payment_status !== $paymentStatus) {
                    $order->update([
                        'midtrans_transaction_id' => $status->transaction_id ?? null,
                        'midtrans_transaction_status' => $transactionStatus,
                        'payment_status' => $paymentStatus,
                        'paid_at' => $paymentStatus === 'paid' ? now() : null,
                    ]);

                    // Update order status
                    if ($paymentStatus === 'paid' && $order->status === 'pending') {
                        $order->update(['status' => 'processing']);
                    } elseif (in_array($paymentStatus, ['failed', 'expired', 'cancelled']) && $order->status === 'pending') {
                        $order->update(['status' => 'cancelled']);
                    }

                    // Refresh order untuk mendapatkan data terbaru
                    $order->refresh();
                }
            } catch (\Exception $e) {
                // Log error tapi tetap lanjutkan menampilkan halaman
                \Log::error('Error auto-checking payment status', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $order->load(['items.product', 'installmentPlan', 'installmentPayments' => function ($query) {
            $query->orderBy('installment_number');
        }]);

        return view('user.orders.show', compact('order'));
    }

    /**
     * Checkout from cart
     */
    public function checkout()
    {
        // Hanya ambil item yang selected
        $cartItems = Cart::where('user_id', auth()->id())
            ->where('is_selected', true)
            ->with(['product'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Tidak ada produk yang dipilih untuk checkout. Silakan pilih produk terlebih dahulu.');
        }

        // Calculate product total for credit calculator
        $productTotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Get active installment plans for credit option
        $installmentPlans = \App\Models\Credit\InstallmentPlan::active()->get();

        // Get product credit settings
        $productCreditSettings = \App\Models\Credit\ProductCreditSetting::whereIn(
            'product_id',
            $cartItems->pluck('product_id')
        )->get()->keyBy('product_id');

        return view('user.orders.checkout', compact('cartItems', 'productTotal', 'installmentPlans', 'productCreditSettings'));
    }

    /**
     * Process checkout and create order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'payment_type' => 'required|in:cash,credit',
            'installment_plan_id' => 'required_if:payment_type,credit|nullable|exists:installment_plans,id',
            'down_payment' => 'required_if:payment_type,credit|nullable|numeric|min:0',
            'customer_notes' => 'nullable|string',
            // Shipping address validation
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_id' => 'required|string',
            'province_name' => 'required|string',
            'city_id' => 'required|string',
            'city_name' => 'required|string',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:10',
            'full_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            // Shipping service validation
            'shipping_cost' => 'required|integer|min:0',
            'shipping_service' => 'required|string',
            'shipping_etd' => 'nullable|string',
        ]);

        // Hanya ambil item yang selected
        $cartItems = Cart::where('user_id', auth()->id())
            ->where('is_selected', true)
            ->with(['product'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Tidak ada produk yang dipilih untuk checkout');
        }

        // ⚠️ START TRANSACTION EARLY to prevent race conditions
        DB::beginTransaction();

        try {
            // ⚠️ CRITICAL: Validate stock WITH PESSIMISTIC LOCK to prevent race condition
            // Lock products for update to ensure no other transaction can modify stock
            foreach ($cartItems as $cartItem) {
                $product = \App\Models\Product\Product::lockForUpdate()
                    ->find($cartItem->product_id);

                if ($cartItem->quantity > $product->stock) {
                    DB::rollBack();
                    return redirect()->route('user.cart.index')
                        ->with('error', "Stok produk '{$product->name}' tidak mencukupi. Tersedia: {$product->stock}, di keranjang: {$cartItem->quantity}");
                }
            }
            // Create shipping address
            $shippingAddress = ShippingAddress::create([
                'user_id' => auth()->id(),
                'label' => 'Order Address',
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'province_id' => $validated['province_id'],
                'province_name' => $validated['province_name'],
                'city_id' => $validated['city_id'],
                'city_name' => $validated['city_name'],
                'district' => $validated['district'],
                'postal_code' => $validated['postal_code'],
                'full_address' => $validated['full_address'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'is_default' => false,
            ]);

            // Calculate total (products + shipping)
            $productTotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
            $totalAmount = $productTotal + $validated['shipping_cost'];

            // Prepare order data
            $orderData = [
                'order_number' => $this->generateOrderNumber(),
                'user_id' => auth()->id(),
                'shipping_address_id' => $shippingAddress->id,
                'total_amount' => $totalAmount,
                'shipping_cost' => $validated['shipping_cost'],
                'shipping_service' => $validated['shipping_service'],
                'shipping_etd' => $validated['shipping_etd'],
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $validated['payment_method'],
                'payment_type' => $validated['payment_type'],
                'customer_notes' => $validated['customer_notes'] ?? null,
            ];

            // If credit order, calculate credit details
            if ($validated['payment_type'] === 'credit') {
                $creditService = new \App\Services\CreditCalculationService();
                $creditCalc = $creditService->calculateCredit(
                    $totalAmount,
                    $validated['down_payment'],
                    $validated['installment_plan_id']
                );

                $orderData = array_merge($orderData, [
                    'installment_plan_id' => $validated['installment_plan_id'],
                    'down_payment_amount' => $creditCalc['down_payment'],
                    'principal_amount' => $creditCalc['principal'],
                    'interest_amount' => $creditCalc['interest_amount'],
                    'total_credit_amount' => $creditCalc['total_credit'],
                    'monthly_installment' => $creditCalc['monthly_installment'],
                    'installment_months' => $creditCalc['installment_months'],
                    'remaining_balance' => $creditCalc['total_credit'],
                    'payment_status' => 'unpaid',
                    'credit_approved_at' => now(),
                ]);
            }

            // Create order
            $order = Order::create($orderData);

            // Create order items and track inventory
            foreach ($cartItems as $cartItem) {
                // ⚠️ CRITICAL: Lock product again to ensure atomicity
                $product = \App\Models\Product\Product::lockForUpdate()
                    ->find($cartItem->product_id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->quantity * $product->price,
                ]);

                // ⚠️ ATOMIC: Reduce stock with pessimistic lock
                // This ensures no race condition between check and decrement
                $product->decrement('stock', $cartItem->quantity);

                // Record inventory movement - if this fails, transaction will rollback
                \App\Models\Inventory\InventoryMovement::record(
                    $cartItem->product_id,
                    'out',
                    $cartItem->quantity,
                    $order,
                    "Penjualan order #{$order->order_number}"
                );
            }

            // Generate installment schedule for credit orders
            if ($validated['payment_type'] === 'credit') {
                $creditService = new \App\Services\CreditCalculationService();
                $schedule = $creditService->generateSchedule(
                    $order->id,
                    $orderData['monthly_installment'],
                    $orderData['installment_months']
                );
                \App\Models\Credit\InstallmentPayment::insert($schedule);
            }

            // Clear hanya cart items yang selected (yang sudah di-checkout)
            Cart::where('user_id', auth()->id())
                ->where('is_selected', true)
                ->delete();

            // Generate Midtrans Snap Token if payment method is midtrans
            if ($validated['payment_method'] === 'midtrans') {
                try {
                    $midtransService = new MidtransService();

                    // For credit orders with down payment, use createDownPaymentTransaction
                    // For cash orders or credit without DP, use createTransaction
                    if ($validated['payment_type'] === 'credit' && $orderData['down_payment_amount'] > 0) {
                        $snapToken = $midtransService->createDownPaymentTransaction($order);
                    } else {
                        $snapToken = $midtransService->createTransaction($order);
                    }

                    $order->update([
                        'midtrans_snap_token' => $snapToken,
                        'midtrans_order_id' => $order->order_number,
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
                }
            }

            DB::commit();

            // Redirect to payment page if using Midtrans
            if ($validated['payment_method'] === 'midtrans') {
                return redirect()->route('user.payment.show', $order);
            }

            return redirect()->route('user.orders.show', $order)
                ->with('success', 'Order berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }


    /**
     * Cancel order (customer self-service)
     */
    public function cancel(Request $request, Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini');
        }

        // Validasi: hanya order pending yang bisa dicancel
        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Order tidak dapat dibatalkan. Hanya order dengan status "Menunggu Pembayaran" yang dapat dibatalkan.');
        }

        // Validasi: tidak boleh cancel jika sudah paid
        if ($order->payment_status === 'paid') {
            return redirect()->back()
                ->with('error', 'Order tidak dapat dibatalkan karena pembayaran sudah diterima. Silakan hubungi admin untuk refund.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan harus diisi.',
            'cancellation_reason.max' => 'Alasan pembatalan maksimal 500 karakter.',
        ]);

        DB::beginTransaction();

        try {
            // 1. Restore stock untuk setiap item
            foreach ($order->items as $item) {
                $product = \App\Models\Product\Product::lockForUpdate()
                    ->find($item->product_id);

                if ($product) {
                    // Kembalikan stock
                    $product->increment('stock', $item->quantity);

                    // Reverse inventory movement
                    \App\Models\Inventory\InventoryMovement::record(
                        $item->product_id,
                        'in',
                        $item->quantity,
                        $order,
                        "Pembatalan order #{$order->order_number} - {$validated['cancellation_reason']}"
                    );
                }
            }

            // 2. Update order status
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $validated['cancellation_reason'],
            ]);

            DB::commit();

            return redirect()->route('user.orders.show', $order)
                ->with('success', 'Order berhasil dibatalkan. Stok produk telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        return $prefix . $date . $random;
    }
}
