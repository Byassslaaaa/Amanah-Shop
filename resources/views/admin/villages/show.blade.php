@extends('layouts.admin')

@section('title', 'Detail Desa - ' . $village->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.villages.index') }}"
               class="text-gray-600 hover:text-gray-800 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $village->name }}</h1>
                <p class="text-gray-600 mt-1">{{ $village->district }}, {{ $village->city }}, {{ $village->province }}</p>
            </div>
        </div>
        <a href="{{ route('admin.villages.edit', $village) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Desa
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Produk</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Produk Aktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $activeProducts }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Admin Desa</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalAdmins }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Village Info -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Desa</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Deskripsi</h3>
                <p class="text-gray-700">{{ $village->description ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Alamat</h3>
                <p class="text-gray-700">{{ $village->address ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Telepon</h3>
                <p class="text-gray-700">{{ $village->phone ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Email</h3>
                <p class="text-gray-700">{{ $village->email ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-2">WhatsApp</h3>
                <p class="text-gray-700">{{ $village->whatsapp ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Status</h3>
                @if($village->status === 'active')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Aktif
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Nonaktif
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Admin List -->
    @if($village->admins->count() > 0)
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Admin Desa</h2>
            <div class="space-y-3">
                @foreach($village->admins as $admin)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $admin->name }}</p>
                                <p class="text-sm text-gray-600">{{ $admin->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Edit
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Products List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Produk Desa</h2>
            <a href="{{ route('admin.products.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition duration-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </a>
        </div>

        @if($village->products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($village->products as $product)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500">{{ Str::limit($product->description, 50) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ $product->category->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ $product->stock }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($product->status === 'active')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium">Belum ada produk</p>
                <p class="text-gray-400 text-sm mt-1">Desa ini belum memiliki produk</p>
            </div>
        @endif
    </div>
</div>
@endsection
