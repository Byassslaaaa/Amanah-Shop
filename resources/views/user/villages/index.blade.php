@extends('layouts.app')

@section('title', 'Semua Desa - BUMDes Marketplace')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Jelajahi Desa-Desa</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Temukan BUMDes dari berbagai desa dengan produk unggulan mereka</p>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6 sm:mb-8">
            <form method="GET" action="{{ route('villages.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Desa</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama desa..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm sm:text-base">
                        </div>
                    </div>

                    <!-- Province Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi</label>
                        <div class="relative">
                            <select name="province" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-sm appearance-none">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>
                                        {{ $province }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-2 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 text-sm font-semibold flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari
                        </button>
                        <a href="{{ route('villages.index') }}" class="w-full sm:w-auto bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all duration-200 text-center text-sm font-semibold flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </a>
                    </div>

                    <!-- Results Counter -->
                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-semibold">{{ $villages->count() }}</span> dari <span class="font-semibold">{{ $villages->total() }}</span> desa
                    </div>
                </div>
            </form>
        </div>

        <!-- Villages Grid -->
        @if($villages->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach ($villages as $village)
                    <a href="{{ route('villages.show', $village->slug) }}"
                       class="bg-white rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-200 hover:border-[#3BB77E]">
                        <!-- Cover Photo -->
                        <div class="relative h-32 bg-gradient-to-r from-[#3BB77E] to-[#2a9d65] overflow-hidden">
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

                        <!-- Village Info -->
                        <div class="relative px-5 pb-5 pt-12">
                            <!-- Profile Photo - Overlapping Cover -->
                            <div class="absolute -top-10 left-1/2 -translate-x-1/2">
                                <div class="relative">
                                    <div class="w-20 h-20 bg-white rounded-full shadow-lg border-4 border-white overflow-hidden group-hover:scale-110 transition-transform">
                                        @if($village->logo)
                                            <img src="{{ asset($village->logo) }}"
                                                 alt="{{ $village->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-[#3BB77E] to-[#2a9d65] flex items-center justify-center">
                                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Verified Badge -->
                                    <div class="absolute -bottom-1 -right-1 bg-[#3BB77E] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg border-2 border-white">
                                        ✓
                                    </div>
                                </div>
                            </div>

                            <!-- Village Name -->
                            <h3 class="text-center text-lg font-bold text-[#253D4E] mb-2 group-hover:text-[#3BB77E] transition-colors line-clamp-1">
                                {{ $village->name }}
                            </h3>

                            <!-- Location -->
                            <div class="flex items-center justify-center text-xs text-gray-600 mb-1">
                                <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="truncate">{{ $village->district }}, {{ $village->city }}</span>
                            </div>

                            <div class="text-center text-[11px] text-gray-500 mb-3">
                                {{ $village->province }}
                            </div>

                            @if($village->description)
                                <p class="text-xs text-gray-600 text-center mb-4 line-clamp-2 px-2">
                                    {{ $village->description }}
                                </p>
                            @endif

                            <!-- Stats & Action -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-[#3BB77E]">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span class="text-sm font-semibold">{{ $village->products_count }}</span>
                                    <span class="text-xs ml-1">Produk</span>
                                </div>
                                <span class="text-[#3BB77E] text-sm font-medium group-hover:translate-x-1 transition-transform flex items-center">
                                    Kunjungi
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $villages->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada desa ditemukan</h3>
                <p class="text-gray-600 mb-4">Coba ubah pencarian Anda atau lihat semua desa</p>
                <a href="{{ route('villages.index') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                    Lihat Semua Desa
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
