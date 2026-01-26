@extends('layouts.app')

@section('title', 'Riwayat Pesanan - BUMDes Marketplace')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Riwayat Pesanan</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Pantau status pesanan Anda</p>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <!-- Order Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 pb-4 border-b">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                                    <p class="text-xl font-bold text-green-600">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="mb-4">
                                <div class="flex items-center gap-3 flex-wrap">
                                    @foreach($order->items->take(3) as $item)
                                        @if($item->product && $item->product->images && count($item->product->images) > 0)
                                            <img src="{{ $item->product->getImageDataUri(0) }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <span class="text-xs text-gray-600 font-medium">+{{ $order->items->count() - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-2">{{ $order->items->count() }} produk</p>
                            </div>

                            <!-- Payment Status & Actions -->
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="text-sm">
                                    <span class="text-gray-600">Pembayaran: </span>
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
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('user.orders.show', $order) }}"
                                   class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors text-center">
                                    Lihat Detail
                                </a>
                                @if($order->payment_status === 'unpaid')
                                    <a href="{{ route('user.payment.show', $order) }}"
                                       class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center">
                                        💳 Bayar Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-lg p-12">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-600 mb-6">Anda belum melakukan pemesanan apapun</p>
                    <a href="{{ route('products.index') }}"
                       class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors inline-block">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
