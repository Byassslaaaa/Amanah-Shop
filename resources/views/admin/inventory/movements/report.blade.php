@extends('layouts.admin')

@section('title', 'Laporan Inventori')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Inventori</h1>
        <p class="text-sm text-gray-600 mt-1">Ringkasan pergerakan stok dan status inventori</p>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.inventory.movements.report') }}" class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                Tampilkan
            </button>
            <a href="{{ route('admin.inventory.movements.report') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Stok Masuk</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalStockIn }}</p>
                    <p class="text-xs text-gray-500 mt-1">Unit produk</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Stok Keluar</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $totalStockOut }}</p>
                    <p class="text-xs text-gray-500 mt-1">Unit produk</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Low Stock Products -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-yellow-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Stok Menipis</h2>
                        <p class="text-sm text-gray-600">{{ $lowStockProducts->count() }} produk</p>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                @forelse($lowStockProducts as $product)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $product->category->name }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-lg font-bold text-yellow-600">{{ $product->stock }}</p>
                            <p class="text-xs text-gray-500">unit</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500">Tidak ada produk dengan stok menipis</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Out of Stock Products -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-red-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Stok Habis</h2>
                        <p class="text-sm text-gray-600">{{ $outOfStockProducts->count() }} produk</p>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                @forelse($outOfStockProducts as $product)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $product->category->name }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-lg font-bold text-red-600">0</p>
                            <p class="text-xs text-gray-500">unit</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500">Tidak ada produk yang habis stok</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Movements -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Pergerakan Stok dalam Periode Ini</h2>
        </div>
        @if($movements->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kuantitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($movements as $movement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $movement->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $movement->type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $movement->type == 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $movement->product->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $movement->supplier ? $movement->supplier->name : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold {{ $movement->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $movement->creator->name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <p class="text-gray-500">Tidak ada pergerakan dalam periode ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
