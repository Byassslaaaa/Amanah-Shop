@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pembayaran</h1>
            <p class="text-gray-600 mt-2">Order #{{ $order->order_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Produk -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Produk</h2>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex items-start gap-4 pb-3 border-b last:border-0">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->product_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Alamat Pengiriman -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Alamat Pengiriman</h2>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-900">{{ $order->shippingAddress->recipient_name }}</p>
                        <p class="mt-1">{{ $order->shippingAddress->phone }}</p>
                        <p class="mt-2">{{ $order->shippingAddress->full_address }}</p>
                        <p>{{ $order->shippingAddress->district }}, {{ $order->shippingAddress->city_name }}</p>
                        <p>{{ $order->shippingAddress->province_name }}, {{ $order->shippingAddress->postal_code }}</p>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran yang Tersedia</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">💳</span>
                            <span class="text-xs font-medium text-gray-700">Credit Card</span>
                        </div>
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">🏦</span>
                            <span class="text-xs font-medium text-gray-700">Virtual Account</span>
                        </div>
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">📱</span>
                            <span class="text-xs font-medium text-gray-700">GoPay</span>
                        </div>
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">🛒</span>
                            <span class="text-xs font-medium text-gray-700">ShopeePay</span>
                        </div>
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">📲</span>
                            <span class="text-xs font-medium text-gray-700">QRIS</span>
                        </div>
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">🏪</span>
                            <span class="text-xs font-medium text-gray-700">Indomaret</span>
                        </div>
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">🏪</span>
                            <span class="text-xs font-medium text-gray-700">Alfamart</span>
                        </div>
                        <div class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg">
                            <span class="text-2xl mb-1">💰</span>
                            <span class="text-xs font-medium text-gray-700">Lainnya</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Ringkasan Pembayaran -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>

                    <div class="space-y-3 mb-4 pb-4 border-b">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal Produk</span>
                            <span class="font-medium text-gray-900">
                                Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span class="font-medium text-gray-900">
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Layanan Pengiriman</span>
                            <span class="font-medium text-gray-900 text-xs">
                                {{ $order->shipping_service }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between mb-6">
                        <span class="text-base font-bold text-gray-900">Total Pembayaran</span>
                        <span class="text-xl font-bold text-green-600">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Status Pembayaran:</span>
                            @if($order->payment_status === 'paid')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    ✓ Lunas
                                </span>
                            @elseif($order->payment_status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    ⏳ Menunggu
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($order->payment_status !== 'paid')
                        <button id="pay-button"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>Bayar Sekarang</span>
                        </button>

                        <div class="mt-3 flex items-center justify-center gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Pembayaran dijamin aman</span>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-green-800 font-semibold">Pembayaran Berhasil!</p>
                            <p class="text-green-600 text-sm mt-1">Order Anda sedang diproses</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('user.orders.show', $order) }}"
                           class="w-full block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                            ← Lihat Detail Order
                        </a>
                    </div>

                    <!-- Info Security -->
                    <div class="mt-6 pt-4 border-t">
                        <div class="flex items-start gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p>Data pembayaran Anda dienkripsi dan diproses secara aman oleh Midtrans</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        const snapToken = '{{ $order->midtrans_snap_token }}';

        // Only show when button is clicked (NO AUTO SHOW)
        if (payButton) {
            payButton.addEventListener('click', function() {
                showMidtransPayment();
            });
        }

        function showMidtransPayment() {
            // Disable button and show loading
            if (payButton) {
                payButton.disabled = true;
                payButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memuat...</span>
                `;
            }

            // Show Midtrans Snap popup
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    // Show success message
                    if (payButton) {
                        payButton.innerHTML = `
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Pembayaran Berhasil!</span>
                        `;
                    }
                    // Redirect after 1 second
                    setTimeout(function() {
                        window.location.href = '{{ route('user.orders.show', $order) }}?payment=success';
                    }, 1000);
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route('user.orders.show', $order) }}?payment=pending';
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    // Reset button
                    if (payButton) {
                        payButton.disabled = false;
                        payButton.innerHTML = `
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>Bayar Sekarang</span>
                        `;
                    }
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // Reset button when user closes popup
                    if (payButton) {
                        payButton.disabled = false;
                        payButton.innerHTML = `
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>Bayar Sekarang</span>
                        `;
                    }
                }
            });
        }
    });
</script>
@endpush
