@extends('layouts.admin')

@section('title', 'Input Nomor Resi - Order #' . $order->order_number)

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center text-green-600 hover:text-green-700 mb-3">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Detail Order
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Input Nomor Resi</h1>
        <p class="text-sm text-gray-600 mt-1">Order #{{ $order->order_number }}</p>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form (2 columns) -->
        <div class="lg:col-span-2">
            <!-- Option Selection -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Metode Pengiriman</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="button" onclick="showManualForm()" id="btn-manual"
                            class="p-4 border-2 border-green-600 bg-white rounded-lg hover:bg-green-50 transition-all">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <div class="text-left">
                                <h4 class="font-semibold text-gray-800">Input Resi Manual</h4>
                                <p class="text-xs text-gray-600 mt-1">Masukkan nomor resi yang sudah ada</p>
                            </div>
                        </div>
                    </button>

                    <button type="button" onclick="showBiteshipForm()" id="btn-biteship"
                            class="p-4 border-2 border-gray-300 bg-white rounded-lg hover:bg-blue-50 hover:border-blue-600 transition-all">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <div class="text-left">
                                <h4 class="font-semibold text-gray-800">Biteship Auto</h4>
                                <p class="text-xs text-gray-600 mt-1">Buat shipment otomatis via Biteship</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Manual Form -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden" id="manual-form">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h3 class="text-lg font-semibold">Input Resi Manual</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.shipping.store', $order) }}" method="POST">
                        @csrf

                        <!-- Courier Selection -->
                        <div class="mb-6">
                            <label for="shipping_courier" class="block text-sm font-medium text-gray-700 mb-2">
                                Kurir Pengiriman <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="shipping_courier"
                                name="shipping_courier"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('shipping_courier') border-red-500 @enderror">
                                <option value="">-- Pilih Kurir --</option>
                                @foreach($couriers as $code => $name)
                                    <option value="{{ $code }}" {{ old('shipping_courier') == $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shipping_courier')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Pilih kurir sesuai dengan yang digunakan untuk pengiriman</p>
                        </div>

                        <!-- Resi Number -->
                        <div class="mb-6">
                            <label for="shipping_resi" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Resi <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="shipping_resi"
                                name="shipping_resi"
                                value="{{ old('shipping_resi') }}"
                                required
                                placeholder="Contoh: JNTXXXXXXXX atau JNE00XXXXXXX"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('shipping_resi') border-red-500 @enderror">
                            @error('shipping_resi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Masukkan nomor resi yang diberikan oleh kurir. Sistem akan otomatis mengambil data tracking setelah resi disimpan.
                            </p>
                        </div>

                        <!-- Info Box -->
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium mb-1">Informasi Penting:</p>
                                    <ul class="list-disc ml-5 space-y-1">
                                        <li>Pastikan nomor resi sudah valid dan sudah terdaftar di sistem kurir</li>
                                        <li>Setelah resi disimpan, status order akan otomatis berubah menjadi "Shipped"</li>
                                        <li>Data tracking akan diupdate otomatis dari sistem kurir</li>
                                        <li>Customer akan dapat melihat status pengiriman secara real-time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                                <svg class="inline w-5 h-5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan & Kirim Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Biteship Auto Form -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hidden" id="biteship-form">
                <div class="px-6 py-4 bg-blue-600 text-white">
                    <h3 class="text-lg font-semibold">Buat Shipment via Biteship</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.shipping.create-shipment', $order) }}" method="POST" id="form-biteship">
                        @csrf

                        <!-- Info -->
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium mb-1">Fitur Biteship Auto:</p>
                                    <ul class="list-disc ml-5 space-y-1">
                                        <li>Shipment akan dibuat otomatis ke Biteship</li>
                                        <li>Nomor resi akan didapatkan otomatis dari kurir</li>
                                        <li>Tracking terintegrasi langsung</li>
                                        <li>Data kurir & layanan diambil dari pilihan checkout customer</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Info (Read-only) -->
                        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-3">Informasi Pengiriman dari Checkout:</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Kurir:</span>
                                    <p class="font-semibold text-gray-800">{{ strtoupper(explode('-', $order->shipping_service)[0] ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Layanan:</span>
                                    <p class="font-semibold text-gray-800">{{ strtoupper(explode('-', $order->shipping_service)[1] ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Estimasi:</span>
                                    <p class="font-semibold text-gray-800">{{ $order->shipping_etd }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Biaya:</span>
                                    <p class="font-semibold text-green-600">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Box -->
                        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-yellow-700">
                                    <p class="font-medium mb-1">Perhatian:</p>
                                    <ul class="list-disc ml-5 space-y-1">
                                        <li>Fitur ini akan membuat shipment baru di Biteship</li>
                                        <li>Biaya shipment akan dipotong dari saldo Biteship Anda</li>
                                        <li>Pastikan data toko (koordinat, alamat) sudah benar di .env</li>
                                        <li>Untuk testing, gunakan mode "Input Resi Manual"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="mb-6">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" id="confirm-biteship" class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" required>
                                <span class="ml-3 text-sm text-gray-700">
                                    Saya memahami bahwa shipment akan dibuat di Biteship dan biaya akan dipotong dari saldo.
                                </span>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                                <svg class="inline w-5 h-5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Buat Shipment via Biteship
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Info Sidebar (1 column) -->
        <div class="lg:col-span-1">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-700 text-white">
                    <h3 class="text-lg font-semibold">Info Order</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Order Number:</label>
                            <p class="text-sm font-mono text-gray-800">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Customer:</label>
                            <p class="text-sm text-gray-800">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Total Order:</label>
                            <p class="text-lg font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Pembayaran:</label>
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    ✓ Lunas
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Belum Bayar
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            @if($order->shippingAddress)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600 text-white">
                        <h3 class="text-lg font-semibold">Alamat Tujuan</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Penerima:</label>
                                <p class="text-sm font-medium text-gray-800">{{ $order->shippingAddress->recipient_name }}</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    <svg class="inline w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $order->shippingAddress->phone }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Lengkap:</label>
                                <p class="text-xs text-gray-700 leading-relaxed">
                                    {{ $order->shippingAddress->full_address }}<br>
                                    {{ $order->shippingAddress->district }}, {{ $order->shippingAddress->city_name }}<br>
                                    {{ $order->shippingAddress->province_name }}, {{ $order->shippingAddress->postal_code }}
                                </p>
                            </div>
                            <div class="pt-3 border-t">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Layanan Dipilih:</label>
                                <p class="text-sm text-gray-800">{{ $order->shipping_service }}</p>
                                <p class="text-xs text-gray-500">Estimasi: {{ $order->shipping_etd }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showManualForm() {
    // Toggle forms
    document.getElementById('manual-form').classList.remove('hidden');
    document.getElementById('biteship-form').classList.add('hidden');

    // Toggle button styles
    document.getElementById('btn-manual').classList.add('border-green-600');
    document.getElementById('btn-manual').classList.remove('border-gray-300');
    document.getElementById('btn-biteship').classList.add('border-gray-300');
    document.getElementById('btn-biteship').classList.remove('border-blue-600');
}

function showBiteshipForm() {
    // Toggle forms
    document.getElementById('manual-form').classList.add('hidden');
    document.getElementById('biteship-form').classList.remove('hidden');

    // Toggle button styles
    document.getElementById('btn-biteship').classList.add('border-blue-600');
    document.getElementById('btn-biteship').classList.remove('border-gray-300');
    document.getElementById('btn-manual').classList.add('border-gray-300');
    document.getElementById('btn-manual').classList.remove('border-green-600');
}

// Default show manual form on page load
document.addEventListener('DOMContentLoaded', function() {
    showManualForm();
});
</script>
@endsection
