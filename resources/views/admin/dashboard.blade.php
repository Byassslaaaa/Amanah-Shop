@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-600 mt-1">Berikut adalah ringkasan aktivitas platform BUMDes Marketplace</p>
</div>

<!-- Shipping Origin Alert (for Village Admin only) -->
@if(isset($needsShippingSetup) && $needsShippingSetup)
<div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-medium text-yellow-800">
                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Penting: Atur Lokasi Asal Pengiriman
            </h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Desa Anda belum mengatur lokasi asal pengiriman. Tanpa setting ini, pembeli tidak dapat menghitung ongkos kirim untuk produk Anda.</p>
                <p class="mt-2">Silakan atur lokasi pengiriman agar produk Anda dapat dibeli dengan ongkir yang akurat.</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.shipping-settings.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Atur Lokasi Pengiriman Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Pelanggan</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if(auth()->user()->isSuperAdmin())
    <!-- Total Villages (SuperAdmin only) -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Desa</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalVillages) }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>
    @endif

    <!-- Total Products -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Produk</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalProducts) }}</p>
                <p class="text-purple-100 text-xs mt-1">{{ $activeProducts }} aktif, {{ $inactiveProducts }} nonaktif</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Total Pesanan</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalOrders) }}</p>
                <p class="text-orange-100 text-xs mt-1">{{ $pendingOrders }} pending, {{ $completedOrders }} selesai</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp{{ number_format($totalRevenue) }}</p>
            </div>
            <div class="text-green-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Stok Menipis</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($lowStockProducts) }}</p>
            </div>
            <div class="text-yellow-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Out of Stock -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Stok Habis</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($outOfStockProducts) }}</p>
            </div>
            <div class="text-red-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Kategori</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCategories) }}</p>
            </div>
            <div class="text-indigo-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Recent Products - 2 columns -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Produk Terbaru</h3>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="p-6">
            @if($recentProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProducts as $product)
                        <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition">
                            <div class="flex-shrink-0">
                                @if($product->images && count($product->images) > 0)
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-500">{{ $product->category->name }}</span>
                                    @if($product->village)
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">{{ $product->village->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">Rp{{ number_format($product->price) }}</p>
                                <p class="text-xs text-gray-500 mt-1">Stok: {{ $product->stock }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p>Belum ada produk</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Type Distribution -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Jenis Produk</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Barang -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Produk Barang</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($productsByType['barang']) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $totalProducts > 0 ? ($productsByType['barang'] / $totalProducts * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $totalProducts > 0 ? number_format(($productsByType['barang'] / $totalProducts * 100), 1) : 0 }}% dari total produk</p>
                </div>

                <!-- Jasa -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Produk Jasa</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($productsByType['jasa']) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ $totalProducts > 0 ? ($productsByType['jasa'] / $totalProducts * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $totalProducts > 0 ? number_format(($productsByType['jasa'] / $totalProducts * 100), 1) : 0 }}% dari total produk</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top Categories -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Kategori Teratas</h3>
        </div>
        <div class="p-6">
            @if($categoryStats->count() > 0)
                <div class="space-y-3">
                    @foreach($categoryStats as $category)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-white font-bold">
                                    {{ substr($category->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $category->type === 'barang' ? 'Barang' : 'Jasa' }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($category->products_count) }} produk</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-8">Belum ada kategori</p>
            @endif
        </div>
    </div>

    <!-- Top Villages (SuperAdmin only) -->
    @if(auth()->user()->isSuperAdmin() && $topVillages)
        <div class="bg-white rounded-xl shadow">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Desa Teratas</h3>
                <a href="{{ route('admin.villages.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    Kelola Desa →
                </a>
            </div>
            <div class="p-6">
                @if($topVillages->count() > 0)
                    <div class="space-y-3">
                        @foreach($topVillages as $village)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $village->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $village->city }}, {{ $village->province }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($village->products_count) }} produk</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Belum ada desa</p>
                @endif
            </div>
        </div>
    @else
        <!-- Quick Actions for Admin -->
        <div class="bg-white rounded-xl shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Tambah Produk</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                        <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Lihat Produk</span>
                    </a>
                    <a href="{{ route('home') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Ke Website</span>
                    </a>
                    <a href="{{ route('profile') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
                        <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Profil Saya</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
