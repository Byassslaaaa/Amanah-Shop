@extends('layouts.admin')

@section('title', 'Tambah Banner')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Banner</h1>
            <p class="text-sm text-gray-600 mt-1">Buat banner baru untuk ditampilkan di halaman utama</p>
        </div>
        <a href="{{ route('admin.banners.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

<form action="{{ route('admin.banners.store') }}" method="POST" class="space-y-6" x-data="{
    imagePreview: null,
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
                document.getElementById('image').value = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Informasi Banner</h2>

                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Banner *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               placeholder="Contoh: Perabotan & Kebutuhan Rumah Tangga"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subtitle -->
                    <div>
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                            Subtitle
                        </label>
                        <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle') }}"
                               placeholder="Contoh: Belanja kebutuhan rumah tangga dengan harga terjangkau"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subtitle') border-red-500 @enderror">
                        @error('subtitle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button Text -->
                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Teks Tombol
                        </label>
                        <input type="text" id="button_text" name="button_text" value="{{ old('button_text') }}"
                               placeholder="Contoh: Belanja Sekarang"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('button_text') border-red-500 @enderror">
                        @error('button_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button Link -->
                    <div>
                        <label for="button_link" class="block text-sm font-medium text-gray-700 mb-2">
                            Link Tombol
                        </label>
                        <input type="text" id="button_link" name="button_link" value="{{ old('button_link') }}"
                               placeholder="Contoh: /products"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('button_link') border-red-500 @enderror">
                        @error('button_link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            URL tujuan ketika tombol diklik (opsional)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Banner Image -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Gambar Banner</h2>

                <div>
                    <label for="image_upload" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Gambar Banner *
                    </label>
                    <input type="file" id="image_upload" accept="image/*" @change="previewImage"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    <input type="hidden" id="image" name="image" value="{{ old('image') }}">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Format: JPEG, PNG, JPG. Rekomendasi ukuran: 1920x600px
                    </p>

                    <!-- Image Preview -->
                    <div x-show="imagePreview" class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <img :src="imagePreview" alt="Preview" class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Settings -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Pengaturan</h2>

                <div class="space-y-4">
                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan Tampilan *
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0" required
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('order') border-red-500 @enderror">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Angka lebih kecil akan ditampilkan lebih dulu
                        </p>
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Banner Aktif</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500 ml-7">
                            Banner akan ditampilkan di halaman utama jika dicentang
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <button type="submit"
                        class="w-full px-4 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Simpan Banner
                </button>
            </div>
        </div>
    </div>
</form>
</div>
@endsection
