@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
            <p class="text-sm text-gray-600 mt-1">Edit informasi kategori "{{ $category->name }}"</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.categories.show', $category) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Lihat Detail
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori *
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea id="description" name="description" rows="4"
                          placeholder="Deskripsi kategori (opsional)..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.categories.show', $category) }}"
           class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
            Batal
        </a>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
            Update Kategori
        </button>
    </div>
</form>

    <!-- Delete Button (separate form) -->
    <div class="mt-6 bg-red-50 border border-red-100 rounded-xl p-6">
        <h3 class="text-base font-semibold text-red-900 mb-2">Zona Bahaya</h3>
        <p class="text-sm text-red-700 mb-4">
            Menghapus kategori akan mempengaruhi produk yang menggunakan kategori ini. Aksi ini tidak dapat dibatalkan.
        </p>
        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
              onsubmit="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada produk yang menggunakan kategori ini.');"
              class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                Hapus Kategori
            </button>
        </form>
    </div>
</div>
@endsection