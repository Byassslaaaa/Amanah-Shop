@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Tambah Kategori</h1>
        <p class="text-gray-600 mt-2">Tambah kategori produk baru</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" 
       class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
        Kembali
    </a>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
    @csrf
    
    <div class="bg-white rounded-lg shadow p-8">
        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori *
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.categories.index') }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            Batal
        </a>
        <button type="submit" 
                class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Simpan Kategori
        </button>
    </div>
</form>
@endsection