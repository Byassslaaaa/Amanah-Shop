@extends('layouts.app')

@section('title', 'Detail Pesanan - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <a href="{{ route('user.orders.index') }}" class="text-green-600 hover:text-green-700 mb-4 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Riwayat Pesanan
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-4">Detail Pesanan</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Order #{{ $order->order_number }}</p>
        </div>

        <!-- Payment Success Alert -->
        @if(request()->get('payment') === 'success' || $order->payment_status === 'paid')
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-semibold text-green-800">Pembayaran Berhasil!</h3>
                        <p class="text-sm text-green-700 mt-1">Terima kasih! Pembayaran Anda telah berhasil diproses. Pesanan Anda sedang diproses dan akan segera dikirimkan.</p>
                        @if($order->paid_at)
                            <p class="text-xs text-green-600 mt-2">
                                <strong>Dibayar pada:</strong> {{ $order->paid_at->format('d M Y, H:i') }} WIB
                            </p>
                        @endif
                        @if($order->midtrans_transaction_id)
                            <p class="text-xs text-green-600 mt-1">
                                <strong>ID Transaksi:</strong> {{ $order->midtrans_transaction_id }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if(request()->get('payment') === 'pending')
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-yellow-800">Pembayaran Pending</h3>
                        <p class="text-sm text-yellow-700 mt-1">Pembayaran Anda sedang diproses. Harap selesaikan pembayaran sesuai instruksi yang diberikan.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Timeline -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Pesanan</h2>
                    <div class="flex items-center justify-between">
                        <div class="flex-1 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                {{ in_array($order->status, ['pending', 'processing', 'shipped', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-xs mt-2 font-medium">Pending</p>
                        </div>
                        <div class="flex-1 h-1 {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        <div class="flex-1 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <p class="text-xs mt-2 font-medium">Diproses</p>
                        </div>
                        <div class="flex-1 h-1 {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        <div class="flex-1 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </div>
                            <p class="text-xs mt-2 font-medium">Dikirim</p>
                        </div>
                        <div class="flex-1 h-1 {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        <div class="flex-1 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs mt-2 font-medium">Selesai</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b bg-green-50">
                        <h2 class="text-lg font-semibold text-gray-900">Produk yang Dipesan</h2>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <div class="p-6">
                                <div class="flex items-start space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product && $item->product->images && count($item->product->images) > 0)
                                            <img src="{{ $item->product->getImageDataUri(0) }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="w-20 h-20 object-cover rounded-lg">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $item->product_name }}</h3>
                                        <div class="flex items-center gap-4 text-sm">
                                            <span class="text-gray-600">{{ $item->quantity }}x</span>
                                            <span class="font-bold text-green-600">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 mb-1">Subtotal</p>
                                        <p class="font-bold text-gray-900">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Customer Notes -->
                @if($order->customer_notes)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                        <p class="text-gray-600">{{ $order->customer_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 lg:sticky lg:top-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $order->payment_status === 'unpaid' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                @if($order->payment_status === 'unpaid')
                                    Belum Bayar
                                @elseif($order->payment_status === 'paid')
                                    ✓ Lunas
                                @elseif($order->payment_status === 'pending')
                                    Pending
                                @else
                                    {{ ucfirst($order->payment_status) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-semibold">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tanggal Order</span>
                            <span class="font-semibold">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <hr>
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900">Total Pembayaran</span>
                            <span class="text-green-600">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if($order->payment_status === 'unpaid')
                        <div class="mb-4">
                            <a href="{{ route('user.payment.show', $order) }}"
                               class="w-full block text-center bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors shadow-md">
                                💳 Bayar Sekarang
                            </a>
                            <p class="text-xs text-gray-500 text-center mt-2">
                                Pembayaran aman dengan Midtrans
                            </p>
                        </div>
                    @elseif($order->payment_status === 'paid')
                        <div class="p-4 bg-green-50 rounded-lg border-2 border-green-300 mb-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-base text-green-800 text-center font-bold mb-1">Pembayaran Lunas</p>
                            <p class="text-xs text-green-700 text-center mb-2">
                                Pesanan sedang diproses
                            </p>
                            @if($order->paid_at)
                                <div class="pt-2 border-t border-green-200">
                                    <p class="text-xs text-green-600 text-center">
                                        <strong>Dibayar:</strong><br>
                                        {{ $order->paid_at->format('d M Y, H:i') }} WIB
                                    </p>
                                </div>
                            @endif
                            @if($order->midtrans_transaction_id)
                                <p class="text-xs text-gray-500 text-center mt-2">
                                    ID: {{ substr($order->midtrans_transaction_id, 0, 20) }}...
                                </p>
                            @endif
                        </div>
                    @elseif($order->payment_status === 'pending')
                        <div class="p-4 bg-yellow-50 rounded-lg border-2 border-yellow-300 mb-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-10 h-10 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-base text-yellow-800 text-center font-bold mb-1">Menunggu Pembayaran</p>
                            <p class="text-xs text-yellow-700 text-center">
                                Selesaikan pembayaran sesuai instruksi
                            </p>
                        </div>
                    @endif

                    <!-- Tracking Button -->
                    @if($order->hasTracking())
                        <div class="mb-4">
                            <a href="{{ route('user.orders.tracking', $order) }}"
                               class="w-full flex items-center justify-center bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Lacak Pengiriman
                            </a>
                            <p class="text-xs text-gray-500 text-center mt-2">
                                <span class="font-mono font-semibold">{{ $order->shipping_resi }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
