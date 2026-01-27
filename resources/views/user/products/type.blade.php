@extends('layouts.app')

@section('title', $typeLabel . ' - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $typeLabel }}</h1>
            <p class="text-gray-600 mt-2">
                @if($type === 'barang')
                    Temukan berbagai produk barang berkualitas
                @else
                    Temukan layanan jasa terbaik
                @endif
            </p>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="{{ route('products.type', $type) }}" class="space-y-4" id="searchForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari {{ $type === 'barang' ? 'Produk' : 'Jasa' }}</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama {{ $type === 'barang' ? 'produk' : 'jasa' }} atau deskripsi..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                               id="searchInput">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 filter-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Min</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                   placeholder="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 filter-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Max</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                   placeholder="1000000"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 filter-input">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 filter-select">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Harga Terendah</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <a href="{{ route('products.type', $type) }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition-colors">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="relative">
            <!-- Loading Overlay for Products Area -->
            <div id="productsLoadingOverlay" class="absolute inset-0 bg-white bg-opacity-80 backdrop-blur-sm z-10 hidden items-center justify-center rounded-lg">
                <div class="flex flex-col items-center space-y-3">
                    <svg class="animate-spin h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-700 font-medium">Mencari {{ $type === 'barang' ? 'produk' : 'jasa' }}...</p>
                </div>
            </div>

            @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4 mb-8" id="productsGrid">
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product) }}" 
                       class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md hover:border-green-300 transition-all duration-200 group block">
                        <!-- Product Image -->
                        <div class="relative overflow-hidden">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}" 
                                     class="w-full h-32 sm:h-40 md:h-44 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-32 sm:h-40 md:h-44 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($type === 'barang')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 6.273A24.317 24.317 0 007.52 15.012M9 7h6m0 10v-3a1 1 0 00-1-1H10a1 1 0 00-1 1v3a1 1 0 001 1h4a1 1 0 001-1z"/>
                                        @endif
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-2 sm:p-3">
                            <!-- Product Name -->
                            <h3 class="font-normal text-gray-800 mb-1 text-xs sm:text-sm line-clamp-2 leading-tight">
                                {{ $product->name }}
                            </h3>
                            
                            <!-- Price -->
                            <div class="mt-2">
                                <span class="text-sm sm:text-base font-bold text-gray-900">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @if($type === 'jasa')
                                    <span class="text-xs text-gray-500">/layanan</span>
                                @endif
                            </div>
                            
                            <!-- Location/Category -->
                            <div class="mt-1">
                                <span class="text-xs text-gray-500">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($type === 'barang')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 6.273A24.317 24.317 0 007.52 15.012M9 7h6m0 10v-3a1 1 0 00-1-1H10a1 1 0 00-1 1v3a1 1 0 001-1h4a1 1 0 001-1z"></path>
                    @endif
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    Tidak ada {{ $type === 'barang' ? 'produk barang' : 'layanan jasa' }} yang ditemukan
                </h3>
                <p class="text-gray-600 mb-6">
                    Coba ubah filter pencarian atau periksa kembali nanti untuk {{ $type === 'barang' ? 'produk barang' : 'layanan jasa' }} terbaru.
                </p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    Lihat Semua Produk
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const filterSelects = document.querySelectorAll('.filter-select');
    const filterInputs = document.querySelectorAll('.filter-input');
    const loadingOverlay = document.getElementById('loadingOverlay');

    let searchTimeout;
    let isSubmitting = false;

    // Function to show loading
    function showLoading() {
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
    }

    // Function to hide loading
    function hideLoading() {
        loadingOverlay.classList.add('hidden');
        loadingOverlay.classList.remove('flex');
    }

    // Function to submit form with loading
    function submitFormWithLoading() {
        if (isSubmitting) return; // Prevent double submission

        isSubmitting = true;
        showLoading();

        // Small delay to ensure loading shows before navigation
        setTimeout(function() {
            searchForm.submit();
        }, 100);

        // Reset flag after 2 seconds (safety measure)
        setTimeout(function() {
            isSubmitting = false;
        }, 2000);
    }

    // Debounce function for search input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            submitFormWithLoading();
        }, 500); // 500ms delay after user stops typing
    });

    // Instant submit for select filters
    filterSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            submitFormWithLoading();
        });
    });

    // Debounce function for price inputs
    filterInputs.forEach(function(input) {
        let inputTimeout;
        input.addEventListener('input', function() {
            clearTimeout(inputTimeout);
            inputTimeout = setTimeout(function() {
                submitFormWithLoading();
            }, 800); // 800ms delay for price inputs
        });
    });

    // Hide loading when page is fully loaded (back button case)
    window.addEventListener('pageshow', function() {
        hideLoading();
        isSubmitting = false;
    });
});
</script>

@endsection