@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Tambah Produk</h1>
        <p class="text-gray-600 mt-2">Tambahkan produk baru ke marketplace</p>
    </div>
    <a href="{{ route('admin.products.index') }}" 
       class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
        Kembali
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Produk *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Beras Premium 5kg"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi *
                        </label>
                        <textarea id="description" name="description" rows="5" required
                                  placeholder="Jelaskan detail produk, manfaat, dan spesifikasi..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Product Images -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Foto Produk</h2>
                
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Foto (Max: 2MB per foto)
                    </label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('images.*') border-red-500 @enderror">
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per foto. Anda dapat memilih beberapa foto sekaligus.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pricing & Stock -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Harga & Stok</h2>
                
                <div class="space-y-4">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0"
                                   placeholder="50000"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            Stok *
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock') }}" required min="0"
                               placeholder="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('stock') border-red-500 @enderror">
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Category & Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Kategori & Status</h2>
                
                <div class="space-y-4">
                    {{-- Type field removed - No barang/jasa distinction --}}

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori *
                        </label>
                        <select id="category_id" name="category_id" required disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Pilih jenis produk terlebih dahulu</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-type="{{ $category->type }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status *
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Kontak Penjual</h2>
                
                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor WhatsApp
                    </label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                           placeholder="628123456789 (tanpa tanda +)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('whatsapp_number') border-red-500 @enderror">
                    @error('whatsapp_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Format: 628123456789 (tanpa tanda + dan spasi)
                    </p>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="bg-white rounded-lg shadow p-6">
                <button type="submit" 
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Simpan Produk
                </button>
            </div>
        </div>
    </div>
</form>

<script>
// Store original category options globally
let originalCategoryOptions = [];

function filterCategories() {
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');
    const selectedType = typeSelect.value;
    
    // Store original options on first run
    if (originalCategoryOptions.length === 0) {
        originalCategoryOptions = Array.from(categorySelect.querySelectorAll('option[data-type]'));
    }
    
    // Reset and disable category select if no type selected
    if (!selectedType) {
        categorySelect.innerHTML = '<option value="">Pilih jenis produk terlebih dahulu</option>';
        categorySelect.disabled = true;
        return;
    }
    
    // Enable category select and reset options
    categorySelect.disabled = false;
    categorySelect.innerHTML = '<option value="">Pilih Kategori</option>';
    
    // Add options that match selected type
    originalCategoryOptions.forEach(option => {
        if (option.dataset.type === selectedType) {
            categorySelect.appendChild(option.cloneNode(true));
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Store original options before any filtering
    const categorySelect = document.getElementById('category_id');
    originalCategoryOptions = Array.from(categorySelect.querySelectorAll('option[data-type]'));
    
    filterCategories();
});
</script>
@endsection