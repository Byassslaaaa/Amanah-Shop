@extends('layouts.app')

@section('title', $village->name . ' - BUMDes Marketplace')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Village Header with Cover -->
    <div class="relative">
        <!-- Cover Photo -->
        <div class="h-64 sm:h-80 bg-gradient-to-r from-[#3BB77E] to-[#2a9d65] relative overflow-hidden">
            @if($village->cover_photo)
                <img src="{{ asset($village->cover_photo) }}"
                     alt="{{ $village->name }} Cover"
                     class="w-full h-full object-cover">
            @else
                <!-- Default Pattern Background -->
                <div class="absolute inset-0">
                    <img src="{{ asset('images/patterns/village-cover-pattern.jpg') }}"
                         alt="Pattern Background"
                         class="w-full h-full object-cover opacity-20"
                         onerror="this.style.display='none'">
                </div>
            @endif
        </div>

        <!-- Profile Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="relative -mt-20 sm:-mt-24">
                <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <!-- Profile Photo -->
                        <div class="relative">
                            <div class="w-32 h-32 sm:w-40 sm:h-40 bg-white rounded-full shadow-lg border-4 border-white flex-shrink-0 overflow-hidden">
                                @if($village->logo)
                                    <img src="{{ asset($village->logo) }}"
                                         alt="{{ $village->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#3BB77E] to-[#2a9d65] flex items-center justify-center">
                                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <!-- Badge -->
                            <div class="absolute bottom-0 right-0 bg-[#3BB77E] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg border-2 border-white">
                                Verified
                            </div>
                        </div>

                        <!-- Village Info -->
                        <div class="flex-1 text-center sm:text-left">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $village->name }}</h1>

                            @if($village->description)
                                <p class="text-gray-600 text-sm sm:text-base mb-4 max-w-3xl">{{ $village->description }}</p>
                            @endif

                            <!-- Stats -->
                            <div class="flex flex-wrap gap-3 sm:gap-4 justify-center sm:justify-start mb-4">
                                <!-- Location -->
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $village->city }}, {{ $village->province }}</span>
                                </div>

                                <!-- Products Count -->
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span class="font-medium">{{ $village->products->count() }} Products</span>
                                </div>

                                <!-- Phone -->
                                @if($village->phone)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="font-medium">{{ $village->phone }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons - Contact Desa -->
                            <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                                @if($village->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $village->whatsapp) }}?text=Halo%20{{ urlencode($village->name) }}%2C%20saya%20ingin%20bertanya%20tentang%20produk%20Anda"
                                       target="_blank"
                                       class="inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#1da851] text-white px-6 py-3 rounded-xl transition-all font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        Chat WhatsApp
                                    </a>
                                @endif

                                @if($village->email)
                                    <a href="mailto:{{ $village->email }}?subject=Pertanyaan%20tentang%20{{ urlencode($village->name) }}"
                                       class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-xl transition-all font-semibold text-sm border-2 border-gray-300 hover:border-[#3BB77E] shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Kirim Email
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Produk dari {{ $village->name }}</h2>
            <p class="text-gray-600">Jelajahi {{ $village->products->count() }} produk berkualitas dari desa ini</p>
        </div>

        @if($village->products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($village->products as $product)
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

                    <a href="{{ route('products.show', $product) }}"
                       class="bg-white rounded-2xl p-4 border border-gray-200 hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300 group flex flex-col">
                        <!-- Product Image -->
                        <div class="relative mb-3 overflow-hidden rounded-lg">
                            @if ($product->images && count($product->images) > 0)
                                <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                     class="w-full h-32 object-contain group-hover:scale-110 transition-transform duration-500 ease-out">
                            @else
                                <div class="w-full h-32 bg-gray-50 rounded flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Stock Badge -->
                            @if($product->stock <= 0)
                                <div class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">
                                    Habis
                                </div>
                            @elseif($product->stock < 10)
                                <div class="absolute top-2 right-2 bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">
                                    {{ $product->stock }} left
                                </div>
                            @endif
                        </div>

                        <!-- Category -->
                        <div class="text-[11px] text-gray-500 mb-1 transition-colors duration-300 group-hover:text-[#3BB77E]">
                            {{ $product->category->name }}
                        </div>

                        <!-- Product Name -->
                        <h3 class="font-bold text-[#253D4E] text-[13px] mb-2 line-clamp-2 group-hover:text-[#3BB77E] transition-all duration-300 leading-tight min-h-[32px]">
                            {{ $product->name }}
                        </h3>

                        <!-- Rating -->
                        <div class="flex items-center gap-1 mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($avgRating))
                                    <svg class="w-3 h-3 text-[#FDC040]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                            <span class="text-[11px] text-gray-500 ml-1">({{ number_format($avgRating, 1) }})</span>
                        </div>

                        <!-- Price -->
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-[16px] font-bold text-[#3BB77E]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Sold Info -->
                        <div class="text-[11px] text-gray-500 mt-auto">
                            Terjual: {{ $sold }}
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4m0 0H8m4 0h4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk</h3>
                <p class="text-gray-600">Desa ini belum menambahkan produk</p>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <a href="{{ route('villages.index') }}"
           class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Desa
        </a>
    </div>
</div>

@endsection
