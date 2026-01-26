@extends('layouts.app')

@section('title', 'Checkout - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Review pesanan Anda dan selesaikan pembayaran</p>
        </div>

        <!-- Warning if any village hasn't set coordinates -->
        @if(isset($villagesOrigin) && $villagesOrigin->where('has_origin', false)->count() > 0)
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Ongkos Kirim Belum Tersedia</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Beberapa desa penjual belum mengatur koordinat lokasi pengiriman. Ongkos kirim tidak dapat dihitung untuk produk dari desa:</p>
                            <ul class="list-disc list-inside mt-1">
                                @foreach($villagesOrigin->where('has_origin', false) as $village)
                                    <li>{{ $village['village_name'] }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2">Silakan hubungi penjual atau pilih produk dari desa lain.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('user.orders.store') }}" method="POST" id="checkout-form">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Order Items -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Produk yang Dipesan -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b bg-green-50">
                            <h2 class="text-lg font-semibold text-gray-900">Produk yang Dipesan</h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <div class="p-4 sm:p-6">
                                    <div class="flex items-start space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            @if($item->product->images && count($item->product->images) > 0)
                                                <img src="{{ $item->product->getImageDataUri(0) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg">
                                            @else
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">
                                                {{ $item->product->name }}
                                            </h3>
                                            <p class="text-xs sm:text-sm text-gray-500 mb-2">
                                                {{ $item->product->category->name }}
                                            </p>
                                            <div class="flex items-center gap-4 text-sm">
                                                <span class="text-gray-600">{{ $item->quantity }}x</span>
                                                <span class="font-bold text-green-600">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500 mb-1">Subtotal</p>
                                            <p class="font-bold text-gray-900">
                                                Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Alamat Pengiriman</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Penerima -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima *</label>
                                <input type="text" name="recipient_name" required value="{{ old('recipient_name', auth()->user()->name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('recipient_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                                <input type="text" name="phone" required value="{{ old('phone', auth()->user()->phone) }}"
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Pos -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos *</label>
                                <input type="text" name="postal_code" id="postal_code" required value="{{ old('postal_code') }}"
                                       placeholder="12345"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Search Lokasi (Biteship) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Lokasi *</label>
                                <div class="relative">
                                    <input type="text" id="location_search"
                                           placeholder="Ketik nama kota/kecamatan (contoh: Jakarta Selatan)"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <div id="search_results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Ketik untuk mencari lokasi Anda</p>
                            </div>

                            <!-- Hidden fields for coordinates and location -->
                            <input type="hidden" name="latitude" id="destination_latitude">
                            <input type="hidden" name="longitude" id="destination_longitude">
                            <input type="hidden" name="province_id" id="province_id">
                            <input type="hidden" name="province_name" id="province_name">
                            <input type="hidden" name="city_id" id="city_id">
                            <input type="hidden" name="city_name" id="city_name">

                            <!-- Selected Location Display -->
                            <div id="selected_location" class="md:col-span-2 hidden">
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-green-900">Lokasi Terpilih:</p>
                                            <p class="text-sm text-green-700" id="selected_location_text"></p>
                                        </div>
                                        <button type="button" onclick="clearLocation()" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Kecamatan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                <input type="text" name="district" id="district" value="{{ old('district') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>

                            <!-- Alamat Lengkap -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                                <textarea name="full_address" rows="3" required
                                          placeholder="Nama jalan, nomor rumah, RT/RW, dll..."
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('full_address') }}</textarea>
                                @error('full_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan Kurir & Ongkir -->
                    <div class="bg-white rounded-lg shadow-lg p-6" id="shipping-options-section" style="display:none;">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                                Pilih Kurir & Layanan
                            </span>
                        </h2>
                        <div id="shipping-loading" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-green-600"></div>
                            <p class="text-sm text-gray-600 mt-3">Menghitung ongkir dengan Biteship...</p>
                        </div>
                        <div id="shipping-options" class="space-y-3"></div>
                        <div id="shipping-error" class="hidden">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm text-red-700" id="shipping-error-message"></p>
                                <button type="button" onclick="retryShippingCalculation()" class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium">
                                    Coba Lagi
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="shipping_service" id="shipping_service" required>
                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                        <input type="hidden" name="shipping_etd" id="shipping_etd">
                    </div>

                    <!-- Tipe Pembayaran (Cash / Credit) -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tipe Pembayaran</h2>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_type" value="cash" id="payment-type-cash" class="peer hidden" checked onchange="toggleCreditOptions()">
                                <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                            <span class="text-2xl">💵</span>
                                        </div>
                                        <h3 class="font-semibold text-gray-900">Tunai</h3>
                                        <p class="text-xs text-gray-600 mt-1">Bayar langsung penuh</p>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_type" value="credit" id="payment-type-credit" class="peer hidden" onchange="toggleCreditOptions()">
                                <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2">
                                            <span class="text-2xl">📅</span>
                                        </div>
                                        <h3 class="font-semibold text-gray-900">Kredit</h3>
                                        <p class="text-xs text-gray-600 mt-1">Bayar dengan cicilan</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Credit Options (Hidden by default) -->
                        <div id="credit-options" class="hidden mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-4">Pengaturan Kredit</h3>

                            <!-- Pilih Paket Cicilan -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Paket Cicilan *</label>
                                <select name="installment_plan_id" id="installment_plan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="calculateCredit()">
                                    <option value="">Pilih Paket Cicilan</option>
                                    @foreach($installmentPlans as $plan)
                                        <option value="{{ $plan->id }}" data-months="{{ $plan->months }}" data-rate="{{ $plan->interest_rate }}">
                                            {{ $plan->name }} - {{ $plan->interest_rate }}% ({{ $plan->months }} bulan)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Down Payment -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Uang Muka (DP) *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                    <input type="number" name="down_payment" id="down_payment" min="0" value="0"
                                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                           oninput="calculateCredit()">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Minimal 0% (optional)</p>
                            </div>

                            <!-- Credit Summary -->
                            <div id="credit-summary" class="hidden mt-4 p-4 bg-white border border-green-300 rounded-lg">
                                <h4 class="font-semibold text-gray-900 mb-3">Rincian Kredit:</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Harga:</span>
                                        <span class="font-semibold" id="credit-total-price">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Uang Muka (DP):</span>
                                        <span class="font-semibold text-blue-600" id="credit-down-payment">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Pokok Pinjaman:</span>
                                        <span class="font-semibold" id="credit-principal">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Bunga Flat:</span>
                                        <span class="font-semibold text-orange-600" id="credit-interest">Rp 0</span>
                                    </div>
                                    <hr>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900">Total Kredit:</span>
                                        <span class="font-bold text-green-600" id="credit-total-amount">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900">Cicilan/Bulan:</span>
                                        <span class="font-bold text-lg text-green-600" id="credit-monthly-payment">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metode Pembayaran (Fixed Midtrans) -->
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Gateway Pembayaran</h3>
                            <div class="p-4 border-2 border-green-500 rounded-lg bg-green-50">
                                <input type="hidden" name="payment_method" value="midtrans">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-1">Pembayaran Online - Midtrans</h3>
                                        <p class="text-sm text-gray-600 mb-3" id="payment-description">
                                            <span id="cash-desc">Bayar penuh dengan berbagai metode pembayaran</span>
                                            <span id="credit-desc" class="hidden">Bayar uang muka (DP) terlebih dahulu, sisanya cicilan bulanan</span>
                                        </p>

                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                            <div class="flex items-center gap-2 text-xs bg-white px-3 py-2 rounded border border-gray-200">
                                                <span>💳</span>
                                                <span class="font-medium">Credit Card</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs bg-white px-3 py-2 rounded border border-gray-200">
                                                <span>🏦</span>
                                                <span class="font-medium">Virtual Account</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs bg-white px-3 py-2 rounded border border-gray-200">
                                                <span>📱</span>
                                                <span class="font-medium">GoPay</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs bg-white px-3 py-2 rounded border border-gray-200">
                                                <span>🛒</span>
                                                <span class="font-medium">ShopeePay</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs bg-white px-3 py-2 rounded border border-gray-200">
                                                <span>📲</span>
                                                <span class="font-medium">QRIS</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs bg-white px-3 py-2 rounded border border-gray-200">
                                                <span>🏪</span>
                                                <span class="font-medium">Indomaret</span>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex items-center gap-2 text-xs text-green-700">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Pembayaran dijamin aman oleh Midtrans</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('payment_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan (Opsional)</h2>
                        <textarea name="customer_notes" rows="4"
                                  placeholder="Tambahkan catatan untuk penjual..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('customer_notes') }}</textarea>
                        @error('customer_notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 lg:sticky lg:top-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Item</span>
                                <span class="font-semibold">{{ $cartItems->sum('quantity') }} produk</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal Produk</span>
                                <span class="font-semibold" id="subtotal-display">
                                    Rp {{ number_format($cartItems->sum(function($item) { return $item->quantity * $item->product->price; }), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="font-semibold text-blue-600" id="shipping-cost-display">Rp 0</span>
                            </div>
                            <hr>
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-gray-900">Total Pembayaran</span>
                                <span class="text-green-600" id="total-display">
                                    Rp {{ number_format($cartItems->sum(function($item) { return $item->quantity * $item->product->price; }), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <button type="submit" id="submit-order-btn" disabled
                                class="w-full py-3 px-4 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2 bg-gray-300 text-gray-500 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span id="submit-btn-text">Lengkapi Data Terlebih Dahulu</span>
                        </button>

                        <!-- Validation Status -->
                        <div id="validation-status" class="mt-4 space-y-2 text-sm">
                            <div id="status-address" class="flex items-center gap-2 text-red-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>Alamat pengiriman belum lengkap</span>
                            </div>
                            <div id="status-shipping" class="flex items-center gap-2 text-red-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>Kurir & layanan belum dipilih</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="mt-6 p-4 bg-green-50 rounded-lg">
                            <h3 class="font-semibold text-green-900 mb-2 text-sm flex items-center gap-1">
                                📋 Cara Pembayaran:
                            </h3>
                            <ul class="text-xs text-green-800 space-y-1">
                                <li>• Klik tombol "Lanjut ke Pembayaran"</li>
                                <li>• Pilih metode pembayaran yang Anda inginkan</li>
                                <li>• Selesaikan pembayaran sesuai instruksi</li>
                                <li>• Order otomatis diproses setelah pembayaran berhasil</li>
                            </ul>
                        </div>

                        <!-- Biteship Powered -->
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-500">Powered by</p>
                            <p class="text-sm font-semibold text-gray-700">Biteship</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// ========================================
// BITESHIP CHECKOUT INTEGRATION
// ========================================

// Data dari backend
const productTotal = {{ $productTotal }};
const subtotalProduct = productTotal;
const villagesOrigin = @json(isset($villagesOrigin) ? $villagesOrigin : []);
const cartItemsData = {!! json_encode($cartItems->map(function($item) {
    return [
        'village_id' => $item->product->village_id ?? null,
        'name' => $item->product->name,
        'price' => $item->product->price,
        'weight' => $item->product->weight ?? 1000,
        'length' => $item->product->length ?? 10,
        'width' => $item->product->width ?? 10,
        'height' => $item->product->height ?? 10,
        'quantity' => $item->quantity
    ];
})) !!};

let selectedShippingCost = 0;
let destinationCoordinates = null;
let searchTimeout = null;

// ========================================
// CREDIT CALCULATOR
// ========================================

function toggleCreditOptions() {
    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
    const creditOptions = document.getElementById('credit-options');
    const cashDesc = document.getElementById('cash-desc');
    const creditDesc = document.getElementById('credit-desc');

    if (paymentType === 'credit') {
        creditOptions.classList.remove('hidden');
        cashDesc.classList.add('hidden');
        creditDesc.classList.remove('hidden');
    } else {
        creditOptions.classList.add('hidden');
        cashDesc.classList.remove('hidden');
        creditDesc.classList.add('hidden');
        // Reset credit summary
        document.getElementById('credit-summary').classList.add('hidden');
    }
}

function calculateCredit() {
    const planSelect = document.getElementById('installment_plan_id');
    const downPaymentInput = document.getElementById('down_payment');

    if (!planSelect.value || !downPaymentInput.value) {
        document.getElementById('credit-summary').classList.add('hidden');
        return;
    }

    const selectedOption = planSelect.options[planSelect.selectedIndex];
    const months = parseInt(selectedOption.getAttribute('data-months'));
    const interestRate = parseFloat(selectedOption.getAttribute('data-rate'));
    const downPayment = parseFloat(downPaymentInput.value) || 0;

    // Calculate with flat interest
    const totalPrice = productTotal + selectedShippingCost;
    const principal = totalPrice - downPayment;
    const interest = principal * (interestRate / 100);
    const totalCredit = principal + interest;
    const monthlyPayment = totalCredit / months;

    // Display results
    document.getElementById('credit-total-price').textContent = formatRupiah(totalPrice);
    document.getElementById('credit-down-payment').textContent = formatRupiah(downPayment);
    document.getElementById('credit-principal').textContent = formatRupiah(principal);
    document.getElementById('credit-interest').textContent = formatRupiah(interest) + ` (${interestRate}%)`;
    document.getElementById('credit-total-amount').textContent = formatRupiah(totalCredit);
    document.getElementById('credit-monthly-payment').textContent = formatRupiah(monthlyPayment);

    document.getElementById('credit-summary').classList.remove('hidden');
}

function formatRupiah(amount) {
    return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

// ========================================
// LOCATION SEARCH WITH BITESHIP
// ========================================

document.getElementById('location_search').addEventListener('input', function(e) {
    const query = e.target.value.trim();

    clearTimeout(searchTimeout);

    if (query.length < 3) {
        document.getElementById('search_results').classList.add('hidden');
        return;
    }

    searchTimeout = setTimeout(() => {
        searchLocation(query);
    }, 500);
});

async function searchLocation(query) {
    try {
        // Use Nominatim OpenStreetMap for free geocoding
        const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)},Indonesia&format=json&addressdetails=1&limit=10`, {
            headers: {
                'User-Agent': 'SiDesa-Marketplace'
            }
        });
        const data = await response.json();

        console.log('Nominatim response:', data);

        if (data && data.length > 0) {
            displaySearchResults(data);
        } else {
            document.getElementById('search_results').innerHTML = '<div class="p-3 text-sm text-gray-500">Lokasi tidak ditemukan. Coba dengan nama yang lebih spesifik (contoh: "Kota Jakarta Selatan")</div>';
            document.getElementById('search_results').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error searching location:', error);
        document.getElementById('search_results').innerHTML = '<div class="p-3 text-sm text-red-500">Gagal mencari lokasi. Silakan coba lagi.</div>';
        document.getElementById('search_results').classList.remove('hidden');
    }
}

function displaySearchResults(areas) {
    const resultsDiv = document.getElementById('search_results');
    resultsDiv.innerHTML = '';

    areas.slice(0, 10).forEach(area => {
        const div = document.createElement('div');
        div.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0';

        // Extract address details from Nominatim
        const address = area.address || {};
        const displayName = area.display_name || area.name;
        const city = address.city || address.county || address.state_district || '';
        const state = address.state || '';
        const postcode = address.postcode || '';

        div.innerHTML = `
            <p class="text-sm font-medium text-gray-900">${displayName}</p>
            <p class="text-xs text-gray-500">${city}${city && state ? ', ' : ''}${state}</p>
            ${postcode ? `<p class="text-xs text-gray-400">Kode Pos: ${postcode}</p>` : ''}
        `;

        div.addEventListener('click', () => selectLocation(area));
        resultsDiv.appendChild(div);
    });

    resultsDiv.classList.remove('hidden');
}

function selectLocation(area) {
    // Extract address from Nominatim
    const address = area.address || {};

    // Set coordinates
    destinationCoordinates = {
        latitude: parseFloat(area.lat),
        longitude: parseFloat(area.lon),
        postal_code: address.postcode || ''
    };

    // Fill form fields
    document.getElementById('destination_latitude').value = destinationCoordinates.latitude;
    document.getElementById('destination_longitude').value = destinationCoordinates.longitude;
    document.getElementById('postal_code').value = address.postcode || '';
    document.getElementById('province_name').value = address.state || '';
    document.getElementById('city_name').value = address.city || address.county || address.state_district || '';
    document.getElementById('district').value = address.suburb || address.village || '';

    // Set hidden IDs (use names as proxy since we don't have IDs)
    document.getElementById('province_id').value = address.state || '';
    document.getElementById('city_id').value = address.city || address.county || '';

    // Display selected location
    const locationText = area.display_name;
    document.getElementById('selected_location_text').textContent = locationText;
    document.getElementById('selected_location').classList.remove('hidden');

    // Hide search results
    document.getElementById('search_results').classList.add('hidden');
    document.getElementById('location_search').value = locationText;

    // Calculate shipping
    calculateShippingBiteship();

    // Validate form
    validateForm();
}

function clearLocation() {
    destinationCoordinates = null;
    document.getElementById('selected_location').classList.add('hidden');
    document.getElementById('location_search').value = '';
    document.getElementById('destination_latitude').value = '';
    document.getElementById('destination_longitude').value = '';
    document.getElementById('shipping-options-section').style.display = 'none';
    selectedShippingCost = 0;
    updateTotal();
    validateForm();
}

// ========================================
// CALCULATE SHIPPING WITH BITESHIP
// ========================================

async function calculateShippingBiteship() {
    if (!destinationCoordinates) {
        console.log('No destination coordinates');
        return;
    }

    const shippingSection = document.getElementById('shipping-options-section');
    const shippingLoading = document.getElementById('shipping-loading');
    const shippingOptions = document.getElementById('shipping-options');
    const shippingError = document.getElementById('shipping-error');

    shippingSection.style.display = 'block';
    shippingLoading.style.display = 'block';
    shippingOptions.innerHTML = '';
    shippingError.classList.add('hidden');

    let allRates = [];

    try {
        // Single shop - use default origin from env or config
        // For Amanah Shop, all products ship from one location
        const shopOrigin = {
            latitude: {{ config('services.biteship.default_origin_lat', -6.175110) }},
            longitude: {{ config('services.biteship.default_origin_lng', 106.865036) }},
            postal_code: '{{ config('services.biteship.default_origin_postal', '12345') }}'
        };

        // Map all cart items for shipping
        const allItems = cartItemsData.map(item => ({
            name: item.name,
            description: item.name,
            value: item.price * item.quantity,
            weight: item.weight * item.quantity,
            length: item.length || 10,
            width: item.width || 10,
            height: item.height || 10,
            quantity: item.quantity
        }));

        const requestPayload = {
            origin_latitude: shopOrigin.latitude,
            origin_longitude: shopOrigin.longitude,
            origin_postal_code: shopOrigin.postal_code,
            destination_latitude: destinationCoordinates.latitude,
            destination_longitude: destinationCoordinates.longitude,
            destination_postal_code: destinationCoordinates.postal_code,
            couriers: 'jne,jnt,sicepat,tiki,anteraja,ninja,lion,idexpress',
            items: allItems
        };

        console.log('Biteship Request for Amanah Shop:', requestPayload);

        const response = await fetch('/api/biteship/rates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(requestPayload)
        });

        const data = await response.json();
        console.log('Biteship Response:', data);

        if (data.success && data.data && data.data.length > 0) {
            console.log('Adding rates:', data.data);
            allRates = data.data;
        } else {
            console.warn('No rates returned:', data);
        }

        shippingLoading.style.display = 'none';

        console.log('Total rates collected:', allRates.length, allRates);

        if (allRates.length > 0) {
            displayBiteshipRates(allRates);
        } else {
            shippingError.classList.remove('hidden');
            document.getElementById('shipping-error-message').textContent = 'Tidak ada layanan pengiriman tersedia untuk lokasi ini. Silakan coba lagi atau hubungi admin.';
        }
    } catch (error) {
        console.error('Error calculating shipping:', error);
        shippingLoading.style.display = 'none';
        shippingError.classList.remove('hidden');
        document.getElementById('shipping-error-message').textContent = 'Gagal menghitung ongkir. Silakan coba lagi atau pilih lokasi lain.';
    }
}

function retryShippingCalculation() {
    calculateShippingBiteship();
}

// ========================================
// DISPLAY BITESHIP RATES
// ========================================

function displayBiteshipRates(rates) {
    const container = document.getElementById('shipping-options');
    container.innerHTML = '';

    const ratesContainer = document.createElement('div');
    ratesContainer.className = 'space-y-2';

    rates.forEach(rate => {
        const rateDiv = document.createElement('label');
        rateDiv.className = 'flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-green-300 transition-all';

        const serviceCode = `${rate.courier_code}-${rate.courier_service_code}`;

        rateDiv.innerHTML = `
            <input type="radio" name="shipping_option" value="${rate.price}"
                   data-service="${serviceCode}"
                   data-etd="${rate.duration || ''}"
                   class="w-4 h-4 text-green-600 focus:ring-green-500"
                   onchange="selectShipping(${rate.price}, '${serviceCode}', '${rate.duration || ''}')">
            <div class="ml-3 flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">${rate.courier_name}</p>
                        <p class="text-xs text-gray-500">${rate.courier_service_name}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">Rp ${rate.price.toLocaleString('id-ID')}</p>
                        <p class="text-xs text-gray-500">${rate.duration || 'N/A'}</p>
                    </div>
                </div>
                ${rate.description ? `<p class="text-xs text-gray-400 mt-1">${rate.description}</p>` : ''}
            </div>
        `;

        ratesContainer.appendChild(rateDiv);
    });

    container.appendChild(ratesContainer);
}

// ========================================
// SELECT SHIPPING
// ========================================

function selectShipping(cost, service, etd) {
    selectedShippingCost = cost;
    document.getElementById('shipping_cost').value = cost;
    document.getElementById('shipping_service').value = service;
    document.getElementById('shipping_etd').value = etd;
    updateTotal();

    // Recalculate credit if credit is selected
    const paymentType = document.querySelector('input[name="payment_type"]:checked');
    if (paymentType && paymentType.value === 'credit') {
        calculateCredit();
    }

    validateForm();
}

// ========================================
// UPDATE TOTAL
// ========================================

function updateTotal() {
    const total = subtotalProduct + selectedShippingCost;
    document.getElementById('shipping-cost-display').textContent = 'Rp ' + selectedShippingCost.toLocaleString('id-ID');
    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// ========================================
// FORM VALIDATION
// ========================================

let validationState = {
    address: false,
    shipping: false
};

function validateForm() {
    // Check address fields
    const recipientName = document.querySelector('input[name="recipient_name"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();
    const postalCode = document.querySelector('input[name="postal_code"]').value.trim();
    const fullAddress = document.querySelector('textarea[name="full_address"]').value.trim();
    const hasCoordinates = destinationCoordinates !== null;

    validationState.address = recipientName && phone && postalCode && fullAddress && hasCoordinates;

    // Check shipping service
    const shippingService = document.getElementById('shipping_service').value;
    validationState.shipping = shippingService && shippingService.trim() !== '';

    // Update status indicators
    updateStatusIndicator('status-address', validationState.address, 'Alamat pengiriman sudah lengkap', 'Alamat pengiriman belum lengkap');
    updateStatusIndicator('status-shipping', validationState.shipping, 'Kurir & layanan sudah dipilih', 'Kurir & layanan belum dipilih');

    // Update submit button
    const submitBtn = document.getElementById('submit-order-btn');
    const submitBtnText = document.getElementById('submit-btn-text');
    const allValid = validationState.address && validationState.shipping;

    if (allValid) {
        submitBtn.disabled = false;
        submitBtn.className = 'w-full py-3 px-4 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2 bg-green-600 text-white hover:bg-green-700';
        submitBtn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>';
        submitBtnText.textContent = 'Lanjut ke Pembayaran';
    } else {
        submitBtn.disabled = true;
        submitBtn.className = 'w-full py-3 px-4 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2 bg-gray-300 text-gray-500 cursor-not-allowed';
        submitBtn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>';
        submitBtnText.textContent = 'Lengkapi Data Terlebih Dahulu';
    }
}

function updateStatusIndicator(elementId, isValid, validText, invalidText) {
    const element = document.getElementById(elementId);
    if (isValid) {
        element.className = 'flex items-center gap-2 text-green-600';
        element.querySelector('svg').innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
        element.querySelector('span').textContent = validText;
    } else {
        element.className = 'flex items-center gap-2 text-red-600';
        element.querySelector('svg').innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
        element.querySelector('span').textContent = invalidText;
    }
}

// Setup event listeners for real-time validation
function setupValidationListeners() {
    const addressFields = [
        'input[name="recipient_name"]',
        'input[name="phone"]',
        'input[name="postal_code"]',
        'textarea[name="full_address"]'
    ];

    addressFields.forEach(selector => {
        const element = document.querySelector(selector);
        if (element) {
            element.addEventListener('input', validateForm);
            element.addEventListener('blur', validateForm);
        }
    });

    // Initial validation
    validateForm();
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupValidationListeners);
} else {
    setupValidationListeners();
}

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#location_search') && !e.target.closest('#search_results')) {
        document.getElementById('search_results').classList.add('hidden');
    }
});
</script>
@endsection
