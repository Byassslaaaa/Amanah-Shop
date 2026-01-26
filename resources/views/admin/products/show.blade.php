@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
        <p class="text-gray-600 mt-2">Detail lengkap produk</p>
    </div>
    <div class="space-x-3">
        <a href="{{ route('admin.products.edit', $product) }}" 
           class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Edit Produk
        </a>
        <a href="{{ route('admin.products.index') }}" 
           class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Product Images -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($product->images && count($product->images) > 0)
                <div class="aspect-w-16 aspect-h-9">
                    <img src="{{ Storage::url($product->images[0]) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-96 object-cover"
                         id="mainImage">
                </div>
                
                @if(count($product->images) > 1)
                    <div class="p-4 bg-gray-50 border-t">
                        <div class="flex space-x-3 overflow-x-auto">
                            @foreach($product->images as $index => $image)
                                <button onclick="changeMainImage('{{ Storage::url($image) }}')"
                                        class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-green-500' : 'border-gray-200' }} hover:border-green-500 transition-colors">
                                    <img src="{{ Storage::url($image) }}" 
                                         alt="Thumbnail {{ $index + 1 }}" 
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="h-96 bg-gray-200 flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>
        
        <!-- Product Description -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Produk</h2>
            <div class="prose prose-green max-w-none">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Product Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Produk</h2>
            
            <div class="space-y-4">
                <!-- Price -->
                <div class="flex justify-between">
                    <span class="text-gray-600">Harga:</span>
                    <span class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>
                
                <!-- Stock -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Stok:</span>
                    <div class="text-right">
                        <span class="text-lg font-semibold {{ $product->stock <= 10 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $product->stock }}
                        </span>
                        @if($product->stock <= 10)
                            <p class="text-xs text-red-500">Stok menipis!</p>
                        @endif
                    </div>
                </div>
                
                <!-- Category -->
                <div class="flex justify-between">
                    <span class="text-gray-600">Kategori:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $product->category->name ?? 'No Category' }}
                    </span>
                </div>
                
                <!-- Status -->
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
                
                <!-- WhatsApp -->
                @if($product->whatsapp_number)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">WhatsApp:</span>
                        <div class="text-right">
                            <p class="font-medium">{{ $product->whatsapp_number }}</p>
                            <a href="https://wa.me/{{ $product->whatsapp_number }}" 
                               target="_blank"
                               class="text-green-600 hover:text-green-800 text-sm">
                                Buka Chat
                            </a>
                        </div>
                    </div>
                @endif
                
                <!-- Created/Updated -->
                <div class="pt-4 border-t border-gray-200 text-sm text-gray-500">
                    <p>Dibuat: {{ $product->created_at->format('d M Y, H:i') }}</p>
                    @if($product->updated_at != $product->created_at)
                        <p>Diupdate: {{ $product->updated_at->format('d M Y, H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
            
            <div class="space-y-3">
                <a href="{{ route('admin.products.edit', $product) }}" 
                   class="w-full bg-green-600 text-white py-2 px-4 rounded-lg text-center font-medium hover:bg-green-700 transition-colors block">
                    Edit Produk
                </a>
                
                <a href="{{ route('products.show', $product->slug) }}" 
                   target="_blank"
                   class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-center font-medium hover:bg-blue-700 transition-colors block">
                    Lihat di Website
                </a>
                
                @if($product->whatsapp_number)
                    <a href="https://wa.me/{{ $product->whatsapp_number }}?text=Halo, saya tertarik dengan produk {{ $product->name }}" 
                       target="_blank"
                       class="w-full bg-green-500 text-white py-2 px-4 rounded-lg text-center font-medium hover:bg-green-600 transition-colors block">
                        Chat WhatsApp
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Danger Zone -->
        <div class="bg-red-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-2">Zona Bahaya</h3>
            <p class="text-red-700 text-sm mb-4">
                Menghapus produk akan menghapus semua data terkait. Aksi ini tidak dapat dibatalkan.
            </p>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                  onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name }}? Aksi ini tidak dapat dibatalkan!')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                    Hapus Produk
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    
    // Update border colors for thumbnails
    document.querySelectorAll('.flex-shrink-0 button').forEach(btn => {
        btn.classList.remove('border-green-500');
        btn.classList.add('border-gray-200');
    });
    
    // Add border to clicked thumbnail
    event.target.closest('button').classList.add('border-green-500');
    event.target.closest('button').classList.remove('border-gray-200');
}
</script>
@endsection