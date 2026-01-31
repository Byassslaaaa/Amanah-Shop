<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Amanah Shop')</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50" style="font-family: 'Poppins', sans-serif;">
    <div class="flex h-screen overflow-hidden" x-data="{
        sidebarOpen: false,
        sidebarCollapsed: false,
        openMenus: {
            finance: false,
            inventory: false,
            credits: false
        }
    }">
        <!-- Sidebar -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-72 bg-white border-r border-gray-200" :class="{ 'w-20': sidebarCollapsed }">
                <!-- Logo/Brand -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3" x-show="!sidebarCollapsed" x-cloak>
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Amanah Shop</span>
                    </div>
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-1.5 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Menu Label -->
                <div class="px-6 pt-6 pb-2" x-show="!sidebarCollapsed" x-cloak>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu</p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}" :class="{ 'justify-center': sidebarCollapsed }">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-cloak>Dashboard</span>
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-cloak>Orders</span>
                    </a>

                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-cloak>Products</span>
                    </a>

                    @if(auth()->user()->isSuperAdmin())
                        <!-- Keuangan (Finance) -->
                        <div x-data="{ open: openMenus.finance }" x-init="$watch('openMenus.finance', value => open = value)">
                            <button @click="openMenus.finance = !openMenus.finance; open = openMenus.finance" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.finance.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-cloak>Keuangan</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open && !sidebarCollapsed" x-cloak class="ml-8 mt-1 space-y-1">
                                <a href="{{ route('admin.finance.transactions.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.finance.transactions.*') ? 'text-blue-600 bg-blue-50' : '' }}">Transaksi Keuangan</a>
                                <a href="{{ route('admin.finance.categories.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.finance.categories.*') ? 'text-blue-600 bg-blue-50' : '' }}">Kategori Transaksi</a>
                                <a href="{{ route('admin.finance.report') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.finance.report') ? 'text-blue-600 bg-blue-50' : '' }}">Laporan Keuangan</a>
                            </div>
                        </div>

                        <!-- Inventori (Inventory) -->
                        <div x-data="{ open: openMenus.inventory }" x-init="$watch('openMenus.inventory', value => open = value)">
                            <button @click="openMenus.inventory = !openMenus.inventory; open = openMenus.inventory" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.inventory.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-cloak>Inventori</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open && !sidebarCollapsed" x-cloak class="ml-8 mt-1 space-y-1">
                                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'text-blue-600 bg-blue-50' : '' }}">Kategori Produk</a>
                                <a href="{{ route('admin.inventory.movements.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.inventory.movements.*') ? 'text-blue-600 bg-blue-50' : '' }}">Pergerakan Stok</a>
                                <a href="{{ route('admin.inventory.suppliers.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.inventory.suppliers.*') ? 'text-blue-600 bg-blue-50' : '' }}">Supplier</a>
                            </div>
                        </div>

                        <!-- Kredit & Hutang (Credits) -->
                        <div x-data="{ open: openMenus.credits }" x-init="$watch('openMenus.credits', value => open = value)">
                            <button @click="openMenus.credits = !openMenus.credits; open = openMenus.credits" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.credits.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-cloak>Kredit & Hutang</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open && !sidebarCollapsed" x-cloak class="ml-8 mt-1 space-y-1">
                                <a href="{{ route('admin.credits.manual.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.credits.manual.*') ? 'text-blue-600 bg-blue-50' : '' }}">Daftar Kredit</a>
                                <a href="{{ route('admin.credits.payments.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.credits.payments.*') ? 'text-blue-600 bg-blue-50' : '' }}">Pembayaran Kredit</a>
                                <a href="{{ route('admin.credits.payments.report') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.credits.payments.report') ? 'text-blue-600 bg-blue-50' : '' }}">Laporan Kredit</a>
                            </div>
                        </div>

                        <!-- Customer -->
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-cloak>Customer</span>
                        </a>

                        <!-- Manage User -->
                        <a href="{{ route('admin.admins.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.admins.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-cloak>Manage User</span>
                        </a>

                        <!-- Settings -->
                        <a href="{{ route('admin.banners.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.banners.*') ? 'bg-gray-100' : '' }}" :class="{ 'justify-center': sidebarCollapsed }">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-cloak>Settings</span>
                        </a>
                    @endif
                </nav>

                <!-- User Profile at Bottom -->
                <div class="p-4 border-t border-gray-200">
                    <a href="{{ route('home') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" :class="{ 'justify-center': sidebarCollapsed }">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-cloak>Ke Website</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" x-cloak>
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
            <div class="fixed inset-y-0 left-0 flex flex-col w-72 bg-white">
                <!-- Mobile Logo -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Amanah Shop</span>
                    </div>
                    <button @click="sidebarOpen = false" class="p-1.5 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <nav class="flex-1 px-4 mt-6 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Orders
                    </a>

                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Products
                    </a>

                    @if(auth()->user()->isSuperAdmin())
                        <!-- Keuangan (Finance) -->
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.finance.*') ? 'bg-gray-100' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Keuangan</span>
                                </div>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                                <a href="{{ route('admin.finance.transactions.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.finance.transactions.*') ? 'text-blue-600 bg-blue-50' : '' }}">Transaksi Keuangan</a>
                                <a href="{{ route('admin.finance.categories.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.finance.categories.*') ? 'text-blue-600 bg-blue-50' : '' }}">Kategori Transaksi</a>
                                <a href="{{ route('admin.finance.report') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.finance.report') ? 'text-blue-600 bg-blue-50' : '' }}">Laporan Keuangan</a>
                            </div>
                        </div>

                        <!-- Inventori (Inventory) -->
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.inventory.*') ? 'bg-gray-100' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span>Inventori</span>
                                </div>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'text-blue-600 bg-blue-50' : '' }}">Kategori Produk</a>
                                <a href="{{ route('admin.inventory.movements.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.inventory.movements.*') ? 'text-blue-600 bg-blue-50' : '' }}">Pergerakan Stok</a>
                                <a href="{{ route('admin.inventory.suppliers.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.inventory.suppliers.*') ? 'text-blue-600 bg-blue-50' : '' }}">Supplier</a>
                            </div>
                        </div>

                        <!-- Kredit & Hutang (Credits) -->
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.credits.*') ? 'bg-gray-100' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span>Kredit & Hutang</span>
                                </div>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                                <a href="{{ route('admin.credits.manual.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.credits.manual.*') ? 'text-blue-600 bg-blue-50' : '' }}">Daftar Kredit</a>
                                <a href="{{ route('admin.credits.payments.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.credits.payments.*') ? 'text-blue-600 bg-blue-50' : '' }}">Pembayaran Kredit</a>
                                <a href="{{ route('admin.credits.payments.report') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.credits.payments.report') ? 'text-blue-600 bg-blue-50' : '' }}">Laporan Kredit</a>
                            </div>
                        </div>

                        <!-- Customer -->
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-100' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Customer
                        </a>

                        <!-- Manage User -->
                        <a href="{{ route('admin.admins.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.admins.*') ? 'bg-gray-100' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Manage User
                        </a>

                        <!-- Settings -->
                        <a href="{{ route('admin.banners.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.banners.*') ? 'bg-gray-100' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                    @endif
                </nav>

                <div class="p-4 border-t border-gray-200">
                    <a href="{{ route('home') }}" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Ke Website
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Left: Mobile Menu + Back Button + Search -->
                    <div class="flex items-center space-x-4 flex-1">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <button class="hidden lg:flex p-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <div class="relative flex-1 max-w-md hidden sm:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="search" placeholder="Search Anything..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Right: Notifications + User Profile -->
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-lg hover:bg-gray-100 relative">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <div class="flex items-center space-x-3 pl-4 border-l border-gray-200">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Your Balance<br/>${{ number_format(auth()->user()->id * 271, 0) }}</p>
                            </div>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <svg class="w-4 h-4 text-gray-600 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @stack('scripts')
</body>
</html>