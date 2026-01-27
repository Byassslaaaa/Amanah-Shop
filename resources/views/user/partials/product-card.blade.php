{{-- Product Card Component --}}

<!-- Clickable Card Content -->
<a href="{{ route('products.show', $product) }}" class="flex flex-col flex-1 cursor-pointer group/card">
    <!-- Product Image -->
    <div class="relative mb-4 overflow-hidden rounded-xl bg-[#F8F8F8]">
        @if ($product->images && count($product->images) > 0)
            <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                class="w-full h-44 object-contain group-hover/card:scale-105 transition-transform duration-700 ease-out p-3">
        @else
            <div class="w-full h-44 bg-gray-50 rounded flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif

        <!-- Discount Badge - More Modern -->
        @php
            $discountPercentage = rand(8, 20);
        @endphp
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[#3BB77E] text-white text-[11px] font-bold shadow-sm">
                {{ $discountPercentage }}%
            </span>
        </div>

        <!-- Wishlist Icon - Refined -->
        <div class="absolute top-3 right-3 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300">
            @auth
                @php
                    $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
                @endphp
                <form action="{{ route('user.favorites.toggle', $product) }}" method="POST" class="inline" onclick="event.stopPropagation()">
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
                <a href="{{ route('login') }}" onclick="event.stopPropagation()"
                    class="bg-white hover:bg-[#FF6B6B] w-9 h-9 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 group/heart">
                    <svg class="w-5 h-5 text-gray-600 group-hover/heart:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>
            @endauth
        </div>
    </div>

    <!-- Category Name -->
    <div class="text-[11px] text-gray-400 mb-1.5 font-medium uppercase tracking-wide">
        {{ $product->category_name_for_display ?? $product->category->name ?? 'Uncategorized' }}
    </div>

    <!-- Product Name -->
    <h3 class="font-bold text-[#253D4E] text-[14px] mb-2.5 line-clamp-2 group-hover/card:text-[#3BB77E] transition-colors duration-300 leading-snug min-h-[2.5rem]">
        {{ $product->name }}
    </h3>

    <!-- Rating -->
    <div class="flex items-center gap-1 mb-3">
        @php
            $avgRating = $product->average_rating ?? 0;
            $reviewsCount = $product->reviews_count ?? 0;
            $fullStars = floor($avgRating);
            $hasHalfStar = $avgRating - $fullStars >= 0.5;
        @endphp
        <div class="flex items-center gap-0.5">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $fullStars)
                    <svg class="w-3.5 h-3.5 text-[#FDC040] fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @elseif($i == $fullStars + 1 && $hasHalfStar)
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20">
                        <defs>
                            <linearGradient id="half-{{ $product->id }}">
                                <stop offset="50%" stop-color="#FDC040"/>
                                <stop offset="50%" stop-color="#E5E7EB"/>
                            </linearGradient>
                        </defs>
                        <path fill="url(#half-{{ $product->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
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

    <!-- Vendor/By Info -->
    <div class="text-[11px] text-gray-500 mb-3 font-medium">
        By <span class="text-[#3BB77E] font-semibold">Amanah Shop</span>
    </div>

    <!-- Price -->
    <div class="flex items-baseline gap-2 mb-4">
        <span class="text-[18px] font-extrabold text-[#3BB77E]">
            Rp{{ number_format($product->price, 0, ',', '.') }}
        </span>
        <span class="text-[13px] text-gray-400 line-through font-medium">
            Rp{{ number_format($product->price * 1.4, 0, ',', '.') }}
        </span>
    </div>
</a>

<!-- Add to Cart Button - Enhanced -->
<div class="mt-auto" onclick="event.stopPropagation()">
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
