@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Edit Kategori</h1>
        <p class="text-gray-600 mt-2">Edit informasi kategori "{{ $category->name }}"</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.categories.show', $category) }}" 
           class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors">
            Lihat Detail
        </a>
        <a href="{{ route('admin.categories.index') }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="bg-white rounded-lg shadow p-8">
        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori *
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.categories.show', $category) }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            Batal
        </a>
        <button type="submit" 
                class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Update Kategori
        </button>
    </div>
</form>

<!-- Delete Button (separate form) -->
<div class="mt-6">
    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
          onsubmit="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada produk yang menggunakan kategori ini.');"
          class="inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors">
            Hapus Kategori
        </button>
    </form>
</div>
@endsection