@extends('layouts.app')

@section('title', 'Lacak Pengiriman - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <a href="{{ route('user.orders.show', $order) }}" class="text-green-600 hover:text-green-700 mb-4 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Detail Pesanan
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-4">Lacak Pengiriman</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Order #{{ $order->order_number }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Tracking Info Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white">
                <h2 class="text-xl font-semibold">Informasi Pengiriman</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Courier -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Kurir:</label>
                        <p class="text-base font-semibold text-gray-900 uppercase">{{ strtoupper($order->shipping_courier) }}</p>
                    </div>

                    <!-- Resi Number -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nomor Resi:</label>
                        <div class="flex items-center gap-2">
                            <p class="text-base font-mono font-semibold text-gray-900">{{ $order->shipping_resi }}</p>
                            <button
                                onclick="copyResi()"
                                class="text-green-600 hover:text-green-700 p-1 rounded hover:bg-green-50 transition-colors"
                                title="Salin nomor resi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status:</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $order->getShippingStatusColor() }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Shipped Date -->
                @if($order->shipped_at)
                    <div class="border-t pt-4 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Dikirim pada: <strong>{{ $order->shipped_at->format('d M Y, H:i') }} WIB</strong></span>
                        </div>
                    </div>
                @endif

                <!-- Auto-Update Info -->
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-blue-900 mb-1">🔄 Update Otomatis</p>
                            <p class="text-xs text-blue-700">
                                Status tracking diperbarui secara otomatis setiap 2 jam dari sistem kurir {{ strtoupper($order->shipping_courier) }}.
                                Anda tidak perlu refresh halaman, status akan update sendiri.
                            </p>
                            @if($order->tracking_updated_at)
                                <p class="text-xs text-blue-800 font-medium mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Terakhir diupdate: {{ $order->tracking_updated_at->format('d M Y, H:i') }} WIB
                                    ({{ $order->tracking_updated_at->diffForHumans() }})
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- External Tracking Link -->
                @if($order->getTrackingUrl())
                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ $order->getTrackingUrl() }}"
                           target="_blank"
                           class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Lacak di website {{ strtoupper($order->shipping_courier) }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tracking History Timeline -->
        @if($order->shipping_history && count($order->shipping_history) > 0)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <h2 class="text-xl font-semibold">Riwayat Pengiriman</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($order->shipping_history as $index => $history)
                            <div class="relative pl-8 pb-6 {{ $index === count($order->shipping_history) - 1 ? 'pb-0' : '' }}">
                                <!-- Timeline Line -->
                                @if($index !== count($order->shipping_history) - 1)
                                    <div class="absolute left-3 top-6 bottom-0 w-0.5 bg-gray-200"></div>
                                @endif

                                <!-- Timeline Dot -->
                                <div class="absolute left-0 top-1 w-6 h-6 rounded-full flex items-center justify-center
                                    {{ $index === 0 ? 'bg-green-500' : 'bg-blue-500' }}">
                                    @if($index === 0)
                                        <!-- Latest status - checkmark -->
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <!-- Past status - dot -->
                                        <div class="w-2 h-2 rounded-full bg-white"></div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="bg-gray-50 rounded-lg p-4 {{ $index === 0 ? 'border-2 border-green-500' : '' }}">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ $history['desc'] ?? $history['description'] ?? 'Status Update' }}
                                        </h3>
                                        @if($index === 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Status Terbaru
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 text-sm text-gray-600">
                                        <!-- Date -->
                                        @if(isset($history['date']))
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ $history['date'] }}</span>
                                            </div>
                                        @endif

                                        <!-- Location -->
                                        @if(isset($history['location']) && $history['location'])
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span>{{ $history['location'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- No History Yet -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Tracking</h3>
                <p class="text-gray-600 text-sm">Riwayat pengiriman akan muncul setelah paket mulai diproses oleh kurir.</p>
                <p class="text-gray-500 text-xs mt-2">Silakan cek kembali nanti atau refresh halaman ini.</p>
            </div>
        @endif

        <!-- Shipping Address Card -->
        @if($order->shippingAddress)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mt-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                    <h2 class="text-xl font-semibold">Alamat Tujuan Pengiriman</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Penerima:</label>
                            <p class="text-base font-semibold text-gray-900">{{ $order->shippingAddress->recipient_name }}</p>
                            <div class="flex items-center text-sm text-gray-600 mt-2">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $order->shippingAddress->phone }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Lengkap:</label>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $order->shippingAddress->full_address }}<br>
                                {{ $order->shippingAddress->district }}, {{ $order->shippingAddress->city_name }}<br>
                                {{ $order->shippingAddress->province_name }}, {{ $order->shippingAddress->postal_code }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function copyResi() {
    const resi = "{{ $order->shipping_resi }}";
    navigator.clipboard.writeText(resi).then(function() {
        // Show toast or alert
        alert('Nomor resi berhasil disalin: ' + resi);
    }, function(err) {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endsection
