@extends('layouts.admin')

@section('title', 'Kelola Halaman About')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Halaman About</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola konten halaman Tentang Kami</p>
        </div>
        <a href="{{ route('admin.about.edit') }}"
            class="inline-flex items-center px-4 py-2 bg-[#3BB77E] text-white rounded-lg hover:bg-[#2a9d66] transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Konten
        </a>
    </div>

    @if($aboutContent)
    <!-- Current Content Preview -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Main Content Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Konten Utama</h2>
            </div>
            <div class="p-6">
                @if($aboutContent->image)
                    <div class="mb-4">
                        <img src="{{ $aboutContent->getImageDataUri() ?? Storage::url($aboutContent->image) }}"
                            alt="About Image" class="w-full h-48 object-cover rounded-lg">
                    </div>
                @endif
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $aboutContent->title }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ $aboutContent->content ?: 'Belum ada konten' }}</p>
            </div>
        </div>

        <!-- Vision & Mission Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Visi & Misi</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Visi</h4>
                    <p class="text-gray-600 text-sm">{{ $aboutContent->vision ?: 'Belum diisi' }}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Misi</h4>
                    @if($aboutContent->mission)
                        <ul class="text-gray-600 text-sm space-y-1">
                            @foreach(explode("\n", $aboutContent->mission) as $mission)
                                @if(trim($mission))
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-[#3BB77E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ trim($mission) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600 text-sm">Belum diisi</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Statistik (Ditampilkan di Halaman About)</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-[#3BB77E]">{{ $aboutContent->years_operating }}+</div>
                        <p class="text-sm text-gray-600 mt-1">Tahun Beroperasi</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-[#3BB77E]">{{ number_format($aboutContent->happy_customers) }}+</div>
                        <p class="text-sm text-gray-600 mt-1">Pelanggan Puas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-[#3BB77E]">{{ number_format($aboutContent->products_sold) }}+</div>
                        <p class="text-sm text-gray-600 mt-1">Produk Terjual</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-[#3BB77E]">{{ $aboutContent->team_members }}+</div>
                        <p class="text-sm text-gray-600 mt-1">Tim Profesional</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-[#3BB77E]">{{ number_format($aboutContent->product_variants) }}+</div>
                        <p class="text-sm text-gray-600 mt-1">Varian Produk</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Konten</h3>
        <p class="text-gray-600 mb-4">Konten halaman About belum diatur</p>
        <a href="{{ route('admin.about.edit') }}"
            class="inline-flex items-center px-4 py-2 bg-[#3BB77E] text-white rounded-lg hover:bg-[#2a9d66] transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Konten
        </a>
    </div>
    @endif
</div>
@endsection
