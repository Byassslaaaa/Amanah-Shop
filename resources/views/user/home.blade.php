@extends('layouts.app')

@section('title', 'Amanah Shop - Toko Online Kebutuhan Rumah Tangga & Lifestyle')

@section('content')
    <!-- Hero Section - Nest Style -->
    <section class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Main Banner with Newsletter -->
                    <div class="bg-gradient-to-r from-[#BCE3C9] via-[#9FD3B8] to-[#BCE3C9] rounded-3xl p-12 mb-6 relative overflow-hidden min-h-[420px] shadow-sm hover:shadow-md transition-shadow duration-300">
                        <!-- Decorative Circles -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none">
                            <div class="absolute top-20 right-40 w-32 h-32 rounded-full bg-white opacity-10 animate-pulse"></div>
                            <div class="absolute bottom-20 right-20 w-24 h-24 rounded-full bg-white opacity-10 animate-pulse" style="animation-delay: 0.5s;"></div>
                            <div class="absolute top-40 left-1/3 w-20 h-20 rounded-full bg-white opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
                        </div>

                        <div class="relative z-10 max-w-2xl">
                            <h1 class="text-[52px] font-bold text-[#253D4E] mb-3 leading-tight">
                                Perabotan & Kebutuhan<br>
                                <span class="text-[#253D4E]">Rumah Tangga</span>
                            </h1>
                            <p class="text-[20px] text-[#7E7E7E] mb-8 font-medium">Belanja kebutuhan rumah tangga dengan harga terjangkau</p>

                            <!-- Newsletter Form -->
                            <form class="flex gap-0 bg-white rounded-full overflow-hidden shadow-lg hover:shadow-xl transition-shadow max-w-lg">
                                <div class="flex items-center pl-6 pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="email" placeholder="Your email address"
                                    class="flex-1 py-4 px-2 border-0 focus:outline-none focus:ring-0 text-sm placeholder:text-gray-400">
                                <button type="submit"
                                    class="bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold px-8 py-4 transition-all duration-300 text-sm whitespace-nowrap hover:shadow-lg active:scale-95">
                                    Subscribe
                                </button>
                            </form>
                        </div>

                        <!-- Decorative Image -->
                        <div class="absolute right-8 bottom-0 w-1/2 h-full hidden lg:flex items-end justify-end pointer-events-none">
                            <img src="{{ asset('images/hero-products.png') }}" alt="Products"
                                class="h-[90%] w-auto object-contain object-bottom opacity-95" onerror="this.style.display='none'">
                        </div>
                    </div>

            <!-- Three Small Promo Banners -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Banner 1 -->
                <div class="bg-[#F2FCE4] rounded-2xl p-6 hover:shadow-md transition-all relative overflow-hidden group cursor-pointer">
                    <div class="relative z-10">
                        <p class="text-[13px] text-gray-600 mb-1">Diskon Hingga</p>
                        <h3 class="text-[26px] font-bold text-[#253D4E] mb-1.5">17%</h3>
                        <p class="text-[13px] text-gray-600 mb-3">Perabotan rumah</p>
                        <a href="{{ route('products.index', ['category' => 'Perabotan']) }}"
                            class="inline-flex items-center gap-1.5 text-[#3BB77E] font-semibold text-[13px] group-hover:gap-2 transition-all">
                            <span>Belanja Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Banner 2 -->
                <div class="bg-[#FFFCEB] rounded-2xl p-6 hover:shadow-md transition-all relative overflow-hidden group cursor-pointer">
                    <div class="relative z-10">
                        <p class="text-[13px] text-gray-600 mb-1">Koleksi Terbaru</p>
                        <h3 class="text-[26px] font-bold text-[#253D4E] mb-1.5">Fashion</h3>
                        <p class="text-[13px] text-gray-600 mb-3">Pakaian & sepatu</p>
                        <a href="{{ route('products.index', ['category' => 'Pakaian']) }}"
                            class="inline-flex items-center gap-1.5 text-[#3BB77E] font-semibold text-[13px] group-hover:gap-2 transition-all">
                            <span>Belanja Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Banner 3 -->
                <div class="bg-[#ECFFEC] rounded-2xl p-6 hover:shadow-md transition-all relative overflow-hidden group cursor-pointer">
                    <div class="relative z-10">
                        <p class="text-[13px] text-gray-600 mb-1">Produk Terbaik</p>
                        <h3 class="text-[26px] font-bold text-[#253D4E] mb-1.5">Organik</h3>
                        <p class="text-[13px] text-gray-600 mb-3">Tekstil berkualitas</p>
                        <a href="{{ route('products.index', ['category' => 'Tekstil Rumah']) }}"
                            class="inline-flex items-center gap-1.5 text-[#3BB77E] font-semibold text-[13px] group-hover:gap-2 transition-all">
                            <span>Belanja Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Deals Of The Day Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-[32px] font-bold text-[#253D4E]">Deals Of The Day</h2>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 text-[#3BB77E] hover:text-[#2a9d66] font-semibold text-sm transition-colors group">
                    <span>All Deals</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredProducts->take(4) as $product)
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:border-[#BCE3C9] hover:shadow-lg transition-all duration-300 group">
                        <!-- Product Image -->
                        <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden bg-[#F8F8F8] aspect-square">
                            @if ($product->images && count($product->images) > 0)
                                <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-contain p-6 group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <!-- Product Info -->
                        <div class="p-5">
                            <!-- Vendor Name -->
                            <p class="text-xs text-gray-500 mb-2">
                                By <span class="text-[#3BB77E] font-semibold">Amanah Shop</span>
                            </p>

                            <!-- Product Name -->
                            <a href="{{ route('products.show', $product) }}">
                                <h3 class="font-bold text-[#253D4E] text-[15px] mb-3 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug min-h-[2.8rem]">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <!-- Price -->
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-[20px] font-extrabold text-[#3BB77E]">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @php
                                    $originalPrice = $product->price * 1.35;
                                @endphp
                                <span class="text-[13px] text-gray-400 line-through font-medium">
                                    Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Add to Cart Button -->
                            <div onclick="event.stopPropagation()">
                                @auth
                                    <form action="{{ route('user.cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-bold py-2.5 px-4 rounded-lg transition-all duration-300 text-sm shadow-sm hover:shadow-md active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                            <span>Add</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="w-full flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-bold py-2.5 px-4 rounded-lg transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <span>Add</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Products Section with Sidebar -->
    <section class="py-12 bg-white border-t border-gray-100" x-data="{ activeTab: 'all' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Sidebar -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Category List -->
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="border-b border-gray-200 p-4">
                            <h3 class="text-[18px] font-bold text-[#253D4E]">Kategori</h3>
                        </div>
                        <div class="p-4 space-y-2">
                            @foreach ($categories->take(8) as $category)
                                <a href="{{ route('products.index', ['category' => $category->name]) }}"
                                    class="flex items-center justify-between px-3 py-2 text-sm text-gray-700 hover:bg-[#F4F6FA] hover:text-[#3BB77E] rounded-lg transition-all group">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tag text-[#3BB77E] text-xs"></i>
                                        <span class="font-medium">{{ $category->name }}</span>
                                    </div>
                                    <span class="w-8 h-8 rounded-full bg-[#DEF9EC] text-[#3BB77E] flex items-center justify-center text-xs font-bold">
                                        {{ $category->products->count() }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Product Tags -->
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="border-b border-gray-200 p-4">
                            <h3 class="text-[18px] font-bold text-[#253D4E]">Product Tags</h3>
                        </div>
                        <div class="p-4">
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $productTags = [
                                        ['name' => 'Perabotan', 'icon' => 'couch'],
                                        ['name' => 'Pakaian', 'icon' => 'tshirt'],
                                        ['name' => 'Sepatu', 'icon' => 'shoe-prints'],
                                        ['name' => 'Organik', 'icon' => 'leaf'],
                                        ['name' => 'Tekstil', 'icon' => 'rug'],
                                        ['name' => 'Rumah Tangga', 'icon' => 'blender']
                                    ];
                                @endphp
                                @foreach ($productTags as $tag)
                                    <a href="{{ route('products.index', ['search' => $tag['name']]) }}"
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 hover:bg-[#DEF9EC] text-gray-700 hover:text-[#3BB77E] rounded-lg text-xs font-medium transition-all">
                                        <i class="fas fa-{{ $tag['icon'] }}"></i>
                                        <span>{{ $tag['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Products (Small List) -->
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="border-b border-gray-200 p-4">
                            <h3 class="text-[18px] font-bold text-[#253D4E]">Produk</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            @foreach ($featuredProducts->take(3) as $product)
                                <div class="flex gap-3 group">
                                    <a href="{{ route('products.show', $product) }}" class="flex-shrink-0">
                                        @if ($product->images && count($product->images) > 0)
                                            <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                                class="w-20 h-20 object-contain rounded-xl bg-[#F8F8F8] p-2 group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-20 h-20 bg-[#F8F8F8] rounded-xl flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('products.show', $product) }}">
                                            <h4 class="font-bold text-[#253D4E] text-[13px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug">
                                                {{ $product->name }}
                                            </h4>
                                        </a>
                                        <div class="flex items-center gap-0.5 mb-2">
                                            @php
                                                $avgRating = $product->average_rating ?? 0;
                                                $fullStars = floor($avgRating);
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $fullStars)
                                                    <svg class="w-3 h-3 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[15px] font-extrabold text-[#3BB77E]">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] text-gray-400 line-through">
                                                Rp{{ number_format($product->price * 1.3, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Content - Popular Products -->
                <div class="lg:col-span-9">
                    <!-- Section Header with Tabs -->
                    <div class="flex flex-col items-start justify-between mb-8 gap-4">
                        <h2 class="text-[32px] font-bold text-[#253D4E]">Produk Populer</h2>

                        <!-- Filter Tabs -->
                        <div class="flex items-center gap-6 text-sm flex-wrap">
                            <button @click="activeTab = 'all'"
                                :class="activeTab === 'all' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' : 'text-gray-600 hover:text-[#3BB77E] font-medium'"
                                class="pb-2 whitespace-nowrap transition-all">
                                Semua
                            </button>
                            @foreach ($categories->take(5) as $category)
                                <button @click="activeTab = '{{ $category->id }}'"
                                    :class="activeTab === '{{ $category->id }}' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' : 'text-gray-600 hover:text-[#3BB77E] font-medium'"
                                    class="pb-2 whitespace-nowrap transition-all">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @php
                    // Prepare products for all categories
                    $categoryProductsMap = [];
                    $allProductsForDisplay = collect();

                    foreach ($categories as $category) {
                        $categoryProducts = $category->products()
                            ->where('status', 'active')
                            ->where('stock', '>', 0)
                            ->limit(8)
                            ->get();

                        foreach ($categoryProducts as $product) {
                            $product->category_id_for_filter = $category->id;
                            $product->category_name_for_display = $category->name;
                        }

                        $categoryProductsMap[$category->id] = $categoryProducts;
                        $allProductsForDisplay = $allProductsForDisplay->merge($categoryProducts->take(2));
                    }

                    // 2 baris x 4 kolom = 8 produk
                    $allProductsForDisplay = $allProductsForDisplay->take(8);
                @endphp

                {{-- All Products Tab --}}
                @foreach ($allProductsForDisplay as $product)
                    <div x-show="activeTab === 'all'"
                        x-transition:enter="transition ease-out duration-400"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="bg-white rounded-2xl p-5 hover:shadow-2xl hover:border-[#3BB77E] transition-all duration-300 group border border-gray-100 hover:-translate-y-2 flex flex-col">

                        @include('user.partials.product-card', ['product' => $product])
                    </div>
                @endforeach

                {{-- Category-specific Products --}}
                @foreach ($categoryProductsMap as $catId => $products)
                    @foreach ($products as $product)
                        <div x-show="activeTab === '{{ $catId }}'"
                            x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="bg-white rounded-2xl p-5 hover:shadow-2xl hover:border-[#3BB77E] transition-all duration-300 group border border-gray-100 hover:-translate-y-2 flex flex-col">

                            @include('user.partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Deals of The Day Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-[36px] font-bold text-[#253D4E]">Deals Of The Day</h2>
                <a href="{{ route('products.index') }}" class="text-[#3BB77E] hover:text-[#2a9d66] font-bold text-sm flex items-center gap-2 group">
                    <span>All Deals</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Deals Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @foreach ($featuredProducts->take(4) as $index => $product)
                    @php
                        // Variasi badge untuk setiap produk
                        $badges = [
                            ['text' => '13%', 'bg' => 'bg-[#3BB77E]'],
                            ['text' => '66%', 'bg' => 'bg-[#3BB77E]'],
                            ['text' => 'Sale', 'bg' => 'bg-[#3BB77E]'],
                            ['text' => '8%', 'bg' => 'bg-[#FF6B6B]']
                        ];
                        $badgeData = $badges[$index % 4];
                    @endphp
                    <div class="bg-white rounded-2xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group border border-gray-100">
                        <!-- Product Image -->
                        <div class="relative overflow-hidden bg-[#F8F8F8]">
                            <a href="{{ route('products.show', $product) }}">
                                @if ($product->images && count($product->images) > 0)
                                    <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                        class="w-full h-52 object-contain p-4 group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-52 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <!-- Sale Badge -->
                            <span class="absolute top-3 left-3 {{ $badgeData['bg'] }} text-white text-[11px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                                {{ $badgeData['text'] }}
                            </span>

                            <!-- Wishlist Icon -->
                            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                @auth
                                    @php
                                        $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
                                    @endphp
                                    <form action="{{ route('user.favorites.toggle', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-white hover:bg-[#FF6B6B] w-9 h-9 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 group/heart">
                                            @if ($isFavorited)
                                                <svg class="w-5 h-5 text-[#FF6B6B]" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-600 group-hover/heart:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="bg-white hover:bg-[#FF6B6B] w-9 h-9 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 group/heart">
                                        <svg class="w-5 h-5 text-gray-600 group-hover/heart:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-5">
                            <div class="text-[11px] text-gray-400 mb-1.5 font-medium uppercase tracking-wide">{{ $product->category->name }}</div>
                            <h3 class="font-bold text-[#253D4E] text-[14px] mb-2.5 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug min-h-[2.5rem]">
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h3>

                            <!-- Rating -->
                            <div class="flex items-center gap-1 mb-3">
                                @php
                                    $avgRating = $product->average_rating ?? 0;
                                    $fullStars = floor($avgRating);
                                @endphp
                                <div class="flex items-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <svg class="w-3.5 h-3.5 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-[11px] text-gray-500 font-medium">({{ number_format($avgRating, 1) }})</span>
                            </div>

                            <!-- Vendor -->
                            <div class="text-[11px] text-gray-500 mb-3 font-medium">
                                By <span class="text-[#3BB77E] font-semibold">Amanah Shop</span>
                            </div>

                            <!-- Price -->
                            <div class="flex items-baseline gap-2 mb-4">
                                <span class="text-[18px] font-extrabold text-[#3BB77E]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-[13px] text-gray-400 line-through font-medium">Rp{{ number_format($product->price * 1.4, 0, ',', '.') }}</span>
                            </div>

                            <!-- Countdown Timer -->
                            <div class="bg-[#FFF3EB] rounded-lg p-2.5 mb-3" x-data="countdownTimer()">
                                <div class="flex items-center justify-center gap-2 text-xs">
                                    <svg class="w-4 h-4 text-[#FF6B6B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold text-[#253D4E]">Berakhir dalam:</span>
                                    <span class="font-bold text-[#FF6B6B]" x-text="timeLeft"></span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            @php
                                $sold = $product->total_sold ?? 0;
                                $total = $product->stock + $sold;
                                $percentage = $total > 0 ? ($sold / $total) * 100 : 0;
                            @endphp
                            <div class="mb-4">
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-[#3BB77E] h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="text-[11px] text-gray-500 mt-1.5 font-medium">Terjual: {{ $sold }}/{{ $total }}</div>
                            </div>

                            <!-- Add to Cart Button -->
                            @auth
                                <form action="{{ route('user.cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-bold py-2.5 px-4 rounded-lg transition-all duration-300 text-[13px] shadow-sm hover:shadow-md transform hover:-translate-y-0.5 active:translate-y-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <span>Add</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-bold py-2.5 px-4 rounded-lg transition-all duration-300 text-[13px] shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span>Add</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Promotional Banners Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Banner 1 - Save on Organic Juice -->
                <div class="bg-gradient-to-br from-[#FFF8E5] to-[#FFEDCC] rounded-2xl p-8 relative overflow-hidden group hover:shadow-lg transition-all cursor-pointer">
                    <div class="relative z-10">
                        <p class="text-[13px] text-[#FF9933] font-semibold mb-1">Organik</p>
                        <h3 class="text-[32px] font-bold text-[#253D4E] leading-tight mb-3">
                            Save 17%<br>
                            <span class="text-[28px]">on <span class="text-[#3BB77E]">Organic</span><br>Juice</span>
                        </h3>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-2 bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold px-6 py-2.5 rounded-md transition-all text-[13px]">
                            <span>Shop Now</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Banner 2 - Everyday Fresh & Clean -->
                <div class="bg-gradient-to-br from-[#FFE8E8] to-[#FFD4D4] rounded-2xl p-8 relative overflow-hidden group hover:shadow-lg transition-all cursor-pointer">
                    <div class="relative z-10">
                        <h3 class="text-[28px] font-bold text-[#253D4E] leading-tight mb-3">
                            Everyday Fresh &<br>Clean with Our<br>Products
                        </h3>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-2 bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold px-6 py-2.5 rounded-md transition-all text-[13px]">
                            <span>Shop Now</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Banner 3 - The best Organic Products -->
                <div class="bg-gradient-to-br from-[#E5F5FF] to-[#CCE9FF] rounded-2xl p-8 relative overflow-hidden group hover:shadow-lg transition-all cursor-pointer">
                    <div class="relative z-10">
                        <h3 class="text-[28px] font-bold text-[#253D4E] leading-tight mb-3">
                            The best Organic<br>Products Online
                        </h3>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-2 bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold px-6 py-2.5 rounded-md transition-all text-[13px]">
                            <span>Shop Now</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Categories - Icon Grid -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-[36px] font-bold text-[#253D4E]">Shop by Categories</h2>
                <a href="{{ route('products.index') }}" class="text-[#3BB77E] hover:text-[#2a9d66] font-bold text-sm flex items-center gap-2 group">
                    <span>All Categories</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-10 gap-5">
                @php
                    $categoryIcons = [
                        'Perabotan' => 'fa-couch',
                        'Perlengkapan Kamar Tidur' => 'fa-bed',
                        'Pakaian' => 'fa-tshirt',
                        'Sepatu & Alas Kaki' => 'fa-shoe-prints',
                        'Keperluan Rumah Tangga' => 'fa-blender',
                        'Tekstil Rumah' => 'fa-rug',
                        'Aksesoris Rumah' => 'fa-lightbulb',
                        'Lain-lain' => 'fa-boxes'
                    ];
                @endphp

                @foreach ($categories->take(10) as $category)
                    <a href="{{ route('products.index', ['category' => $category->name]) }}"
                        class="flex flex-col items-center text-center p-5 bg-white rounded-2xl hover:shadow-xl border-2 border-transparent hover:border-[#3BB77E] transition-all duration-300 group cursor-pointer transform hover:-translate-y-1">
                        <div class="w-20 h-20 bg-gradient-to-br from-[#F4F6FA] to-[#E8ECEF] rounded-2xl flex items-center justify-center mb-4 group-hover:from-[#DEF9EC] group-hover:to-[#BCE3C9] transition-all duration-300 shadow-sm group-hover:shadow-md">
                            <i class="fas {{ $categoryIcons[$category->name] ?? 'fa-tag' }} text-[#3BB77E] text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-[#253D4E] text-[13px] mb-2 group-hover:text-[#3BB77E] transition-colors line-clamp-2 min-h-[2rem]">
                            {{ $category->name }}
                        </h3>
                        <span class="text-[11px] text-gray-500 font-medium">{{ $category->products->count() }} items</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Multiple Product Sections - 4 Columns Layout -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Top Selling Column -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[22px] font-bold text-[#253D4E]">Terlaris</h3>
                        <svg class="w-5 h-5 text-[#FF6B6B]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="space-y-4">
                        @foreach ($featuredProducts->take(3) as $product)
                            <div class="flex gap-4 bg-white rounded-xl p-4 hover:shadow-lg border border-gray-100 transition-all duration-300 group">
                                <a href="{{ route('products.show', $product) }}" class="flex-shrink-0">
                                    @if ($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                            class="w-24 h-24 object-contain rounded-xl bg-[#F8F8F8] p-2 group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-24 h-24 bg-[#F8F8F8] rounded-xl flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $product) }}">
                                        <h4 class="font-bold text-[#253D4E] text-[14px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug">
                                            {{ $product->name }}
                                        </h4>
                                    </a>
                                    <div class="flex items-center gap-0.5 mb-2">
                                        @php
                                            $avgRating = $product->average_rating ?? 0;
                                            $fullStars = floor($avgRating);
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $fullStars)
                                                <svg class="w-3.5 h-3.5 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[17px] font-extrabold text-[#3BB77E]">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Trending Products Column -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[22px] font-bold text-[#253D4E]">Trending</h3>
                        <svg class="w-5 h-5 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="space-y-4">
                        @foreach ($featuredProducts->skip(3)->take(3) as $product)
                            <div class="flex gap-4 bg-white rounded-xl p-4 hover:shadow-lg border border-gray-100 transition-all duration-300 group">
                                <a href="{{ route('products.show', $product) }}" class="flex-shrink-0">
                                    @if ($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                            class="w-24 h-24 object-contain rounded-xl bg-[#F8F8F8] p-2 group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-24 h-24 bg-[#F8F8F8] rounded-xl flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $product) }}">
                                        <h4 class="font-bold text-[#253D4E] text-[14px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug">
                                            {{ $product->name }}
                                        </h4>
                                    </a>
                                    <div class="flex items-center gap-0.5 mb-2">
                                        @php
                                            $avgRating = $product->average_rating ?? 0;
                                            $fullStars = floor($avgRating);
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $fullStars)
                                                <svg class="w-3.5 h-3.5 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[17px] font-extrabold text-[#3BB77E]">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recently Added Column -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[22px] font-bold text-[#253D4E]">Baru Ditambahkan</h3>
                        <svg class="w-5 h-5 text-[#FDC040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="space-y-4">
                        @foreach ($categories->first()->products->sortByDesc('created_at')->take(3) as $product)
                            <div class="flex gap-4 bg-white rounded-xl p-4 hover:shadow-lg border border-gray-100 transition-all duration-300 group">
                                <a href="{{ route('products.show', $product) }}" class="flex-shrink-0">
                                    @if ($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                            class="w-24 h-24 object-contain rounded-xl bg-[#F8F8F8] p-2 group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-24 h-24 bg-[#F8F8F8] rounded-xl flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $product) }}">
                                        <h4 class="font-bold text-[#253D4E] text-[14px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug">
                                            {{ $product->name }}
                                        </h4>
                                    </a>
                                    <div class="flex items-center gap-0.5 mb-2">
                                        @php
                                            $avgRating = $product->average_rating ?? 0;
                                            $fullStars = floor($avgRating);
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $fullStars)
                                                <svg class="w-3.5 h-3.5 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[17px] font-extrabold text-[#3BB77E]">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Rated Column -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[22px] font-bold text-[#253D4E]">Rating Tertinggi</h3>
                        <svg class="w-5 h-5 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div class="space-y-4">
                        @foreach ($featuredProducts->sortByDesc('average_rating')->take(3) as $product)
                            <div class="flex gap-4 bg-white rounded-xl p-4 hover:shadow-lg border border-gray-100 transition-all duration-300 group">
                                <a href="{{ route('products.show', $product) }}" class="flex-shrink-0">
                                    @if ($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                            class="w-24 h-24 object-contain rounded-xl bg-[#F8F8F8] p-2 group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-24 h-24 bg-[#F8F8F8] rounded-xl flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $product) }}">
                                        <h4 class="font-bold text-[#253D4E] text-[14px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug">
                                            {{ $product->name }}
                                        </h4>
                                    </a>
                                    <div class="flex items-center gap-0.5 mb-2">
                                        @php
                                            $avgRating = $product->average_rating ?? 0;
                                            $fullStars = floor($avgRating);
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $fullStars)
                                                <svg class="w-3.5 h-3.5 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[17px] font-extrabold text-[#3BB77E]">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Viewed Products -->
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-[36px] font-bold text-[#253D4E]">Baru Dilihat</h2>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>

            <!-- Products List -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach ($featuredProducts->take(12) as $product)
                    <div class="flex flex-col group">
                        <a href="{{ route('products.show', $product) }}" class="w-full">
                            <div class="relative mb-4 overflow-hidden rounded-xl bg-[#F8F8F8]">
                                @if ($product->images && count($product->images) > 0)
                                    <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                        class="w-full h-36 object-contain p-3 group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-36 bg-[#F8F8F8] flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-[#253D4E] text-[13px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-snug min-h-[2.5rem]">
                                    {{ $product->name }}
                                </h4>
                                <div class="flex items-center gap-0.5 mb-2">
                                    @php
                                        $avgRating = $product->average_rating ?? 0;
                                        $fullStars = floor($avgRating);
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <svg class="w-3 h-3 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[15px] font-extrabold text-[#3BB77E]">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 line-through">
                                        Rp{{ number_format($product->price * 1.3, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        function countdownTimer() {
            return {
                timeLeft: '',
                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },
                updateCountdown() {
                    const now = new Date();
                    const tomorrow = new Date(now);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    tomorrow.setHours(0, 0, 0, 0);

                    const diff = tomorrow - now;
                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    this.timeLeft = `${hours}j ${minutes}m ${seconds}d`;
                }
            }
        }
    </script>
    @endpush
@endsection
