@extends('layouts.app')

@section('title', 'Amanah Shop - Produk Lokal Berkualitas UMKM')

@section('content')
    <!-- Hero Section with Newsletter - Nest Style -->
    <section class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Large Banner with Newsletter - Light Blue Background -->
                <div class="lg:col-span-2 bg-[#BCE3C9] rounded-3xl p-12 relative overflow-hidden">
                    <!-- Background Image - Ukuran optimal: 1200x400px atau 1600x500px -->
                    <img src="{{ asset('images/hero-banner-main.jpg') }}" alt="Hero Banner"
                        class="absolute inset-0 w-full h-full object-cover opacity-full" onerror="this.style.display='none'">

                    <div class="relative z-10 max-w-lg">
                        <h1 class="text-[48px] font-bold text-[#253D4E] mb-4 leading-tight"
                            style="font-family: 'Quicksand', sans-serif;">
                            Produk Lokal UMKM<br>
                            <span class="text-[#253D4E]">Berkualitas Tinggi</span>
                        </h1>
                        <p class="text-[18px] text-gray-700 mb-6">Dukung ekonomi UMKM dengan belanja produk lokal berkualitas</p>
                        <form class="flex gap-0 bg-white rounded-full overflow-hidden shadow-lg max-w-md">
                            <div class="flex items-center pl-6 pr-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" placeholder="Alamat email Anda"
                                class="flex-1 py-4 border-0 focus:outline-none text-sm">
                            <button type="submit"
                                class="bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-semibold px-8 py-4 rounded-full transition-colors text-sm">
                                Berlangganan
                            </button>
                        </form>
                    </div>

                    <!-- Decorative Image (Right Side) - Ukuran optimal: 400x400px -->
                    {{-- <img src="{{ asset('images/hero-product-image.png') }}" alt="Products"
                        class="absolute right-0 bottom-0 w-64 h-64 object-contain" onerror="this.style.display='none'"> --}}
                </div>

                <!-- Small Banner - Orange/Peach Background -->
                <div class="bg-[#FFE6D2] rounded-3xl p-8 flex flex-col justify-center relative overflow-hidden">
                    <!-- Background Image - Ukuran optimal: 600x400px atau 800x500px -->
                    <img src="{{ asset('images/hero-banner-delivery.jpg') }}" alt="Delivery Banner"
                        class="absolute inset-0 w-full h-full object-cover opacity-full"
                        onerror="this.style.display='none'">

                    <div class="relative z-10">
                        <h3 class="text-[32px] font-bold text-[#253D4E] mb-2 leading-tight"
                            style="font-family: 'Quicksand', sans-serif;">Antar Ke<br>Rumah Anda</h3>
                        <p class="text-[14px] text-gray-700 mb-4">Pengiriman cepat & aman</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-block bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-semibold px-6 py-3 rounded-md transition-colors text-sm w-fit">
                            Belanja Sekarang →
                        </a>
                    </div>

                    <!-- Decorative Icon - Ukuran optimal: 200x200px -->
                    {{-- <img src="{{ asset('images/delivery-icon.png') }}" alt="Delivery"
                        class="absolute right-4 bottom-4 w-24 h-24 object-contain opacity-80"
                        onerror="this.style.display='none'"> --}}
                </div>
            </div>

            <!-- Three Promo Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#F2FCE4] rounded-2xl p-6 hover:shadow-lg transition-shadow relative overflow-hidden">
                    <!-- Card Image - Ukuran optimal: 400x300px -->
                    <img src="{{ asset('images/promo-pertanian.jpg') }}" alt="Hasil Pertanian"
                        class="absolute inset-0 w-full h-full object-cover opacity-full"
                        onerror="this.style.display='none'">
                    <div class="relative z-10">
                        <h3 class="text-[20px] font-bold text-[#253D4E] mb-1" style="font-family: 'Quicksand', sans-serif;">
                            Hasil Pertanian</h3>
                        <p class="text-[14px] text-gray-600 mb-4">Segar dari sawah</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-block bg-[#3BB77E] text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-[#2a9d66] transition-colors">
                            Belanja →
                        </a>
                    </div>
                </div>
                <div class="bg-[#FFFCEB] rounded-2xl p-6 hover:shadow-lg transition-shadow relative overflow-hidden">
                    <!-- Card Image - Ukuran optimal: 400x300px -->
                    <img src="{{ asset('images/promo-kerajinan.jpg') }}" alt="Kerajinan Tangan"
                        class="absolute inset-0 w-full h-full object-cover opacity-full"
                        onerror="this.style.display='none'">
                    <div class="relative z-10">
                        <h3 class="text-[20px] font-bold text-[#253D4E] mb-1" style="font-family: 'Quicksand', sans-serif;">
                            Kerajinan Tangan</h3>
                        <p class="text-[14px] text-gray-600 mb-4">Karya asli UMKM</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-block bg-[#3BB77E] text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-[#2a9d66] transition-colors">
                            Belanja →
                        </a>
                    </div>
                </div>
                <div class="bg-[#ECFFEC] rounded-2xl p-6 hover:shadow-lg transition-shadow relative overflow-hidden">
                    <!-- Card Image - Ukuran optimal: 400x300px -->
                    <img src="{{ asset('images/promo-makanan.jpg') }}" alt="Makanan Olahan"
                        class="absolute inset-0 w-full h-full object-cover opacity-full"
                        onerror="this.style.display='none'">
                    <div class="relative z-10">
                        <h3 class="text-[20px] font-bold text-[#253D4E] mb-1" style="font-family: 'Quicksand', sans-serif;">
                            Makanan Olahan</h3>
                        <p class="text-[14px] text-gray-600 mb-4">Rasa tradisional</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-block bg-[#3BB77E] text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-[#2a9d66] transition-colors">
                            Belanja →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Categories with Tabs - Nest Style -->
    @if ($categories->count() > 0)
        <section class="py-12 bg-white border-t border-gray-100" x-data="{ activeCategory: 'all' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header with Tabs -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">Kategori
                        Produk</h2>
                    <div class="flex items-center gap-8 text-sm">
                        <button @click="activeCategory = 'all'"
                            :class="activeCategory === 'all' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' :
                                'text-gray-600 hover:text-[#3BB77E] font-medium'"
                            class="pb-2 whitespace-nowrap transition-all duration-300 transform hover:scale-105">Semua</button>
                        @foreach ($categories->take(4) as $cat)
                            <button @click="activeCategory = '{{ $cat->id }}'"
                                :class="activeCategory === '{{ $cat->id }}' ?
                                    'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' :
                                    'text-gray-600 hover:text-[#3BB77E] font-medium'"
                                class="pb-2 whitespace-nowrap transition-all duration-300 transform hover:scale-105">{{ $cat->name }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- Categories Grid with Products - Limited to 2 rows -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 mb-8">
                    @php
                        // Prepare products for all categories
                        $categoryProductsMap = [];
                        $allProductsForDisplay = collect();

                        foreach ($categories as $category) {
                            // Get products for each category (10 products per category for 2 rows)
                            $categoryProducts = $category
                                ->products()
                                ->where('status', 'active')
                                ->where('stock', '>', 0)
                                ->limit(10)
                                ->get();

                            foreach ($categoryProducts as $product) {
                                $product->category_id_for_filter = $category->id;
                                $product->category_name_for_display = $category->name;
                            }

                            $categoryProductsMap[$category->id] = $categoryProducts;

                            // For "all" tab, take 2 products from each category
                            $allProductsForDisplay = $allProductsForDisplay->merge($categoryProducts->take(2));
                        }

                        // Limit "all" tab to 10 products (2 rows x 5 columns)
                        $allProductsForDisplay = $allProductsForDisplay->take(10);
                    @endphp

                    {{-- Display products for "all" tab --}}
                    @foreach ($allProductsForDisplay as $product)
                        <div x-show="activeCategory === 'all'" x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="bg-white rounded-2xl p-5 hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300 group border border-gray-200 hover:-translate-y-1 flex flex-col">
                            <!-- Discount Badge & Wishlist -->
                            <div class="flex justify-between items-start mb-3">
                                @if ($loop->index < 3)
                                    <span
                                        class="bg-pink-500 text-white text-[11px] font-bold px-2 py-1 rounded-sm animate-pulse">{{ ['Populer', 'Diskon', 'Terlaris'][$loop->index] }}</span>
                                @else
                                    <span></span>
                                @endif

                                <!-- Favorite Button -->
                                @auth
                                    @php
                                        $isFavorited = auth()
                                            ->user()
                                            ->favorites()
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    @endphp
                                    <form action="{{ route('user.favorites.toggle', $product) }}" method="POST"
                                        class="inline" onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit" class="transition-all duration-300 transform hover:scale-110">
                                            @if ($isFavorited)
                                                <svg class="w-5 h-5 text-[#FF6B6B]" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-400 hover:text-[#FF6B6B]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" onclick="event.stopPropagation()"
                                        class="text-gray-400 hover:text-[#FF6B6B] transition-all duration-300 transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </a>
                                @endauth
                            </div>

                            <!-- Clickable Card Content -->
                            <a href="{{ route('products.show', $product) }}" class="flex flex-col flex-1 cursor-pointer">

                            <!-- Product Image -->
                            <div class="relative mb-3 overflow-hidden rounded-lg">
                                @if ($product->images && count($product->images) > 0)
                                    <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                        class="w-full h-32 object-contain group-hover:scale-110 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-32 bg-gray-50 rounded flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 group-hover:scale-110 transition-transform duration-300"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Category Name -->
                            <div
                                class="text-[12px] text-gray-500 mb-1 transition-colors duration-300 group-hover:text-[#3BB77E]">
                                {{ $product->category_name_for_display }}</div>
                            <h3
                                class="font-bold text-[#253D4E] text-[14px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-all duration-300 leading-tight">
                                {{ $product->name }}
                            </h3>

                            <!-- Rating -->
                            <div class="flex items-center gap-1 mb-2">
                                @php
                                    $avgRating = $product->average_rating ?? 0;
                                    $reviewsCount = $product->reviews_count ?? 0;
                                    $fullStars = floor($avgRating);
                                    $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $fullStars)
                                        <svg class="w-3 h-3 text-[#FDC040]" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @elseif($i == $fullStars + 1 && $hasHalfStar)
                                        <svg class="w-3 h-3" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="half-cat-{{ $product->id }}-{{ $i }}">
                                                    <stop offset="50%" stop-color="#FDC040" />
                                                    <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1" />
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#half-cat-{{ $product->id }}-{{ $i }})"
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="text-[12px] text-gray-500 ml-1">({{ number_format($avgRating, 1) }})</span>
                            </div>

                            <!-- Price & Vendor -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[18px] font-bold text-[#3BB77E]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span
                                        class="text-[14px] text-gray-400 line-through">Rp{{ number_format($product->price * 1.4, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Vendor removed - Amanah Shop is single shop -->

                            <!-- Progress Bar -->
                            @php
                                // Calculate actual sold quantity from orders
                                $sold = DB::table('order_items')
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->where('order_items.product_id', $product->id)
                                    ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                                    ->where('orders.payment_status', 'paid')
                                    ->sum('order_items.quantity');

                                // Stock from database + sold items = initial stock (approximate total produced)
                                $currentStock = $product->stock;
                                $total = $currentStock + $sold;

                                // Calculate percentage - prevent division by zero
                                $percentage = $total > 0 ? ($sold / $total) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-[#3BB77E] h-1.5 rounded-full transition-all duration-500 ease-out"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] mt-1">
                                    <span class="text-gray-500">Terjual: {{ $sold }} | Stok:
                                        {{ $currentStock }}</span>
                                </div>
                            </div>
                            </a>

                            <!-- Add to Cart Button -->
                            @auth
                                <form action="{{ route('user.cart.add') }}" method="POST" class="mt-auto" onclick="event.stopPropagation()">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-semibold py-2 px-3 rounded-md transition-all duration-300 text-[13px] transform hover:scale-105 hover:shadow-md">
                                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <span>Tambah</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" onclick="event.stopPropagation()"
                                    class="mt-auto flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-semibold py-2 px-3 rounded-md transition-all duration-300 text-[13px] transform hover:scale-105 hover:shadow-md">
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Tambah</span>
                                </a>
                            @endauth
                        </div>
                    @endforeach

                    {{-- Display products for each category tab --}}
                    @foreach ($categoryProductsMap as $catId => $products)
                        @foreach ($products as $product)
                            <div x-show="activeCategory === '{{ $catId }}'"
                                x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="bg-white rounded-2xl p-5 hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300 group border border-gray-200 hover:-translate-y-1 flex flex-col">
                                <!-- Discount Badge & Wishlist -->
                                <div class="flex justify-between items-start mb-3">
                                    @if ($loop->parent->index == 0 && $loop->index < 3)
                                        <span
                                            class="bg-pink-500 text-white text-[11px] font-bold px-2 py-1 rounded-sm animate-pulse">{{ ['Populer', 'Diskon', 'Terlaris'][$loop->index] }}</span>
                                    @else
                                        <span></span>
                                    @endif

                                    <!-- Favorite Button -->
                                    @auth
                                        @php
                                            $isFavorited = auth()
                                                ->user()
                                                ->favorites()
                                                ->where('product_id', $product->id)
                                                ->exists();
                                        @endphp
                                        <form action="{{ route('user.favorites.toggle', $product) }}" method="POST"
                                            class="inline" onclick="event.stopPropagation()">
                                            @csrf
                                            <button type="submit"
                                                class="transition-all duration-300 transform hover:scale-110">
                                                @if ($isFavorited)
                                                    <svg class="w-5 h-5 text-[#FF6B6B]" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400 hover:text-[#FF6B6B]" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" onclick="event.stopPropagation()"
                                            class="text-gray-400 hover:text-[#FF6B6B] transition-all duration-300 transform hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endauth
                                </div>

                                <!-- Clickable Card Content -->
                                <a href="{{ route('products.show', $product) }}" class="flex flex-col flex-1 cursor-pointer">

                                <!-- Product Image -->
                                <div class="relative mb-3 overflow-hidden rounded-lg">
                                    @if ($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                            class="w-full h-32 object-contain group-hover:scale-110 transition-transform duration-500 ease-out">
                                    @else
                                        <div class="w-full h-32 bg-gray-50 rounded flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300 group-hover:scale-110 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Category Name -->
                                <div
                                    class="text-[12px] text-gray-500 mb-1 transition-colors duration-300 group-hover:text-[#3BB77E]">
                                    {{ $product->category_name_for_display }}</div>
                                <h3
                                    class="font-bold text-[#253D4E] text-[14px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-all duration-300 leading-tight">
                                    {{ $product->name }}
                                </h3>

                                <!-- Rating -->
                                <div class="flex items-center gap-1 mb-2">
                                    @php
                                        $avgRating = $product->average_rating ?? 0;
                                        $reviewsCount = $product->reviews_count ?? 0;
                                        $fullStars = floor($avgRating);
                                        $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <svg class="w-3 h-3 text-[#FDC040]" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            <svg class="w-3 h-3" viewBox="0 0 20 20">
                                                <defs>
                                                    <linearGradient
                                                        id="half-cat2-{{ $product->id }}-{{ $i }}">
                                                        <stop offset="50%" stop-color="#FDC040" />
                                                        <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1" />
                                                    </linearGradient>
                                                </defs>
                                                <path fill="url(#half-cat2-{{ $product->id }}-{{ $i }})"
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span
                                        class="text-[12px] text-gray-500 ml-1">({{ number_format($avgRating, 1) }})</span>
                                </div>

                                <!-- Price & Vendor -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-[18px] font-bold text-[#3BB77E]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span
                                            class="text-[14px] text-gray-400 line-through">Rp{{ number_format($product->price * 1.4, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Vendor removed - Amanah Shop is single shop -->

                                <!-- Progress Bar -->
                                @php
                                    // Calculate actual sold quantity from orders
                                    $sold = DB::table('order_items')
                                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                        ->where('order_items.product_id', $product->id)
                                        ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                                        ->where('orders.payment_status', 'paid')
                                        ->sum('order_items.quantity');

                                    // Stock from database + sold items = initial stock (approximate total produced)
                                    $currentStock = $product->stock;
                                    $total = $currentStock + $sold;

                                    // Calculate percentage - prevent division by zero
                                    $percentage = $total > 0 ? ($sold / $total) * 100 : 0;
                                @endphp
                                <div class="mb-3">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-[#3BB77E] h-1.5 rounded-full transition-all duration-500 ease-out"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] mt-1">
                                        <span class="text-gray-500">Terjual: {{ $sold }} | Stok:
                                            {{ $currentStock }}</span>
                                    </div>
                                </div>
                                </a>

                                <!-- Add to Cart Button -->
                                @auth
                                    <form action="{{ route('user.cart.add') }}" method="POST" class="mt-auto" onclick="event.stopPropagation()">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-semibold py-2 px-3 rounded-md transition-all duration-300 text-[13px] transform hover:scale-105 hover:shadow-md">
                                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            <span>Tambah</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" onclick="event.stopPropagation()"
                                        class="mt-auto flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-semibold py-2 px-3 rounded-md transition-all duration-300 text-[13px] transform hover:scale-105 hover:shadow-md">
                                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <span>Tambah</span>
                                    </a>
                                @endauth
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- View All Button -->
                <div class="text-center mt-8">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold px-8 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg text-sm group">
                        <span>Lihat Semua Produk</span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @else
        <!-- Empty State for Categories -->
        <section class="py-12 bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">
                        Kategori Produk</h2>
                </div>

                <!-- Empty Message -->
                <div class="text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-[20px] font-bold text-gray-400 mb-2">Belum Ada Kategori Produk</h3>
                    <p class="text-gray-400 text-[14px]">Kategori produk akan muncul di sini</p>
                </div>
            </div>
        </section>
    @endif

    <!-- Daily Best Sells - Nest Style -->
    @if ($featuredProducts->count() > 0)
        <section class="py-12 bg-gray-50" x-data="{ activeBestSeller: 'all' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">
                        Terlaris Hari Ini</h2>
                    <div class="flex items-center gap-6 text-sm flex-wrap">
                        <button @click="activeBestSeller = 'all'"
                            :class="activeBestSeller === 'all' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' :
                                'text-gray-600 hover:text-[#3BB77E] font-medium'"
                            class="pb-2 whitespace-nowrap transition-all duration-300">Semua</button>
                        <button @click="activeBestSeller = 'barang'"
                            :class="activeBestSeller === 'barang' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' :
                                'text-gray-600 hover:text-[#3BB77E] font-medium'"
                            class="pb-2 whitespace-nowrap transition-all duration-300">Produk Barang</button>
                        <button @click="activeBestSeller = 'jasa'"
                            :class="activeBestSeller === 'jasa' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' :
                                'text-gray-600 hover:text-[#3BB77E] font-medium'"
                            class="pb-2 whitespace-nowrap transition-all duration-300">Produk Jasa</button>
                        <button @click="activeBestSeller = 'pertanian'"
                            :class="activeBestSeller === 'pertanian' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' :
                                'text-gray-600 hover:text-[#3BB77E] font-medium'"
                            class="pb-2 whitespace-nowrap transition-all duration-300">Hasil Pertanian</button>
                        <button @click="activeBestSeller = 'kerajinan'"
                            :class="activeBestSeller === 'kerajinan' ? 'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E]' :
                                'text-gray-600 hover:text-[#3BB77E] font-medium'"
                            class="pb-2 whitespace-nowrap transition-all duration-300">Kerajinan</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 min-h-[420px]">
                    <!-- Side Banner -->
                    <div
                        class="rounded-2xl p-10 text-white flex flex-col justify-start items-start relative overflow-hidden">
                        <!-- Background Image - Ukuran optimal: 400x500px -->
                        <img src="{{ asset('images/side-banner-bestseller.jpg') }}" alt="Best Seller Banner"
                            class="absolute inset-0 w-full h-full object-cover"
                            onerror="this.style.display='none'; this.parentElement.classList.add('bg-gradient-to-br', 'from-[#7FB88D]', 'to-[#5A9C6E]')">

                        <!-- Overlay untuk keterbacaan teks -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#7FB88D]/50 to-[#5A9C6E]/50"></div>

                        <div class="relative z-10">
                            <h3 class="text-[32px] font-bold mb-6 leading-tight text-[#253D4E]"
                                style="font-family: 'Quicksand', sans-serif;">Bawa produk UMKM ke rumah </br> anda</h3>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center gap-2 bg-[#3BB77E] text-white font-semibold px-3 py-2 rounded-md hover:bg-[#2F9A62] transition-colors text-[12px]">
                                Belanja Sekarang
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Products -->
                    @php
                        $displayedProducts = $featuredProducts->take(3);
                    @endphp

                    @foreach ($displayedProducts as $product)
                        <div x-show="activeBestSeller === 'all' || activeBestSeller === '{{ $product->type }}' || (activeBestSeller === 'pertanian' && '{{ strtolower($product->category->name ?? '') }}'.includes('pertanian')) || (activeBestSeller === 'kerajinan' && '{{ strtolower($product->category->name ?? '') }}'.includes('kerajinan'))"
                            x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="bg-white rounded-2xl p-5 hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300 group cursor-pointer border border-gray-200 hover:-translate-y-1 flex flex-col">
                            <!-- Discount Badge & Wishlist -->
                            <div class="flex justify-between items-start mb-3">
                                @if ($loop->index == 0)
                                    <span
                                        class="bg-green-500 text-white text-[11px] font-bold px-2 py-1 rounded-sm animate-pulse">Diskon</span>
                                @elseif($loop->index == 1)
                                    <span
                                        class="bg-blue-500 text-white text-[11px] font-bold px-2 py-1 rounded-sm animate-pulse">Terlaris</span>
                                @else
                                    <span
                                        class="bg-orange-500 text-white text-[11px] font-bold px-2 py-1 rounded-sm animate-pulse">Populer</span>
                                @endif

                                <!-- Favorite Button -->
                                @auth
                                    @php
                                        $isFavorited = auth()
                                            ->user()
                                            ->favorites()
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    @endphp
                                    <form action="{{ route('user.favorites.toggle', $product) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="transition-all duration-300 transform hover:scale-110">
                                            @if ($isFavorited)
                                                <svg class="w-5 h-5 text-[#FF6B6B]" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-400 hover:text-[#FF6B6B]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="text-gray-400 hover:text-[#FF6B6B] transition-all duration-300 transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </a>
                                @endauth
                            </div>

                            <!-- Product Image -->
                            <div class="relative mb-3 overflow-hidden rounded-lg">
                                @if ($product->images && count($product->images) > 0)
                                    <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                        class="w-full h-32 object-contain group-hover:scale-110 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-32 bg-gray-50 rounded flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Category -->
                            @if ($product->category)
                                <div
                                    class="text-[12px] text-gray-500 mb-1 transition-colors duration-300 group-hover:text-[#3BB77E]">
                                    {{ $product->category->name }}</div>
                            @endif

                            <!-- Product Name -->
                            <h3
                                class="font-bold text-[#253D4E] text-[14px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-all duration-300 leading-tight">
                                {{ $product->name }}
                            </h3>

                            <!-- Rating -->
                            <div class="flex items-center gap-1 mb-2">
                                @php
                                    $avgRating = $product->average_rating ?? 0;
                                    $reviewsCount = $product->reviews_count ?? 0;
                                    $fullStars = floor($avgRating);
                                    $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $fullStars)
                                        <svg class="w-3 h-3 text-[#FDC040]" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @elseif($i == $fullStars + 1 && $hasHalfStar)
                                        <svg class="w-3 h-3" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="half-best-{{ $product->id }}-{{ $i }}">
                                                    <stop offset="50%" stop-color="#FDC040" />
                                                    <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1" />
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#half-best-{{ $product->id }}-{{ $i }})"
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="text-[12px] text-gray-500 ml-1">({{ number_format($avgRating, 1) }})</span>
                            </div>

                            <!-- Price & Vendor -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[18px] font-bold text-[#3BB77E]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span
                                        class="text-[14px] text-gray-400 line-through">Rp{{ number_format($product->price * 1.3, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Vendor removed - Amanah Shop is single shop -->

                            <!-- Progress Bar -->
                            @php
                                // Calculate actual sold quantity from orders
                                $sold = DB::table('order_items')
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->where('order_items.product_id', $product->id)
                                    ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                                    ->where('orders.payment_status', 'paid')
                                    ->sum('order_items.quantity');

                                // Stock from database + sold items = initial stock (approximate total produced)
                                $currentStock = $product->stock;
                                $total = $currentStock + $sold;

                                // Calculate percentage - prevent division by zero
                                $percentage = $total > 0 ? ($sold / $total) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-[#3BB77E] h-1.5 rounded-full transition-all duration-500 ease-out"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] mt-1">
                                    <span class="text-gray-500">Terjual: {{ $sold }} | Stok:
                                        {{ $currentStock }}</span>
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            <a href="{{ route('products.show', $product) }}"
                                class="mt-auto flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-semibold py-2 px-3 rounded-md transition-all duration-300 text-[13px] transform hover:scale-105 hover:shadow-md">
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span>Tambah</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <!-- Empty State for Terlaris Hari Ini -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">
                        Terlaris Hari Ini</h2>
                </div>

                <!-- Empty Message -->
                <div class="text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                    <h3 class="text-[20px] font-bold text-gray-400 mb-2">Belum Ada Produk Terlaris</h3>
                    <p class="text-gray-400 text-[14px]">Produk terlaris akan muncul di sini</p>
                </div>
            </div>
        </section>
    @endif

    <!-- Deals of The Day - Nest Style -->
    @if ($featuredProducts->count() > 0)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">
                        Penawaran Hari Ini</h2>
                    <a href="{{ route('products.index') }}"
                        class="text-[#3BB77E] font-bold flex items-center gap-2 text-[14px] hover:gap-3 transition-all whitespace-nowrap">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredProducts->take(4) as $product)
                        <div
                            class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300 group hover:-translate-y-1">
                            <!-- Product Image Container -->
                            <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden">
                                @if ($product->images && count($product->images) > 0)
                                    <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                        class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-56 bg-gray-100 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300 group-hover:scale-110 transition-transform duration-300"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Badge for Stock Status -->
                                <div class="absolute top-4 left-4">
                                    @if ($product->stock > 50)
                                        <span
                                            class="bg-green-500 text-white text-[12px] font-bold px-3 py-1 rounded shadow-lg">Stok
                                            Banyak</span>
                                    @elseif ($product->stock > 10)
                                        <span
                                            class="bg-blue-500 text-white text-[12px] font-bold px-3 py-1 rounded shadow-lg">Tersedia</span>
                                    @else
                                        <span
                                            class="bg-orange-500 text-white text-[12px] font-bold px-3 py-1 rounded animate-pulse shadow-lg">Stok
                                            Terbatas</span>
                                    @endif
                                </div>

                                <!-- Favorite Button -->
                                <div
                                    class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    @auth
                                        @php
                                            $isFavorited = auth()
                                                ->user()
                                                ->favorites()
                                                ->where('product_id', $product->id)
                                                ->exists();
                                        @endphp
                                        <form action="{{ route('user.favorites.toggle', $product) }}" method="POST"
                                            class="inline"
                                            onclick="event.preventDefault(); event.stopPropagation(); this.submit();">
                                            @csrf
                                            <button type="submit"
                                                class="bg-white hover:bg-[#FF6B6B] text-gray-600 hover:text-white rounded-full p-2 shadow-lg transition-all duration-300 transform hover:scale-110 {{ $isFavorited ? 'bg-[#FF6B6B] text-white' : '' }}">
                                                <svg class="w-5 h-5" fill="{{ $isFavorited ? 'currentColor' : 'none' }}"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" onclick="event.stopPropagation();"
                                            class="bg-white hover:bg-[#FF6B6B] text-gray-600 hover:text-white rounded-full p-2 shadow-lg transition-all duration-300 transform hover:scale-110 inline-block">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endauth
                                </div>
                            </a>

                            <!-- Product Details -->
                            <div class="p-5">
                                <a href="{{ route('products.show', $product) }}" class="block">
                                    <h3
                                        class="font-bold text-[#253D4E] text-[15px] mb-2 group-hover:text-[#3BB77E] transition-colors duration-300 line-clamp-2 leading-snug min-h-[42px]">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                {{-- Vendor removed - Amanah Shop is single shop --}}

                                <!-- Rating -->
                                <div class="flex items-center gap-1 mb-3">
                                    @php
                                        $avgRating = $product->average_rating ?? 0;
                                        $reviewsCount = $product->reviews_count ?? 0;
                                        $fullStars = floor($avgRating);
                                        $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <svg class="w-3 h-3 text-[#FDC040]" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            <svg class="w-3 h-3" viewBox="0 0 20 20">
                                                <defs>
                                                    <linearGradient
                                                        id="half-deal-{{ $product->id }}-{{ $i }}">
                                                        <stop offset="50%" stop-color="#FDC040" />
                                                        <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1" />
                                                    </linearGradient>
                                                </defs>
                                                <path fill="url(#half-deal-{{ $product->id }}-{{ $i }})"
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span
                                        class="text-[12px] text-gray-500 ml-1">({{ number_format($avgRating, 1) }})</span>
                                </div>

                                <!-- Price -->
                                <div class="flex items-center gap-2 mb-4">
                                    <span
                                        class="text-[18px] font-bold text-[#3BB77E]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span
                                        class="text-[13px] text-gray-400 line-through">Rp{{ number_format($product->price * 1.4, 0, ',', '.') }}</span>
                                </div>

                                <!-- Add to Cart Button -->
                                <a href="{{ route('products.show', $product) }}"
                                    class="mt-auto flex items-center justify-center gap-2 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 text-[13px] transform hover:scale-105 hover:shadow-md w-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Tambah Keranjang</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <!-- Empty State for Penawaran Hari Ini -->
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">
                        Penawaran Hari Ini</h2>
                </div>

                <!-- Empty Message -->
                <div class="text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                        </path>
                    </svg>
                    <h3 class="text-[20px] font-bold text-gray-400 mb-2">Belum Ada Penawaran</h3>
                    <p class="text-gray-400 text-[14px]">Penawaran spesial akan muncul di sini</p>
                </div>
            </div>
        </section>
    @endif

    <!-- Product Tabs Section - Nest Style -->
    @if ($featuredProducts->count() > 0)
        <section class="py-12 bg-gray-50" x-data="{ activeProductTab: 'all' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Tabs -->
                <div class="flex items-center justify-center gap-10 mb-10 border-b-2 border-gray-200 pb-0">
                    <button @click="activeProductTab = 'all'"
                        :class="activeProductTab === 'all' ?
                            'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E] -mb-0.5' :
                            'text-gray-600 hover:text-[#3BB77E] font-medium'"
                        class="pb-4 whitespace-nowrap text-[16px] transition-all duration-300">Semua Produk</button>
                    <button @click="activeProductTab = 'barang'"
                        :class="activeProductTab === 'barang' ?
                            'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E] -mb-0.5' :
                            'text-gray-600 hover:text-[#3BB77E] font-medium'"
                        class="pb-4 whitespace-nowrap text-[16px] transition-all duration-300">Produk Barang</button>
                    <button @click="activeProductTab = 'jasa'"
                        :class="activeProductTab === 'jasa' ?
                            'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E] -mb-0.5' :
                            'text-gray-600 hover:text-[#3BB77E] font-medium'"
                        class="pb-4 whitespace-nowrap text-[16px] transition-all duration-300">Produk Jasa</button>
                    <button @click="activeProductTab = 'terbaru'"
                        :class="activeProductTab === 'terbaru' ?
                            'text-[#3BB77E] font-bold border-b-2 border-[#3BB77E] -mb-0.5' :
                            'text-gray-600 hover:text-[#3BB77E] font-medium'"
                        class="pb-4 whitespace-nowrap text-[16px] transition-all duration-300">Terbaru</button>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($featuredProducts as $product)
                        <a href="{{ route('products.show', $product) }}"
                            x-show="activeProductTab === 'all' || activeProductTab === '{{ $product->type }}' || activeProductTab === 'terbaru'"
                            x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="flex items-center gap-4 bg-white p-4 rounded-xl border border-gray-200 hover:border-[#3BB77E] hover:shadow-lg transition-all group cursor-pointer hover:-translate-y-1">
                            <!-- Product Image Small -->
                            @if ($product->images && count($product->images) > 0)
                                <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                    class="w-20 h-20 object-cover rounded-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div
                                    class="w-20 h-20 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-[14px] font-bold text-[#253D4E] mb-1 line-clamp-2 group-hover:text-[#3BB77E] transition-colors leading-tight">
                                    {{ $product->name }}</h3>
                                <div class="flex items-center gap-1 mb-2">
                                    @php
                                        $avgRating = $product->average_rating ?? 0;
                                        $reviewsCount = $product->reviews_count ?? 0;
                                        $fullStars = floor($avgRating);
                                        $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <svg class="w-3 h-3 text-[#FDC040]" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            <svg class="w-3 h-3" viewBox="0 0 20 20">
                                                <defs>
                                                    <linearGradient
                                                        id="half-tab-{{ $product->id }}-{{ $i }}">
                                                        <stop offset="50%" stop-color="#FDC040" />
                                                        <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1" />
                                                    </linearGradient>
                                                </defs>
                                                <path fill="url(#half-tab-{{ $product->id }}-{{ $i }})"
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span
                                        class="text-[11px] text-gray-500 ml-1">({{ number_format($avgRating, 1) }})</span>
                                </div>
                                <div class="text-[16px] font-bold text-[#3BB77E]">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                        </a>
                    @endforeach

                </div>

                <!-- View All Button -->
                <div class="text-center mt-8">
                    <a href="{{ route('products.index') }}"
                        class="text-[#3BB77E] font-bold inline-flex items-center gap-2 text-[14px] hover:gap-3 transition-all">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Shop by Categories - Nest Style -->
    @if ($categories->count() > 0)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">Belanja
                        Berdasarkan Kategori</h2>
                    <a href="{{ route('products.index') }}"
                        class="text-[#3BB77E] font-bold flex items-center gap-2 text-[14px] hover:gap-3 transition-all whitespace-nowrap">
                        Semua Kategori
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                    @foreach ($categories->take(8) as $category)
                        <a href="{{ route('products.category', $category) }}" class="text-center group">
                            <div
                                class="bg-[#F4F6FA] rounded-2xl p-6 mb-3 group-hover:bg-[#DEF9EC] group-hover:shadow-lg transition-all duration-300 group-hover:-translate-y-1">
                                @if ($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                        class="w-16 h-16 mx-auto object-contain group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <svg class="w-16 h-16 mx-auto text-[#3BB77E] group-hover:scale-110 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                @endif
                            </div>
                            <h3
                                class="font-bold text-[#253D4E] text-[14px] group-hover:text-[#3BB77E] transition-colors duration-300 mb-1">
                                {{ $category->name }}</h3>
                            <p class="text-[12px] text-gray-500 group-hover:text-[#3BB77E] transition-colors duration-300">
                                {{ $category->products_count ?? $category->products->count() }} produk</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <!-- Empty State for Shop by Categories -->
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8 gap-4">
                    <h2 class="text-[32px] font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">Belanja
                        Berdasarkan Kategori</h2>
                </div>

                <!-- Empty Message -->
                <div class="text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <h3 class="text-[20px] font-bold text-gray-400 mb-2">Belum Ada Kategori</h3>
                    <p class="text-gray-400 text-[14px]">Kategori akan muncul di sini</p>
                </div>
            </div>
        </section>
    @endif
@endsection
