<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Amanah Shop')</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white" style="font-family: 'Poppins', sans-serif;">

    <!-- Flash Messages -->
    @include('components.flash-message')

    <!-- Main Header - Nest Style -->
    <header class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between gap-6">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <img src="/images/logo.svg" alt="Amanah Shop" class="w-12 h-12"
                            onerror="this.style.display='none'">
                        <div>
                            <span class="text-[32px] font-bold text-[#253D4E] leading-none">Amanah Shop</span>
                        </div>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="hidden lg:flex flex-1 max-w-2xl">
                    <form action="{{ route('products.index') }}" method="GET"
                        class="flex items-center w-full bg-white border-2 border-[#BCE3C9] rounded-full overflow-hidden">
                        <div class="flex items-center pl-4 pr-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Cari produk..."
                            class="flex-1 px-2 py-2 text-[14px] border-0 focus:outline-none focus:ring-0">
                        <button type="submit"
                            class="bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold px-8 py-3 rounded-full transition-colors text-[14px]">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Right Icons -->
                <div class="hidden lg:flex items-center gap-6">
                    <!-- Favorites -->
                    <a href="{{ auth()->check() ? route('user.favorites.index') : route('login') }}"
                        class="flex items-center gap-2 group relative">
                        <div class="relative">
                            <svg class="w-7 h-7 text-[#253D4E] group-hover:text-[#FF6B6B] transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            @auth
                                <span
                                    class="absolute -top-1 -right-2 bg-[#FF6B6B] text-white rounded-full w-5 h-5 flex items-center justify-center text-[11px] font-bold">{{ auth()->user()->favorites->count() }}</span>
                            @else
                                <span
                                    class="absolute -top-1 -right-2 bg-[#FF6B6B] text-white rounded-full w-5 h-5 flex items-center justify-center text-[11px] font-bold">0</span>
                            @endauth
                        </div>
                        <div class="text-left">
                            <p class="text-[11px] text-gray-500 group-hover:text-[#FF6B6B] transition-colors">Favorit
                            </p>
                        </div>
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('user.cart.index') }}" class="flex items-center gap-2 group relative">
                        <div class="relative">
                            <svg class="w-7 h-7 text-[#253D4E] group-hover:text-[#3BB77E] transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span
                                class="absolute -top-1 -right-2 bg-[#3BB77E] text-white rounded-full w-5 h-5 flex items-center justify-center text-[11px] font-bold">{{ auth()->check() ? auth()->user()->carts->sum('quantity') : 0 }}</span>
                        </div>
                        <div class="text-left">
                            <p class="text-[11px] text-gray-500 group-hover:text-[#3BB77E] transition-colors">Keranjang
                            </p>
                        </div>
                    </a>

                    <!-- Account -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 group">
                                <div class="relative">
                                    <svg class="w-7 h-7 text-[#253D4E]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-[11px] text-gray-500">Akun</p>
                                </div>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak
                                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[60]">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                        <span class="font-semibold">Dashboard Admin</span>
                                    </a>
                                @endif
                                <a href="{{ route('profile') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-semibold">Profil Saya</span>
                                </a>
                                <a href="{{ route('user.orders.index') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span class="font-semibold">Pesanan Saya</span>
                                </a>
                                <a href="{{ route('user.favorites.index') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    <span class="font-semibold">Produk Favorit</span>
                                </a>
                                <div class="border-t border-gray-100 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <span class="font-semibold">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full bg-primary-500 hover:bg-primary-600 text-white font-bold transition-all shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Masuk/Daftar</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2" x-data @click="$dispatch('toggle-mobile-menu')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Navigation Bar - Nest Style (Sticky) -->
    <nav class="border-t border-gray-100 bg-white sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8 py-3">
                <!-- Browse All Categories -->
                <div class="relative hidden lg:block" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-3 bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold px-6 py-3 rounded-md transition-colors text-[14px]">
                        <i class="fas fa-bars"></i>
                        <span>Jelajahi Semua Kategori</span>
                        <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition x-cloak
                        class="absolute left-0 top-full mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 py-3 z-50 max-h-96 overflow-y-auto">
                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 font-semibold">
                            <i class="fas fa-boxes text-[#3BB77E] w-5"></i>
                            <span>Semua Produk</span>
                        </a>
                        <div class="border-t border-gray-200 my-2"></div>
                        <a href="{{ route('products.index', ['category' => 'Perabotan']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-couch text-[#3BB77E] w-5"></i>
                            <span>Perabotan</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'Perlengkapan Kamar Tidur']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-bed text-[#3BB77E] w-5"></i>
                            <span>Perlengkapan Kamar Tidur</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'Pakaian']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-tshirt text-[#3BB77E] w-5"></i>
                            <span>Pakaian</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'Sepatu & Alas Kaki']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-shoe-prints text-[#3BB77E] w-5"></i>
                            <span>Sepatu & Alas Kaki</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'Keperluan Rumah Tangga']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-blender text-[#3BB77E] w-5"></i>
                            <span>Keperluan Rumah Tangga</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'Tekstil Rumah']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-rug text-[#3BB77E] w-5"></i>
                            <span>Tekstil Rumah</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'Aksesoris Rumah']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-lightbulb text-[#3BB77E] w-5"></i>
                            <span>Aksesoris Rumah</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'Lain-lain']) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                            <i class="fas fa-ellipsis-h text-[#3BB77E] w-5"></i>
                            <span>Lain-lain</span>
                        </a>
                    </div>
                </div>

                <!-- Main Navigation -->
                <div class="hidden lg:flex items-center gap-10 flex-1">
                    <a href="{{ route('home') }}"
                        class="text-[#253D4E] hover:text-[#3BB77E] font-bold text-[15px] transition-colors relative group">
                        Beranda
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="text-[#253D4E] hover:text-[#3BB77E] font-bold text-[15px] transition-colors relative group">
                        Produk
                    </a>
                    <a href="{{ route('about') }}"
                        class="text-[#253D4E] hover:text-[#3BB77E] font-bold text-[15px] transition-colors relative group">
                        Tentang Kami
                    </a>
                </div>

                <!-- Contact -->
                <div class="hidden lg:flex items-center gap-3">
                    <svg class="w-9 h-9 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C10.82 21 3 13.18 3 4V5z" />
                    </svg>
                    <div>
                        <p class="text-[20px] font-bold text-[#3BB77E]">+62 896-7436-6444</p>
                        <p class="text-[12px] text-gray-500">Hubungi Kami</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Panel -->
    <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-transition.origin.top
        x-cloak
        class="lg:hidden bg-white border-b border-gray-200 fixed top-0 left-0 right-0 bottom-0 z-50 overflow-y-auto">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <span class="text-xl font-bold text-secondary-800">Menu</span>
                <button @click="open = false" class="p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <a href="{{ route('home') }}"
                    class="block text-lg text-gray-700 hover:text-primary-500 py-2">Beranda</a>
                <a href="{{ route('products.index') }}"
                    class="block text-lg text-gray-700 hover:text-primary-500 py-2">Produk</a>
                <a href="{{ route('about') }}"
                    class="block text-lg text-gray-700 hover:text-primary-500 py-2">Tentang Kami</a>

                <div class="border-t border-gray-200 pt-4">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="block text-lg text-gray-700 hover:text-primary-500 py-2">Dashboard Admin</a>
                        @endif
                        <a href="{{ route('profile') }}"
                            class="block text-lg text-gray-700 hover:text-primary-500 py-2">Profil</a>
                        <a href="{{ route('user.orders.index') }}"
                            class="block text-lg text-gray-700 hover:text-primary-500 py-2">Pesanan</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left text-lg text-red-600 hover:text-red-700 py-2">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block text-lg text-gray-700 hover:text-primary-500 py-2">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="block text-lg text-gray-700 hover:text-primary-500 py-2">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4"
            role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Newsletter Section - Nest Style -->
    <section class="relative bg-gradient-to-r from-[#BCE3C9] to-[#7FB88D] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8 relative z-10">
                <div class="flex-1 text-center lg:text-left">
                    <h2 class="text-[36px] font-bold text-[#253D4E] mb-4 leading-tight">
                        Dapatkan Penawaran & Produk Terbaru
                    </h2>
                    <p class="text-[#7E7E7E] text-[16px] mb-6">Berlangganan newsletter untuk info produk terbaru, promo spesial, dan penawaran menarik dari Amanah Shop</p>
                    <form class="flex gap-0 bg-white rounded-full overflow-hidden shadow-lg max-w-lg mx-auto lg:mx-0">
                        <div class="flex items-center pl-6 pr-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <input type="email" placeholder="Alamat email Anda"
                            class="flex-1 py-4 border-0 focus:outline-none text-sm">
                        <button type="submit"
                            class="bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-semibold px-8 py-4 transition-colors text-sm">
                            Berlangganan
                        </button>
                    </form>
                </div>

                <div class="flex-1 hidden lg:block"></div>
            </div>
        </div>

        <!-- Newsletter Image - Full Height -->
        <div class="absolute right-0 top-0 bottom-0 w-1/2 hidden lg:flex items-end justify-end pointer-events-none">
            <img src="{{ asset('images/newsletter-banner.png') }}" alt="Amanah Shop"
                class="h-full w-auto object-contain object-bottom" onerror="this.style.display='none'">
        </div>
    </section>

    <!-- Features Section - Nest Style -->
    <section class="py-12 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
                <div class="flex flex-col items-center text-center group">
                    <div class="bg-[#F4F6FA] rounded-lg p-5 mb-4 group-hover:bg-[#DEF9EC] transition-colors">
                        <svg class="w-10 h-10 text-[#3BB77E]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#253D4E] mb-1 text-[14px]">Harga Terjangkau</h3>
                    <p class="text-[12px] text-gray-500">Sistem kredit mudah</p>
                </div>

                <div class="flex flex-col items-center text-center group">
                    <div class="bg-[#F4F6FA] rounded-lg p-5 mb-4 group-hover:bg-[#DEF9EC] transition-colors">
                        <svg class="w-10 h-10 text-[#3BB77E]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#253D4E] mb-1 text-[14px]">Gratis Ongkir</h3>
                    <p class="text-[12px] text-gray-500">Pembelian minimal</p>
                </div>

                <div class="flex flex-col items-center text-center group">
                    <div class="bg-[#F4F6FA] rounded-lg p-5 mb-4 group-hover:bg-[#DEF9EC] transition-colors">
                        <svg class="w-10 h-10 text-[#3BB77E]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#253D4E] mb-1 text-[14px]">Kualitas Terjamin</h3>
                    <p class="text-[12px] text-gray-500">Produk pilihan</p>
                </div>

                <div class="flex flex-col items-center text-center group">
                    <div class="bg-[#F4F6FA] rounded-lg p-5 mb-4 group-hover:bg-[#DEF9EC] transition-colors">
                        <svg class="w-10 h-10 text-[#3BB77E]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#253D4E] mb-1 text-[14px]">Produk Berkualitas</h3>
                    <p class="text-[12px] text-gray-500">Pilihan terlengkap</p>
                </div>

                <div class="flex flex-col items-center text-center group">
                    <div class="bg-[#F4F6FA] rounded-lg p-5 mb-4 group-hover:bg-[#DEF9EC] transition-colors">
                        <svg class="w-10 h-10 text-[#3BB77E]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#253D4E] mb-1 text-[14px]">Mudah Dikembalikan</h3>
                    <p class="text-[12px] text-gray-500">Dalam 30 hari</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer - Nest Style -->
    <footer class="mt-16 bg-white border-t border-gray-100">

        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8">
                <!-- Brand Column -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <img src="/images/logo.svg" alt="Amanah Shop" class="w-10 h-10"
                            onerror="this.style.display='none'">
                        <span class="text-[28px] font-bold text-[#253D4E]">Amanah Shop</span>
                    </div>
                    <p class="text-[14px] text-gray-600 mb-6 leading-relaxed">Toko kebutuhan rumah tangga & lifestyle dengan sistem pembayaran tunai dan kredit yang mudah dan terpercaya</p>
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#3BB77E]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-[14px] text-gray-700"><strong>Alamat:</strong> Seluruh Indonesia</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#3BB77E]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C10.82 21 3 13.18 3 4V5z" />
                            </svg>
                            <span class="text-[14px] text-gray-700"><strong>Telepon:</strong> +62 812-3456-7890</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#3BB77E]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[14px] text-gray-700"><strong>Email:</strong> admin@amanahshop.com</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi -->
                <div>
                    <h4 class="text-[18px] font-bold text-[#253D4E] mb-6">Informasi</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Tentang Kami</a>
                        </li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Produk</a></li>
                    </ul>
                </div>

                <!-- Akun -->
                <div>
                    <h4 class="text-[18px] font-bold text-[#253D4E] mb-6">Akun</h4>
                    <ul class="space-y-3">
                        @auth
                            <li><a href="{{ route('profile') }}"
                                    class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Profil Saya</a></li>
                            <li><a href="{{ route('user.cart.index') }}"
                                    class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Keranjang</a></li>
                            <li><a href="{{ route('user.orders.index') }}"
                                    class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Pesanan Saya</a></li>
                        @else
                            <li><a href="{{ route('login') }}"
                                    class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Masuk</a></li>
                            <li><a href="{{ route('register') }}"
                                    class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Daftar</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Kategori Produk -->
                <div>
                    <h4 class="text-[18px] font-bold text-[#253D4E] mb-6">Kategori</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('products.index') }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Semua Produk</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Perabotan']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Perabotan</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Perlengkapan Kamar Tidur']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Perlengkapan Kamar Tidur</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Pakaian']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Pakaian</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Sepatu & Alas Kaki']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Sepatu & Alas Kaki</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Keperluan Rumah Tangga']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Keperluan Rumah Tangga</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Tekstil Rumah']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Tekstil Rumah</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Aksesoris Rumah']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Aksesoris Rumah</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Lain-lain']) }}"
                                class="text-[14px] text-gray-600 hover:text-[#3BB77E] transition">Lain-lain</a></li>
                    </ul>
                </div>

                <!-- Media Sosial -->
                <div>
                    <h4 class="text-[18px] font-bold text-[#253D4E] mb-6">Ikuti Kami</h4>
                    <p class="text-[14px] text-gray-600 mb-4">Dapatkan info terbaru dari Amanah Shop</p>
                    <div class="flex gap-3">
                        <!-- Facebook -->
                        <a href="#"
                            class="w-12 h-12 flex-shrink-0 rounded-full bg-[#3BB77E] hover:bg-[#2a9d66] flex items-center justify-center text-white transition-colors"
                            title="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <!-- Twitter/X -->
                        <a href="#"
                            class="w-12 h-12 flex-shrink-0 rounded-full bg-[#3BB77E] hover:bg-[#2a9d66] flex items-center justify-center text-white transition-colors"
                            title="Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a href="#"
                            class="w-12 h-12 flex-shrink-0 rounded-full bg-[#3BB77E] hover:bg-[#2a9d66] flex items-center justify-center text-white transition-colors"
                            title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <!-- WhatsApp -->
                        <a href="#"
                            class="w-12 h-12 flex-shrink-0 rounded-full bg-[#3BB77E] hover:bg-[#2a9d66] flex items-center justify-center text-white transition-colors"
                            title="WhatsApp">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col md:flex-row justify-between items-center gap-3 text-[13px] text-gray-500">
                    <p>&copy; {{ date('Y') }}, <strong class="text-[#3BB77E]">Amanah Shop</strong> - Toko Kebutuhan Rumah Tangga & Lifestyle</p>
                    <div class="flex items-center gap-2">
                        <span>Hubungi Kami:</span>
                        <span class="text-[#253D4E] font-bold">+62 896-7436-6444</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @stack('scripts')
</body>

</html>
