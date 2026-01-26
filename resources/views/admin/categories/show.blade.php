@extends('layouts.admin')

@section('title', 'Detail Kategori')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
        <p class="text-gray-600 mt-2">Detail kategori dan produk terkait</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.categories.edit', $category) }}" 
           class="bg-yellow-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
            Edit Kategori
        </a>
        <a href="{{ route('admin.categories.index') }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            Kembali
        </a>
    </div>
</div>

<!-- Category Info -->
<div class="bg-white rounded-lg shadow p-8 mb-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Kategori</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
            <p class="text-lg text-gray-900">{{ $category->name }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Produk</label>
            <p class="text-lg text-gray-900">{{ $products->count() }} produk</p>
        </div>
        @if($category->description)
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <p class="text-gray-900">{{ $category->description }}</p>
        </div>
        @endif
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat</label>
            <p class="text-gray-900">{{ $category->created_at->format('d M Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diupdate</label>
            <p class="text-gray-900">{{ $category->updated_at->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Products in this Category -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Produk dalam Kategori Ini</h2>
        <p class="text-gray-600 mt-1">Daftar semua produk yang menggunakan kategori ini</p>
    </div>
    
    @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Produk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stok
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($product->images)
                                    <img src="{{ $product->getImageDataUri() }}" alt="{{ $product->name }}" 
                                         class="h-10 w-10 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $product->stock }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="text-yellow-600 hover:text-yellow-900">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada produk</h3>
            <p class="mt-1 text-sm text-gray-500">Kategori ini belum memiliki produk apapun.</p>
            <div class="mt-6">
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Tambah Produk Baru
                </a>
            </div>
        </div>
    @endif
</div>
@endsection