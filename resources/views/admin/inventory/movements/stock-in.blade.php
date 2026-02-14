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
        <p class="text-sm text-gray-600 mt-1">Catat barang masuk untuk manajemen website atau pencatatan pembukuan saja</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl"
         x-data="{
            displayOnWeb: 'yes',
            productSelection: 'existing'
         }">
        <form action="{{ route('admin.inventory.movements.stock-in') }}" method="POST">
            @csrf

            <!-- Display on Web Toggle -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-lg">
                <label class="block text-sm font-medium text-gray-900 mb-3">Tampilkan di Website? <span class="text-red-500">*</span></label>
                <div class="flex items-center space-x-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="display_on_web" value="yes"
                               x-model="displayOnWeb"
                               class="w-4 h-4 text-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-sm text-gray-900">Ya, tampilkan sebagai produk di website</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="display_on_web" value="no"
                               x-model="displayOnWeb"
                               class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-900">Tidak, pencatatan pembukuan saja</span>
                    </label>
                </div>
                <p class="text-xs text-gray-600 mt-2">
                    Pilih "Ya" jika barang ini akan dijual di website. Pilih "Tidak" untuk mencatat transaksi di luar website.
                </p>
            </div>

            <!-- SCENARIO 1: Bookkeeping Only (No Web Display) -->
            <div x-show="displayOnWeb === 'no'" x-cloak>
                <!-- Item Name -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('item_name') border-red-500 @enderror"
                        placeholder="Contoh: Perlengkapan kantor"
                        x-bind:required="displayOnWeb === 'no'">
                    @error('item_name')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SCENARIO 2 & 3: Display on Web -->
            <div x-show="displayOnWeb === 'yes'" x-cloak>
                <!-- Product Selection: New or Existing -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Produk <span class="text-red-500">*</span></label>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="product_selection" value="existing"
                                   x-model="productSelection"
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500" checked>
                            <span class="ml-2 text-sm text-gray-700">Produk yang sudah ada</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="product_selection" value="new"
                                   x-model="productSelection"
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Produk baru</span>
                        </label>
                    </div>
                </div>

                <!-- Existing Product Selection -->
                <div x-show="productSelection === 'existing'" x-cloak class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Produk <span class="text-red-500">*</span></label>
                    <select name="product_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('product_id') border-red-500 @enderror"
                            x-bind:required="displayOnWeb === 'yes' && productSelection === 'existing'">
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

                <!-- New Product Fields -->
                <div x-show="productSelection === 'new'" x-cloak>
                    <!-- Product Name -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Sofa Minimalis Modern"
                            x-bind:required="displayOnWeb === 'yes' && productSelection === 'new'">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi produk untuk ditampilkan di website..."
                            x-bind:required="displayOnWeb === 'yes' && productSelection === 'new'">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror"
                                x-bind:required="displayOnWeb === 'yes' && productSelection === 'new'">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}"
                                class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror"
                                placeholder="0" step="0.01" min="0"
                                x-bind:required="displayOnWeb === 'yes' && productSelection === 'new'">
                        </div>
                        @error('price')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Harga jual ke pelanggan di website</p>
                    </div>
                </div>
            </div>

            <!-- Common Fields (All Scenarios) -->
            <div class="pt-4 border-t border-gray-200">
                <!-- Supplier -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier <span class="text-gray-400 text-xs">(Opsional)</span></label>
                    <select name="supplier_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-500 @enderror">
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

                <!-- Unit Price (Purchase) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Beli Satuan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                        <input type="number" name="unit_price" value="{{ old('unit_price') }}"
                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('unit_price') border-red-500 @enderror"
                            placeholder="0" step="0.01" min="0" required>
                    </div>
                    @error('unit_price')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Harga pembelian dari supplier</p>
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

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
