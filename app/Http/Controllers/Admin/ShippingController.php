<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\BiteshipService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $biteshipService;

    public function __construct(BiteshipService $biteshipService)
    {
        $this->biteshipService = $biteshipService;
    }

    /**
     * Show form to input shipping info
     */
    public function create(Order $order)
    {
        // Check if order belongs to admin's village
        if (!auth()->user()->isSuperAdmin() && $order->items->first()->village_id !== auth()->user()->village_id) {
            abort(403, 'Unauthorized');
        }

        // Check if order is paid
        if ($order->payment_status !== 'paid') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Order harus sudah dibayar sebelum dapat dikirim.');
        }

        $couriers = $this->biteshipService->getSupportedCouriers();

        return view('admin.shipping.create', compact('order', 'couriers'));
    }

    /**
     * Store shipping info (Manual input resi)
     */
    public function store(Request $request, Order $order)
    {
        // Check if order belongs to admin's village
        if (!auth()->user()->isSuperAdmin() && $order->items->first()->village_id !== auth()->user()->village_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'shipping_courier' => 'required|string|max:50',
            'shipping_resi' => 'required|string|max:100',
        ]);

        // Update order with shipping info
        $order->update([
            'shipping_courier' => strtolower($validated['shipping_courier']),
            'shipping_resi' => $validated['shipping_resi'],
            'shipping_status' => 'on_process',
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        // Try to get initial tracking data from Biteship
        $tracking = $this->biteshipService->track($validated['shipping_resi']);

        if ($tracking['success']) {
            $trackingData = $tracking['data'];
            $status = $this->biteshipService->parseStatus($trackingData);
            $history = $this->biteshipService->getTrackingHistory($trackingData);

            $order->update([
                'shipping_status' => $status,
                'shipping_history' => $history,
                'tracking_updated_at' => now(),
            ]);

            // If delivered on first check, update accordingly
            if ($status === 'delivered') {
                $order->update([
                    'delivered_at' => now(),
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Nomor resi berhasil ditambahkan. Order sudah dalam status pengiriman.');
    }

    /**
     * Create shipment via Biteship API (Auto generate resi)
     */
    public function createShipment(Request $request, Order $order)
    {
        // Check if order belongs to admin's village
        if (!auth()->user()->isSuperAdmin() && $order->items->first()->village_id !== auth()->user()->village_id) {
            abort(403, 'Unauthorized');
        }

        // Check if order is paid
        if ($order->payment_status !== 'paid') {
            return redirect()->back()
                ->with('error', 'Order harus sudah dibayar sebelum dapat dikirim.');
        }

        // Check if already shipped
        if ($order->isShipped()) {
            return redirect()->back()
                ->with('error', 'Order ini sudah dalam proses pengiriman.');
        }

        // Get village data
        $village = $order->items->first()->product->village;

        if (!$village->latitude || !$village->longitude) {
            return redirect()->back()
                ->with('error', 'Desa belum mengatur koordinat lokasi pengiriman.');
        }

        // Parse shipping service (format: jne-reg, jnt-ez, etc)
        $serviceParts = explode('-', $order->shipping_service);
        $courierCode = $serviceParts[0] ?? '';
        $courierType = strtoupper($serviceParts[1] ?? 'REG');

        // Prepare items data
        $items = $order->items->map(function($item) {
            return [
                'name' => $item->product_name,
                'description' => $item->product_name,
                'value' => (int) $item->price,
                'length' => $item->product->length ?? 10,
                'width' => $item->product->width ?? 10,
                'height' => $item->product->height ?? 10,
                'weight' => (int) ($item->product->weight * $item->quantity),
                'quantity' => (int) $item->quantity,
            ];
        })->toArray();

        // Create shipment via Biteship
        $result = $this->biteshipService->createOrder([
            'shipper_name' => $village->name,
            'shipper_phone' => $village->phone ?? '081234567890',
            'shipper_email' => $village->email ?? 'noreply@sidesa.com',
            'shipper_organization' => 'SiDesa - ' . $village->name,

            'origin_name' => $village->name,
            'origin_phone' => $village->phone ?? '081234567890',
            'origin_address' => $village->address ?? $village->name,
            'origin_note' => 'Desa ' . $village->name,
            'origin_postal_code' => $village->origin_postal_code ?? '12345',
            'origin_latitude' => (float) $village->latitude,
            'origin_longitude' => (float) $village->longitude,

            'destination_name' => $order->shippingAddress->recipient_name,
            'destination_phone' => $order->shippingAddress->phone,
            'destination_email' => $order->user->email ?? '',
            'destination_address' => $order->shippingAddress->full_address,
            'destination_postal_code' => $order->shippingAddress->postal_code ?? '12345',
            'destination_note' => $order->shippingAddress->notes ?? '',
            'destination_latitude' => (float) $order->shippingAddress->latitude,
            'destination_longitude' => (float) $order->shippingAddress->longitude,

            'courier_company' => $courierCode,
            'courier_type' => $courierType,
            'delivery_type' => 'now',
            'items' => $items,
            'reference_id' => $order->order_number,
        ]);

        if (!$result['success']) {
            return redirect()->back()
                ->with('error', 'Gagal membuat shipment: ' . $result['message']);
        }

        // Extract tracking info from Biteship response
        $biteshipData = $result['data'];
        $trackingId = $biteshipData['id'] ?? null;
        $waybillId = $biteshipData['courier']['waybill_id'] ?? null;

        // Update order with tracking info
        $order->update([
            'shipping_tracking_number' => $trackingId,
            'shipping_resi' => $waybillId,
            'shipping_courier' => $courierCode,
            'shipping_status' => 'on_process',
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Shipment berhasil dibuat via Biteship! Nomor resi: ' . $waybillId);
    }

    /**
     * Update tracking info
     */
    public function updateTracking(Order $order)
    {
        if (!$order->hasTracking()) {
            return redirect()->back()
                ->with('error', 'Order belum memiliki info tracking.');
        }

        $tracking = $this->biteshipService->track($order->shipping_resi);

        if (!$tracking['success']) {
            return redirect()->back()
                ->with('error', 'Gagal update tracking: ' . $tracking['message']);
        }

        $trackingData = $tracking['data'];
        $status = $this->biteshipService->parseStatus($trackingData);
        $history = $this->biteshipService->getTrackingHistory($trackingData);

        $updateData = [
            'shipping_status' => $status,
            'shipping_history' => $history,
            'tracking_updated_at' => now(),
        ];

        // If delivered, update status and delivered_at
        if ($status === 'delivered' && !$order->isDelivered()) {
            $updateData['delivered_at'] = now();
            $updateData['status'] = 'completed';
            $updateData['completed_at'] = now();
        }

        $order->update($updateData);

        return redirect()->back()
            ->with('success', 'Tracking berhasil diupdate!');
    }

    /**
     * Show tracking page (for users)
     */
    public function show(Order $order)
    {
        // Check if user owns this order or is admin
        if (!auth()->user()->isSuperAdmin() &&
            !auth()->user()->isAdmin() &&
            $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$order->hasTracking()) {
            return redirect()->route('user.orders.show', $order)
                ->with('error', 'Order ini belum memiliki informasi tracking.');
        }

        // Get latest tracking data from Biteship
        $tracking = $this->biteshipService->track($order->shipping_resi);

        if ($tracking['success']) {
            $trackingData = $tracking['data'];
            $status = $this->biteshipService->parseStatus($trackingData);
            $history = $this->biteshipService->getTrackingHistory($trackingData);

            // Update if different or if history is empty
            if ($order->shipping_status !== $status || empty($order->shipping_history)) {
                $updateData = [
                    'shipping_status' => $status,
                    'shipping_history' => $history,
                    'tracking_updated_at' => now(),
                ];

                if ($status === 'delivered' && !$order->isDelivered()) {
                    $updateData['delivered_at'] = now();
                    $updateData['status'] = 'completed';
                    $updateData['completed_at'] = now();
                }

                $order->update($updateData);
                $order->refresh();
            }
        }

        $statusLabel = $this->biteshipService->getStatusLabel($order->shipping_status);

        return view('user.orders.tracking', compact('order', 'statusLabel'));
    }
}
