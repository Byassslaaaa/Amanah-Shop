@extends('layouts.admin')

@section('title', 'Edit Halaman About')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.about.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Halaman About</h1>
            <p class="text-sm text-gray-600 mt-1">Perbarui konten halaman Tentang Kami</p>
        </div>
    </div>

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Content Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Konten Utama</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $aboutContent->title ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E] @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="content" id="content" rows="5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E] @error('content') border-red-500 @enderror"
                                placeholder="Deskripsi singkat tentang toko...">{{ old('content', $aboutContent->content ?? '') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Vision & Mission Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Visi & Misi</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="vision" class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                            <textarea name="vision" id="vision" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E]"
                                placeholder="Visi perusahaan...">{{ old('vision', $aboutContent->vision ?? '') }}</textarea>
                        </div>

                        <div>
                            <label for="mission" class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                            <textarea name="mission" id="mission" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E]"
                                placeholder="Satu misi per baris...">{{ old('mission', $aboutContent->mission ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Tulis satu misi per baris (pisahkan dengan Enter)</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Statistik</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label for="years_operating" class="block text-sm font-medium text-gray-700 mb-1">Tahun Beroperasi</label>
                                <input type="number" name="years_operating" id="years_operating" min="0"
                                    value="{{ old('years_operating', $aboutContent->years_operating ?? 5) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E]">
                            </div>
                            <div>
                                <label for="happy_customers" class="block text-sm font-medium text-gray-700 mb-1">Pelanggan Puas</label>
                                <input type="number" name="happy_customers" id="happy_customers" min="0"
                                    value="{{ old('happy_customers', $aboutContent->happy_customers ?? 1000) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E]">
                            </div>
                            <div>
                                <label for="products_sold" class="block text-sm font-medium text-gray-700 mb-1">Produk Terjual</label>
                                <input type="number" name="products_sold" id="products_sold" min="0"
                                    value="{{ old('products_sold', $aboutContent->products_sold ?? 500) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E]">
                            </div>
                            <div>
                                <label for="team_members" class="block text-sm font-medium text-gray-700 mb-1">Tim Profesional</label>
                                <input type="number" name="team_members" id="team_members" min="0"
                                    value="{{ old('team_members', $aboutContent->team_members ?? 10) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E]">
                            </div>
                            <div>
                                <label for="product_variants" class="block text-sm font-medium text-gray-700 mb-1">Varian Produk</label>
                                <input type="number" name="product_variants" id="product_variants" min="0"
                                    value="{{ old('product_variants', $aboutContent->product_variants ?? 200) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Image Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Gambar</h2>
                    </div>
                    <div class="p-6">
                        @if($aboutContent && $aboutContent->image)
                            <div class="mb-4 relative">
                                <img src="{{ $aboutContent->getImageDataUri() ?? Storage::url($aboutContent->image) }}"
                                    alt="Current Image" class="w-full h-48 object-cover rounded-lg" id="preview-image">
                                <a href="{{ route('admin.about.delete-image') }}"
                                    onclick="return confirm('Hapus gambar ini?')"
                                    class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            </div>
                        @else
                            <div class="mb-4 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center" id="preview-container">
                                <img src="" alt="Preview" class="hidden w-full h-48 object-cover rounded-lg mb-4" id="preview-image">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" id="placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500" id="placeholder-text">Belum ada gambar</p>
                            </div>
                        @endif

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3BB77E] focus:border-[#3BB77E] text-sm"
                                onchange="previewImage(this)">
                            <p class="mt-1 text-xs text-gray-500">Max 2MB. Format: JPG, PNG, GIF, WEBP</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-6 space-y-3">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-[#3BB77E] text-white rounded-lg hover:bg-[#2a9d66] transition-colors font-medium">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.about.index') }}"
                            class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview-image');
    const placeholder = document.getElementById('placeholder-icon');
    const placeholderText = document.getElementById('placeholder-text');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
            if (placeholderText) placeholderText.classList.add('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
