@extends('layouts.admin')

@section('title', 'Edit Kategori Transaksi')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.finance.categories.index') }}" class="hover:text-gray-900">Kategori Transaksi</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">Edit Kategori</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Kategori Transaksi</h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.finance.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                <select name="type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror" required>
                    <option value="">Pilih Tipe</option>
                    <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                @error('type')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Penjualan Produk, Gaji Karyawan, dll." required>
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Masukkan deskripsi kategori (opsional)...">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info about transactions -->
            @if($category->transactions->count() > 0)
            <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Kategori ini memiliki {{ $category->transactions->count() }} transaksi</p>
                        <p class="text-xs text-blue-700 mt-1">Perubahan kategori akan mempengaruhi semua transaksi yang menggunakan kategori ini.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Update Kategori
                </button>
                <a href="{{ route('admin.finance.categories.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
