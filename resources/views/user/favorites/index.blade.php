@extends('layouts.app')

@section('title', 'Produk Favorit Saya')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-[#3BB77E]">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Produk Favorit</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="flex items-center gap-3 mb-8">
            <svg class="w-8 h-8 text-[#FF6B6B]" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                    clip-rule="evenodd" />
            </svg>
            <h1 class="text-3xl font-bold text-[#253D4E]" style="font-family: 'Quicksand', sans-serif;">
                Produk Favorit Saya
            </h1>
        </div>

        @if ($favorites->count() > 0)
            <!-- Favorites Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach ($favorites as $favorite)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <a href="{{ route('products.show', $favorite->product) }}" class="block">
                            <!-- Product Image -->
                            <div class="relative aspect-square overflow-hidden bg-gray-100">
                                @if ($favorite->product->images && count($favorite->product->images) > 0)
                                    <img src="{{ $favorite->product->getImageDataUri(0) }}" alt="{{ $favorite->product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Remove Favorite Button -->
                                <form action="{{ route('user.favorites.destroy', $favorite) }}" method="POST"
                                    class="absolute top-3 right-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-[#FF6B6B] hover:bg-[#FF6B6B] hover:text-white transition-all shadow-lg group/btn"
                                        onclick="return confirm('Hapus produk ini dari favorit?')">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Category Badge -->
                                @if ($favorite->product->category)
                                    <span
                                        class="absolute bottom-3 left-3 px-3 py-1 bg-[#3BB77E] text-white text-xs font-bold rounded-full">
                                        {{ $favorite->product->category->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <h3 class="text-base font-bold text-[#253D4E] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-colors"
                                    style="font-family: 'Quicksand', sans-serif;">
                                    {{ $favorite->product->name }}
                                </h3>

                                <!-- Village -->
                                @if ($favorite->product->village)
                                    <div class="flex items-center gap-1 mb-2 text-xs text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $favorite->product->village->name }}</span>
                                    </div>
                                @endif

                                <!-- Price -->
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-[#3BB77E]">
                                        Rp{{ number_format($favorite->product->price, 0, ',', '.') }}
                                    </span>

                                    @if ($favorite->product->stock > 0)
                                        <span class="text-xs text-green-600 font-medium">Stok: {{ $favorite->product->stock }}</span>
                                    @else
                                        <span class="text-xs text-red-600 font-medium">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <!-- Add to Cart Button -->
                        <div class="px-4 pb-4">
                            @if ($favorite->product->stock > 0)
                                <form action="{{ route('user.cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $favorite->product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit"
                                        class="w-full bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            @else
                                <button disabled
                                    class="w-full bg-gray-300 text-gray-500 font-bold py-2.5 px-4 rounded-lg cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 mb-2" style="font-family: 'Quicksand', sans-serif;">
                    Belum Ada Produk Favorit
                </h3>
                <p class="text-gray-500 mb-6">
                    Anda belum menambahkan produk ke dalam daftar favorit
                </p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-2 bg-[#3BB77E] hover:bg-[#2a9d66] text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Jelajahi Produk
                </a>
            </div>
        @endif
    </div>
@endsection
