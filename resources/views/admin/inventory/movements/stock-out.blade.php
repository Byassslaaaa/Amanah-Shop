@extends('layouts.admin')

@section('title', 'Barang Keluar')

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
            <span class="text-gray-900">Barang Keluar</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Catat Barang Keluar</h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.inventory.movements.stock-out') }}" method="POST">
            @csrf

            <!-- Product -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk <span class="text-red-500">*</span></label>
                <select name="product_id" id="productSelect" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('product_id') border-red-500 @enderror" required>
                    <option value="">Pilih Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (Stok: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p id="stockInfo" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <!-- Quantity -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kuantitas <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" id="quantityInput" value="{{ old('quantity') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror"
                    placeholder="0" min="1" required>
                @error('quantity')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Reference Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Referensi</label>
                <select name="reference_type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('reference_type') border-red-500 @enderror">
                    <option value="">Pilih Tipe</option>
                    <option value="sale" {{ old('reference_type') == 'sale' ? 'selected' : '' }}>Penjualan</option>
                    <option value="damage" {{ old('reference_type') == 'damage' ? 'selected' : '' }}>Rusak</option>
                    <option value="return" {{ old('reference_type') == 'return' ? 'selected' : '' }}>Retur</option>
                    <option value="adjustment" {{ old('reference_type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                    <option value="other" {{ old('reference_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('reference_type')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan <span class="text-red-500">*</span></label>
                <textarea name="notes" rows="4"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                    placeholder="Jelaskan alasan barang keluar..." required>{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    Catat Barang Keluar
                </button>
                <a href="{{ route('admin.inventory.movements.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const stockInfo = document.getElementById('stockInfo');

    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');

        if (stock) {
            stockInfo.textContent = `Stok tersedia: ${stock} unit`;
            quantityInput.max = stock;
        } else {
            stockInfo.textContent = '';
            quantityInput.removeAttribute('max');
        }
    });
});
</script>
@endsection
