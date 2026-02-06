@extends('layouts.app')

@section('title', $product->name . ' - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#3BB77E]">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-[#3BB77E]">Produk</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700">{{ $product->category->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Product Detail -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Left: Product Images -->
                <div class="flex flex-col-reverse sm:flex-row gap-4">
                    @php
                        // Create dummy gallery with 4 images using the main product image
                        $mainImage = $product->images && count($product->images) > 0 ? $product->getImageDataUri(0) : null;
                        $galleryImages = [];
                        if ($mainImage) {
                            // If product has multiple images, use them; otherwise use dummy with same image
                            if ($product->images && count($product->images) > 1) {
                                foreach($product->images as $index => $image) {
                                    $galleryImages[] = $product->getImageDataUri($index);
                                }
                            } else {
                                // Create 4 dummy gallery items with the same image
                                for ($i = 0; $i < 4; $i++) {
                                    $galleryImages[] = $mainImage;
                                }
                            }
                        }
                    @endphp

                    @if($mainImage)
                        <!-- Thumbnails -->
                        <div class="flex sm:flex-col gap-2 justify-center sm:justify-start">
                            @foreach($galleryImages as $index => $imageUrl)
                            <div class="w-[70px] h-[70px] sm:w-[90px] sm:h-[90px] rounded-lg cursor-pointer transition-all overflow-hidden {{ $index === 0 ? 'ring-2 ring-[#3BB77E] ring-offset-2 opacity-100' : 'opacity-60 hover:opacity-100' }}"
                                 onclick="changeMainImage('{{ $imageUrl }}', {{ $index }})"
                                 data-thumb-index="{{ $index }}">
                                <img src="{{ $imageUrl }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        </div>

                        <!-- Main Image -->
                        <div class="flex-1 relative">
                            <div class="rounded-xl overflow-hidden bg-gray-50 relative group aspect-square shadow-sm">
                                <img id="main-image"
                                     src="{{ $galleryImages[0] }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain p-6">

                                <!-- Navigation Arrows -->
                                <button onclick="navigatePrevImage()"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white rounded-full p-2.5 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button onclick="navigateNextImage()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white rounded-full p-2.5 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Image Counter -->
                                <div class="absolute bottom-3 right-3 bg-black/70 text-white text-xs px-2.5 py-1 rounded-full font-medium">
                                    <span id="current-image-index">1</span> / {{ count($galleryImages) }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Right: Product Info -->
                <div>
                    @php
                        $avgRating = $product->approvedReviews()->avg('rating') ?? 0;
                        $reviewCount = $product->approvedReviews()->count();
                        $sold = DB::table('order_items')
                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->where('order_items.product_id', $product->id)
                            ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                            ->where('orders.payment_status', 'paid')
                            ->sum('order_items.quantity');
                    @endphp

                    <!-- Product Name -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>

                    <!-- Rating & Reviews -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= floor($avgRating) ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <a href="#reviews" class="text-sm text-gray-600 hover:text-[#3BB77E]">({{ $reviewCount }} reviews)</a>
                        <span class="text-gray-300">|</span>
                        <a href="#reviews" class="text-sm text-[#3BB77E] hover:underline">Write a review</a>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex items-baseline gap-3 mb-1">
                            <span class="text-4xl font-bold text-[#3BB77E]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $sold }} products sold</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <p class="text-gray-700 leading-relaxed">{{ Str::limit($product->description, 250) }}</p>
                    </div>

                    <!-- Product Details -->
                    <div class="mb-6 space-y-3">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 w-32">Kategori:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $product->category->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 w-32">Stok:</span>
                            <span class="text-sm font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                @if($product->stock > 0)
                                    {{ $product->stock }} In Stock
                                @else
                                    Out of Stock
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($product->status === 'active' && $product->stock > 0)
                    <div class="mb-6">
                        @auth
                            <form action="{{ route('user.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <!-- Quantity -->
                                <div class="flex items-center gap-6 mb-6">
                                    <div class="flex items-center border border-gray-300 rounded">
                                        <button type="button" onclick="decrementQuantity()" class="px-4 py-2 hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                               class="w-16 text-center border-0 focus:outline-none font-medium">
                                        <button type="button" onclick="incrementQuantity()" class="px-4 py-2 hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <button type="submit" class="flex-1 bg-[#3BB77E] hover:bg-[#2a9d65] text-white px-8 py-3 rounded font-semibold transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        Add To Cart
                                    </button>
                                </div>
                            </form>
                        @else
                        <a href="{{ route('login') }}" class="block w-full bg-[#3BB77E] hover:bg-[#2a9d65] text-white px-8 py-3 rounded font-semibold transition-colors text-center mb-6">
                            Login to Purchase
                        </a>
                        @endauth

                        <!-- Secondary Actions -->
                        <div class="flex gap-3">
                            @auth
                            <button onclick="toggleFavorite({{ $product->id }})"
                                    id="favorite-btn-{{ $product->id }}"
                                    class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded hover:border-[#3BB77E] hover:text-[#3BB77E] transition-colors {{ auth()->user()->favorites()->where('product_id', $product->id)->exists() ? 'text-[#3BB77E] border-[#3BB77E]' : 'text-gray-700' }}">
                                <svg class="w-5 h-5" fill="{{ auth()->user()->favorites()->where('product_id', $product->id)->exists() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Add to wishlist</span>
                            </button>
                            @endauth

                            @if($product->whatsapp_number)
                            <button onclick="openWhatsAppOrder('{{ $product->whatsapp_number }}', '{{ $product->name }}', '{{ number_format($product->price, 0, ',', '.') }}', '{{ route('products.show', $product) }}')"
                                    class="flex items-center gap-2 px-5 py-2.5 border border-[#25D366] text-[#25D366] rounded hover:bg-[#25D366] hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                <span class="text-sm font-medium">Chat Penjual</span>
                            </button>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="p-6 bg-red-50 border border-red-200 rounded text-center">
                        <p class="text-red-700 font-medium">Product currently unavailable</p>
                    </div>
                    @endif

                    <!-- Share -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-gray-700 uppercase">Share:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('products.show', $product)) }}" target="_blank"
                               class="text-blue-600 hover:text-blue-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('products.show', $product)) }}&text={{ urlencode($product->name) }}" target="_blank"
                               class="text-sky-500 hover:text-sky-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(route('products.show', $product)) }}&description={{ urlencode($product->name) }}" target="_blank"
                               class="text-red-600 hover:text-red-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-8" x-data="{ activeTab: 'description' }">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button @click="activeTab = 'description'"
                            :class="activeTab === 'description' ? 'border-[#3BB77E] text-[#3BB77E]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap">
                        Description
                    </button>
                    <button @click="activeTab = 'details'"
                            :class="activeTab === 'details' ? 'border-[#3BB77E] text-[#3BB77E]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap">
                        Product Details
                    </button>
                    <button @click="activeTab = 'reviews'"
                            :class="activeTab === 'reviews' ? 'border-[#3BB77E] text-[#3BB77E]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap">
                        Reviews ({{ $reviewCount }})
                    </button>
                </nav>
            </div>

            <div class="p-8">
                <!-- Description -->
                <div x-show="activeTab === 'description'" x-transition>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                </div>

                <!-- Details -->
                <div x-show="activeTab === 'details'" x-transition>
                    <table class="w-full">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-3 text-sm text-gray-600 w-1/3">Kategori</td>
                                <td class="py-3 text-sm font-medium">{{ $product->category->name }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm text-gray-600">Stok</td>
                                <td class="py-3 text-sm font-medium">{{ $product->stock }} units</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm text-gray-600">Terjual</td>
                                <td class="py-3 text-sm font-medium">{{ $sold }} units</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Reviews -->
                <div x-show="activeTab === 'reviews'" x-transition id="reviews">
                    @if($reviewCount > 0)
                        <div class="space-y-6">
                            @foreach($product->approvedReviews()->latest()->get() as $review)
                            <div class="flex gap-4 pb-6 border-b border-gray-200 last:border-0">
                                <div class="w-12 h-12 rounded-full bg-[#3BB77E] text-white flex items-center justify-center font-bold text-lg">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold">{{ $review->user->name }}</h4>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                        @endfor
                                    </div>
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">Belum ada ulasan untuk produk ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h2 class="text-2xl font-bold mb-6">Other Products</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($relatedProducts as $related)
                @php
                    $relatedSold = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('order_items.product_id', $related->id)
                        ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                        ->where('orders.payment_status', 'paid')
                        ->sum('order_items.quantity');
                @endphp
                <a href="{{ route('products.show', $related) }}" class="group">
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow relative">
                        @if($related->images && count($related->images) > 0)
                        <img src="{{ $related->getImageDataUri(0) }}" alt="{{ $related->name }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif

                        <div class="p-3">
                            <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 group-hover:text-[#3BB77E]">{{ $related->name }}</h3>
                            <div class="text-lg font-bold text-[#3BB77E] mb-1">Rp{{ number_format($related->price, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">{{ $relatedSold }} sold</div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
const maxStock = {{ $product->stock }};
@php
    $mainImage = $product->images && count($product->images) > 0 ? $product->getImageDataUri(0) : null;
    $jsGalleryImages = [];
    if ($mainImage) {
        if ($product->images && count($product->images) > 1) {
            foreach($product->images as $index => $image) {
                $jsGalleryImages[] = $product->getImageDataUri($index);
            }
        } else {
            for ($i = 0; $i < 4; $i++) {
                $jsGalleryImages[] = $mainImage;
            }
        }
    }
@endphp
const totalImages = {{ count($jsGalleryImages) }};
let currentImageIndex = 0;

const imageUrls = [
    @foreach($jsGalleryImages as $index => $imageUrl)
        '{{ $imageUrl }}'{{ $loop->last ? '' : ',' }}
    @endforeach
];

function changeMainImage(src, index) {
    currentImageIndex = index;
    document.getElementById('main-image').src = src;
    updateImageCounter();

    document.querySelectorAll('[data-thumb-index]').forEach(thumb => {
        if (parseInt(thumb.dataset.thumbIndex) === index) {
            thumb.classList.remove('opacity-60', 'hover:opacity-100');
            thumb.classList.add('ring-2', 'ring-[#3BB77E]', 'ring-offset-2', 'opacity-100');
        } else {
            thumb.classList.remove('ring-2', 'ring-[#3BB77E]', 'ring-offset-2');
            thumb.classList.add('opacity-60', 'hover:opacity-100');
        }
    });
}

function navigateNextImage() {
    currentImageIndex = (currentImageIndex + 1) % totalImages;
    changeMainImage(imageUrls[currentImageIndex], currentImageIndex);
}

function navigatePrevImage() {
    currentImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
    changeMainImage(imageUrls[currentImageIndex], currentImageIndex);
}

function updateImageCounter() {
    const counterElement = document.getElementById('current-image-index');
    if (counterElement) {
        counterElement.textContent = currentImageIndex + 1;
    }
}

function incrementQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current < maxStock) {
        input.value = current + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}

function openWhatsAppOrder(whatsappNumber, productName, productPrice, productUrl) {
    let quantity = 1;
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantity = parseInt(quantityInput.value) || 1;
    }

    const priceValue = parseInt(productPrice.replace(/\./g, ''));
    const totalPrice = priceValue * quantity;
    const formattedTotalPrice = totalPrice.toLocaleString('id-ID');

    const message = `🛍️ *Halo! Saya tertarik dengan produk ini*

📦 *Detail Produk:*
• Nama: ${productName}
• Harga: Rp ${productPrice}
• Jumlah: ${quantity} unit
• Total: Rp ${formattedTotalPrice}

🔗 ${productUrl}

Terima kasih!`;

    const cleanPhone = whatsappNumber.replace(/\D/g, '');
    const formattedPhone = cleanPhone.startsWith('62') ? cleanPhone :
                          cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) :
                          '62' + cleanPhone;

    const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

@auth
function toggleFavorite(productId) {
    fetch(`/user/favorites/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById(`favorite-btn-${productId}`);
        if (btn) {
            const svg = btn.querySelector('svg');
            if (data.favorited) {
                btn.classList.add('text-[#3BB77E]', 'border-[#3BB77E]');
                btn.classList.remove('text-gray-700');
                svg.setAttribute('fill', 'currentColor');
            } else {
                btn.classList.remove('text-[#3BB77E]', 'border-[#3BB77E]');
                btn.classList.add('text-gray-700');
                svg.setAttribute('fill', 'none');
            }
        }
    });
}
@endauth
</script>
@endsection
