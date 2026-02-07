<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'shippingAddress'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'unpaid' => Order::where('payment_status', 'unpaid')->count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'shippingAddress']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $order->status;

        // If cancelling, restore stock within a transaction
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            DB::beginTransaction();
            try {
                $order = Order::lockForUpdate()->find($order->id);

                // Restore stock for each item
                foreach ($order->items as $item) {
                    $product = \App\Models\Product\Product::lockForUpdate()
                        ->find($item->product_id);

                    if ($product) {
                        $product->increment('stock', $item->quantity);

                        \App\Models\Inventory\InventoryMovement::record(
                            $item->product_id,
                            'in',
                            $item->quantity,
                            $order,
                            "Pembatalan oleh admin - order #{$order->order_number}"
                        );
                    }
                }

                $updateData = [
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ];

                if ($request->filled('admin_notes')) {
                    $updateData['admin_notes'] = $request->admin_notes;
                }

                $order->update($updateData);

                Log::info('Order cancelled by admin', [
                    'order_id' => $order->id,
                    'admin_id' => auth()->id(),
                ]);

                DB::commit();

                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error cancelling order by admin', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                return redirect()->back()
                    ->with('error', 'Gagal membatalkan pesanan.');
            }
        }

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->filled('admin_notes')) {
            $updateData['admin_notes'] = $request->admin_notes;
        }

        // Set completed_at timestamp when status is completed
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $updateData['completed_at'] = now();
        }

        $order->update($updateData);

        Log::info('Order status updated by admin', [
            'order_id' => $order->id,
            'admin_id' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $request->status,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui dari "' . ucfirst($oldStatus) . '" menjadi "' . ucfirst($request->status) . '"');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,pending,paid,failed,expired,cancelled,refunded,installment_active,installment_overdue,installment_completed',
        ]);

        $oldPaymentStatus = $order->payment_status;

        $updateData = [
            'payment_status' => $request->payment_status,
        ];

        // Set paid_at timestamp when payment is marked as paid
        if ($request->payment_status === 'paid' && $oldPaymentStatus !== 'paid') {
            $updateData['paid_at'] = now();

            // Also update order status to processing if still pending
            if ($order->status === 'pending') {
                $updateData['status'] = 'processing';
            }
        }

        $order->update($updateData);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pembayaran berhasil diperbarui');
    }

    /**
     * Add shipping tracking information
     */
    public function updateShipping(Request $request, Order $order)
    {
        $request->validate([
            'shipping_tracking_number' => 'nullable|string|max:255',
            'shipping_notes' => 'nullable|string|max:1000',
        ]);

        $updateData = [];

        if ($request->filled('shipping_tracking_number')) {
            $updateData['shipping_tracking_number'] = $request->shipping_tracking_number;
        }

        if ($request->filled('shipping_notes')) {
            $updateData['admin_notes'] = ($order->admin_notes ? $order->admin_notes . "\n\n" : '') .
                                          "Shipping: " . $request->shipping_notes;
        }

        // Update status to shipped if tracking number is added
        if ($request->filled('shipping_tracking_number') && $order->status === 'processing') {
            $updateData['status'] = 'shipped';
        }

        $order->update($updateData);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Informasi pengiriman berhasil diperbarui');
    }
}
