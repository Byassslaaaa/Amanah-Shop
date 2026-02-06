@extends('layouts.admin')

@section('title', 'Laporan Pergerakan Stok')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Pergerakan Stok</h1>
            <p class="text-sm text-gray-600 mt-1">Ringkasan barang masuk dan keluar</p>
        </div>
        <a href="{{ route('admin.inventory.movements.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Filter -->
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium opacity-90">{{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </div>
            <p class="text-sm opacity-90 mb-1">Total Barang Masuk</p>
            <p class="text-3xl font-bold">{{ number_format($totalStockIn, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">unit</p>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium opacity-90">{{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </div>
            <p class="text-sm opacity-90 mb-1">Total Barang Keluar</p>
            <p class="text-3xl font-bold">{{ number_format($totalStockOut, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">unit</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium opacity-90">Pergerakan Bersih</span>
            </div>
            <p class="text-sm opacity-90 mb-1">{{ ($totalStockIn - $totalStockOut) >= 0 ? 'Stok Bertambah' : 'Stok Berkurang' }}</p>
            <p class="text-3xl font-bold">{{ ($totalStockIn - $totalStockOut) >= 0 ? '+' : '' }}{{ number_format($totalStockIn - $totalStockOut, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">unit</p>
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
                            <p class="text-xs text-gray-500 mt-1">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-lg font-bold text-yellow-600">{{ $product->stock }}</p>
                            <p class="text-xs text-gray-500">unit</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-yellow-500 h-1.5 rounded-full" style="width: {{ min(($product->stock / 10 * 100), 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-green-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
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
                            <p class="text-xs text-gray-500 mt-1">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-lg font-bold text-red-600">0</p>
                            <p class="text-xs text-gray-500">unit</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-green-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">Tidak ada produk yang habis stok</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Movements -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Pergerakan Stok dalam Periode Ini</h2>
                <span class="text-sm text-gray-600">{{ $movements->count() }} pergerakan</span>
            </div>
        </div>
        @if($movements->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk/Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kuantitas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dicatat Oleh</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($movements as $movement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $movement->created_at->format('d M Y') }}
                            <p class="text-xs text-gray-400">{{ $movement->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $movement->type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $movement->type == 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $movement->display_name }}
                            @if($movement->isBookkeepingOnly())
                                <span class="ml-1 px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Pencatatan</span>
                            @endif
                            @if($movement->document_number)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $movement->document_number }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $movement->supplier ? $movement->supplier->name : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold {{ $movement->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            @if($movement->unit_price)
                                Rp{{ number_format($movement->unit_price, 0, ',', '.') }}
                                @if($movement->total_price)
                                    <p class="text-xs text-gray-500">Total: Rp{{ number_format($movement->total_price, 0, ',', '.') }}</p>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $movement->creator ? $movement->creator->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.inventory.movements.show', $movement) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                            Total Pergerakan (Masuk - Keluar):
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold {{ ($totalStockIn - $totalStockOut) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($totalStockIn - $totalStockOut) >= 0 ? '+' : '' }}{{ number_format($totalStockIn - $totalStockOut, 0, ',', '.') }}
                            </span>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <p class="text-gray-500">Tidak ada pergerakan stok dalam periode ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
