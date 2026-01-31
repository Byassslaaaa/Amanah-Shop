@extends('layouts.admin')

@section('title', 'Barang Masuk')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.inventory.movements.index') }}" class="hover:text-gray-900">Pergerakan Stok</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">Barang Masuk</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Catat Barang Masuk</h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.inventory.movements.stock-in') }}" method="POST">
            @csrf

            <!-- Product -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk <span class="text-red-500">*</span></label>
                <select name="product_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('product_id') border-red-500 @enderror" required>
                    <option value="">Pilih Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (Stok: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Supplier -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier <span class="text-red-500">*</span></label>
                <select name="supplier_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-500 @enderror" required>
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    Belum ada supplier?
                    <a href="{{ route('admin.inventory.suppliers.create') }}" target="_blank" class="text-blue-600 hover:underline">Tambah supplier baru</a>
                </p>
            </div>

            <!-- Quantity -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kuantitas <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" value="{{ old('quantity') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror"
                    placeholder="0" min="1" required>
                @error('quantity')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Unit Price -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                    <input type="number" name="unit_price" value="{{ old('unit_price') }}"
                        class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('unit_price') border-red-500 @enderror"
                        placeholder="0" step="0.01" min="0" required>
                </div>
                @error('unit_price')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Document Number -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Dokumen</label>
                <input type="text" name="document_number" value="{{ old('document_number') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('document_number') border-red-500 @enderror"
                    placeholder="Contoh: PO-2024-001, INV-001">
                @error('document_number')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                    placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                    Catat Barang Masuk
                </button>
                <a href="{{ route('admin.inventory.movements.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
