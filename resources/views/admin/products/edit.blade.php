@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Edit Produk</h1>
        <p class="text-gray-600 mt-2">Edit informasi produk: {{ $product->name }}</p>
    </div>
    <div class="space-x-3">
        <a href="{{ route('admin.products.show', $product) }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
            Lihat Produk
        </a>
        <a href="{{ route('admin.products.index') }}" 
           class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
            Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    
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
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
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
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Current Images -->
            @if($product->images && count($product->images) > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Foto Saat Ini</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($product->images as $index => $image)
                            <div class="relative group">
                                <img src="{{ $product->getImageDataUri($index) }}" alt="Product Image" 
                                     class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="flex items-center justify-center h-full">
                                        <label class="bg-red-600 text-white px-3 py-1 rounded text-sm cursor-pointer hover:bg-red-700">
                                            <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="sr-only">
                                            Hapus
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Centang foto yang ingin dihapus dan klik Simpan untuk menghapusnya.
                    </p>
                </div>
            @endif
            
            <!-- Add New Images -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Tambah Foto Baru</h2>
                
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Foto Tambahan (Max: 2MB per foto)
                    </label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('images.*') border-red-500 @enderror">
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per foto. Foto baru akan ditambahkan ke foto yang sudah ada.
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
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0"
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
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                               placeholder="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('stock') border-red-500 @enderror">
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($product->stock <= 10)
                            <p class="mt-1 text-sm text-orange-600">⚠️ Stok produk ini sudah menipis!</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Category & Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Kategori & Status</h2>
                
                <div class="space-y-4">
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Produk *
                        </label>
                        <select id="type" name="type" required onchange="filterCategories()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('type') border-red-500 @enderror">
                            <option value="">Pilih Jenis Produk</option>
                            <option value="barang" {{ old('type', $product->type) == 'barang' ? 'selected' : '' }}>
                                Produk Barang
                            </option>
                            <option value="jasa" {{ old('type', $product->type) == 'jasa' ? 'selected' : '' }}>
                                Produk Jasa
                            </option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Pilih "Barang" untuk produk fisik atau "Jasa" untuk layanan
                        </p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori *
                        </label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-type="{{ $category->type }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
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
                    <input type="text" id="whatsapp_number" name="whatsapp_number" 
                           value="{{ old('whatsapp_number', $product->whatsapp_number) }}"
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
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Delete Product -->
<div class="mt-8 bg-red-50 rounded-lg p-6">
    <h3 class="text-lg font-semibold text-red-900 mb-2">Zona Bahaya</h3>
    <p class="text-red-700 mb-4">
        Menghapus produk akan menghapus semua data terkait termasuk foto. Aksi ini tidak dapat dibatalkan.
    </p>
    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
          class="inline-block" 
          onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name }}? Aksi ini tidak dapat dibatalkan!')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
            Hapus Produk
        </button>
    </form>
</div>

<script>
// Store original category options globally
let originalCategoryOptions = [];

function filterCategories() {
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');
    const selectedType = typeSelect.value;
    const currentCategoryId = '{{ old('category_id', $product->category_id) }}';
    
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
            const newOption = option.cloneNode(true);
            // Preserve selection if it matches
            if (newOption.value === currentCategoryId) {
                newOption.selected = true;
            }
            categorySelect.appendChild(newOption);
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