@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name . ' - Amanah Shop')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-green-600">Beranda</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('products.index') }}"
                                class="ml-1 text-gray-700 hover:text-green-600">Produk</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Category Header -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <div class="text-center">
                    @if ($category->image)
                        {{-- <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-24 h-24 mx-auto mb-4 rounded-full object-cover"> --}}
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                            class="w-24 h-24 mx-auto mb-4 rounded-full object-cover">
                    @else
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    @endif
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
                    @if ($category->description)
                        <p class="text-gray-600 max-w-2xl mx-auto">{{ $category->description }}</p>
                    @endif
                    <p class="text-sm text-green-600 mt-2">{{ $products->total() }} produk tersedia</p>
                </div>
            </div>

            <!-- Products Grid -->
            @if ($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4 mb-8">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product) }}" 
                           class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md hover:border-green-300 transition-all duration-200 group block">
                            <!-- Product Image -->
                            <div class="relative overflow-hidden">
                                @if ($product->images && count($product->images) > 0)
                                    <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}" 
                                         class="w-full h-32 sm:h-40 md:h-44 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-32 sm:h-40 md:h-44 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4m0 0H8m4 0h4">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk di kategori ini</h3>
                    <p class="text-gray-600">Silakan kembali nanti atau lihat kategori lainnya</p>
                    <a href="{{ route('products.index') }}"
                        class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                        Lihat Semua Produk
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
