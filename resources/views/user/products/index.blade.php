@extends('layouts.app')

@section('title', 'Semua Produk - BUMDes Marketplace')

@section('content')
<div class="bg-gray-50 min-h-screen" x-data="{ view: 'grid', showFilters: false }">
    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex text-sm" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#3BB77E]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-700 font-medium">Produk</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Page Header with Filter Toggle and View Options -->
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <!-- Left: Title and Results -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">Produk</h1>
                    <p class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold text-[#3BB77E]">{{ $products->count() }}</span> dari <span class="font-semibold">{{ $products->total() }}</span> produk
                    </p>
                </div>

                <!-- Right: View Options and Sort -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Mobile Filter Toggle -->
                    <button @click="showFilters = !showFilters" class="lg:hidden flex items-center gap-2 px-4 py-2 bg-[#3BB77E] text-white rounded-lg hover:bg-[#2a9d65] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>

                    <!-- View Toggle -->
                    <div class="hidden sm:flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                        <button @click="view = 'grid'"
                                :class="view === 'grid' ? 'bg-white shadow-sm' : 'hover:bg-gray-200'"
                                class="p-2 rounded transition-all">
                            <svg class="w-5 h-5" :class="view === 'grid' ? 'text-[#3BB77E]' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button @click="view = 'list'"
                                :class="view === 'list' ? 'bg-white shadow-sm' : 'hover:bg-gray-200'"
                                class="p-2 rounded transition-all">
                            <svg class="w-5 h-5" :class="view === 'list' ? 'text-[#3BB77E]' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Sort Dropdown -->
                    <form method="GET" action="{{ route('products.index') }}" class="flex-1 sm:flex-none" id="sortForm">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="relative">
                            <select name="sort" onchange="document.getElementById('sortForm').submit()"
                                    class="w-full sm:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E] bg-white text-sm appearance-none pr-10">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terlaris</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content: Sidebar + Products -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-80 xl:w-96 shrink-0"
                   x-show="showFilters || window.innerWidth >= 1024"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="opacity-0 -translate-x-4"
                   x-transition:enter-end="opacity-100 translate-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                        <!-- Preserve sort parameter -->
                        <input type="hidden" name="sort" value="{{ request('sort') }}">

                        <!-- Filter Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filter Produk
                            </h3>
                            <button type="button" @click="showFilters = false" class="lg:hidden text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Search -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Produk</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Cari nama produk..."
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E] text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6"></div>

                        <!-- Category Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Kategori Produk</label>
                            <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="category_id" value=""
                                           {{ !request('category_id') ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#3BB77E] focus:ring-[#3BB77E] border-gray-300">
                                    <span class="text-sm text-gray-700 font-medium">Semua Kategori</span>
                                </label>
                                @foreach($categories as $category)
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="category_id" value="{{ $category->id }}"
                                           {{ request('category_id') == $category->id ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#3BB77E] focus:ring-[#3BB77E] border-gray-300">
                                    <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                    <span class="ml-auto text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                        {{ $category->products()->where('status', 'active')->count() }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6"></div>

                        <!-- Village Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Desa</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="village_id" value=""
                                           {{ !request('village_id') ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#3BB77E] focus:ring-[#3BB77E] border-gray-300">
                                    <span class="text-sm text-gray-700 font-medium">Semua Desa</span>
                                </label>
                                @foreach($villages as $village)
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="village_id" value="{{ $village->id }}"
                                           {{ request('village_id') == $village->id ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#3BB77E] focus:ring-[#3BB77E] border-gray-300">
                                    <span class="text-sm text-gray-700">{{ $village->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6"></div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Rentang Harga</label>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Harga Minimum</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                                               placeholder="0"
                                               class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E] text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Harga Maksimum</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                                               placeholder="1000000"
                                               class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E] text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6"></div>

                        <!-- Product Type Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Jenis Produk</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="type" value=""
                                           {{ !request('type') ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#3BB77E] focus:ring-[#3BB77E] border-gray-300">
                                    <span class="text-sm text-gray-700 font-medium">Semua Jenis</span>
                                </label>
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="type" value="barang"
                                           {{ request('type') == 'barang' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#3BB77E] focus:ring-[#3BB77E] border-gray-300">
                                    <span class="text-sm text-gray-700">Produk Barang</span>
                                </label>
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="type" value="jasa"
                                           {{ request('type') == 'jasa' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#3BB77E] focus:ring-[#3BB77E] border-gray-300">
                                    <span class="text-sm text-gray-700">Produk Jasa</span>
                                </label>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button type="submit"
                                    class="flex-1 bg-[#3BB77E] text-white px-4 py-2.5 rounded-lg hover:bg-[#2a9d65] transition-colors font-medium text-sm">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('products.index') }}"
                               class="flex-1 bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                @if($products->count() > 0)
                    <!-- Grid View -->
                    <div x-show="view === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
                        @foreach($products as $product)
                            @php
                                // Calculate actual sold quantity from orders
                                $sold = DB::table('order_items')
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->where('order_items.product_id', $product->id)
                                    ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                                    ->where('orders.payment_status', 'paid')
                                    ->sum('order_items.quantity');
                            @endphp
                            <a href="{{ route('products.show', $product) }}"
                               class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-[#3BB77E] transition-all duration-300 group block">
                                <!-- Product Image -->
                                <div class="relative overflow-hidden bg-gray-100">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                             class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Stock Badge -->
                                    <div class="absolute top-3 left-3">
                                        @if($product->stock > 50)
                                            <span class="bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">Stok Banyak</span>
                                        @elseif($product->stock > 10)
                                            <span class="bg-blue-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">Tersedia</span>
                                        @elseif($product->stock > 0)
                                            <span class="bg-orange-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg animate-pulse">Stok Terbatas</span>
                                        @else
                                            <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">Habis</span>
                                        @endif
                                    </div>

                                    <!-- Type Badge -->
                                    @if($product->type === 'jasa')
                                        <div class="absolute top-3 right-3">
                                            <span class="bg-purple-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">Jasa</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="p-4">
                                    <!-- Category -->
                                    <div class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</div>

                                    <!-- Product Name -->
                                    <h3 class="font-semibold text-gray-800 mb-2 text-sm line-clamp-2 leading-tight group-hover:text-[#3BB77E] transition-colors">
                                        {{ $product->name }}
                                    </h3>

                                    <!-- Rating -->
                                    <div class="flex items-center gap-1 mb-2">
                                        @php
                                            $avgRating = $product->approvedReviews()->avg('rating') ?? 0;
                                            $reviewCount = $product->approvedReviews()->count();
                                        @endphp
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($avgRating))
                                                    <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @elseif($i - 0.5 <= $avgRating)
                                                    <svg class="w-3.5 h-3.5 text-yellow-400" viewBox="0 0 20 20">
                                                        <defs>
                                                            <linearGradient id="half-{{ $product->id }}-{{ $i }}">
                                                                <stop offset="50%" stop-color="#FBBF24"/>
                                                                <stop offset="50%" stop-color="#D1D5DB"/>
                                                            </linearGradient>
                                                        </defs>
                                                        <path fill="url(#half-{{ $product->id }}-{{ $i }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-500">({{ $reviewCount }})</span>
                                    </div>

                                    <!-- Village & Sold -->
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                        @if($product->village)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span class="truncate">{{ $product->village->name }}</span>
                                            </div>
                                        @endif
                                        <span class="text-gray-400">{{ $sold }} terjual</span>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <span class="text-lg font-bold text-[#3BB77E]">
                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <div class="bg-[#3BB77E] text-white p-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- List View -->
                    <div x-show="view === 'list'" class="space-y-4 mb-8">
                        @foreach($products as $product)
                            @php
                                // Calculate actual sold quantity from orders
                                $sold = DB::table('order_items')
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->where('order_items.product_id', $product->id)
                                    ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                                    ->where('orders.payment_status', 'paid')
                                    ->sum('order_items.quantity');
                            @endphp
                            <a href="{{ route('products.show', $product) }}"
                               class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-[#3BB77E] transition-all duration-300 group flex">
                                <!-- Product Image -->
                                <div class="relative overflow-hidden bg-gray-100 w-48 shrink-0">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Stock Badge -->
                                    <div class="absolute top-3 left-3">
                                        @if($product->stock > 50)
                                            <span class="bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">Stok Banyak</span>
                                        @elseif($product->stock > 10)
                                            <span class="bg-blue-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">Tersedia</span>
                                        @elseif($product->stock > 0)
                                            <span class="bg-orange-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg animate-pulse">Stok Terbatas</span>
                                        @else
                                            <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">Habis</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <!-- Category & Type -->
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-xs text-gray-500">{{ $product->category->name }}</span>
                                                @if($product->type === 'jasa')
                                                    <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-0.5 rounded-full">Jasa</span>
                                                @endif
                                            </div>

                                            <!-- Product Name -->
                                            <h3 class="font-bold text-gray-900 mb-2 text-lg group-hover:text-[#3BB77E] transition-colors">
                                                {{ $product->name }}
                                            </h3>

                                            <!-- Description -->
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                                {{ $product->description }}
                                            </p>

                                            <!-- Rating & Village -->
                                            <div class="flex items-center gap-4 mb-3">
                                                @php
                                                    $avgRating = $product->approvedReviews()->avg('rating') ?? 0;
                                                    $reviewCount = $product->approvedReviews()->count();
                                                @endphp
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($avgRating))
                                                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                    <span class="text-sm text-gray-500 ml-1">({{ $reviewCount }})</span>
                                                </div>

                                                @if($product->village)
                                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <span>{{ $product->village->name }}</span>
                                                    </div>
                                                @endif

                                                <span class="text-sm text-gray-400">{{ $sold }} terjual</span>
                                            </div>
                                        </div>

                                        <!-- Price & Action -->
                                        <div class="text-right ml-6">
                                            <div class="text-2xl font-bold text-[#3BB77E] mb-3">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                            <div class="bg-[#3BB77E] text-white px-4 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity inline-flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Detail
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4m0 0H8m4 0h4"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada produk ditemukan</h3>
                        <p class="text-gray-600 mb-6">Coba ubah filter pencarian Anda atau lihat semua produk</p>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 bg-[#3BB77E] text-white px-6 py-3 rounded-lg hover:bg-[#2a9d65] transition-colors font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filter & Lihat Semua
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filter form on radio change
    const filterForm = document.getElementById('filterForm');
    const radioInputs = filterForm.querySelectorAll('input[type="radio"]');

    radioInputs.forEach(function(radio) {
        radio.addEventListener('change', function() {
            filterForm.submit();
        });
    });

    // Debounce for price inputs
    const priceInputs = filterForm.querySelectorAll('input[type="number"]');
    let priceTimeout;

    priceInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(function() {
                filterForm.submit();
            }, 800);
        });
    });

    // Debounce for search input
    const searchInput = filterForm.querySelector('input[name="search"]');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                filterForm.submit();
            }, 500);
        });
    }
});
</script>

@endsection
