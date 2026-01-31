@extends('layouts.admin')

@section('title', 'Detail Pergerakan Stok')

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
            <span class="text-gray-900">Detail</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pergerakan Stok</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Movement Info Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pergerakan</h2>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $movement->type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $movement->type == 'in' ? 'Barang Masuk' : 'Barang Keluar' }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal</p>
                            <p class="text-base text-gray-900">{{ $movement->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Produk</p>
                            <p class="text-base text-gray-900 font-medium">{{ $movement->product->name }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kuantitas</p>
                            <p class="text-2xl font-bold {{ $movement->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                            </p>
                        </div>
                        @if($movement->unit_price)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Harga Satuan</p>
                            <p class="text-xl font-semibold text-gray-900">Rp{{ number_format($movement->unit_price, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($movement->total_price)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Harga</p>
                        <p class="text-xl font-semibold text-gray-900">Rp{{ number_format($movement->total_price, 0, ',', '.') }}</p>
                    </div>
                    @endif

                    @if($movement->supplier)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Supplier</p>
                        <p class="text-base text-gray-900">{{ $movement->supplier->name }}</p>
                    </div>
                    @endif

                    @if($movement->document_number)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nomor Dokumen</p>
                        <p class="text-base text-gray-900">{{ $movement->document_number }}</p>
                    </div>
                    @endif

                    @if($movement->reference_type)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tipe Referensi</p>
                        <p class="text-base text-gray-900 capitalize">{{ $movement->reference_type }}</p>
                    </div>
                    @endif

                    @if($movement->notes)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Catatan</p>
                        <p class="text-base text-gray-900">{{ $movement->notes }}</p>
                    </div>
                    @endif

                    <!-- Stock Snapshot -->
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm font-medium text-gray-700 mb-2">Snapshot Stok</p>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Sebelum</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $movement->stock_before }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-xs text-blue-600 mb-1">Perubahan</p>
                                <p class="text-lg font-semibold {{ $movement->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Sesudah</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $movement->stock_after }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Metadata Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1">Dicatat oleh</p>
                        <p class="text-gray-900 font-medium">{{ $movement->creator->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Tanggal dicatat</p>
                        <p class="text-gray-900">{{ $movement->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.products.edit', $movement->product) }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        Lihat Produk
                    </a>
                    @if($movement->supplier)
                    <a href="{{ route('admin.inventory.suppliers.show', $movement->supplier) }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        Lihat Supplier
                    </a>
                    @endif
                    <a href="{{ route('admin.inventory.movements.index', ['product_id' => $movement->product_id]) }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        Riwayat Produk Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
