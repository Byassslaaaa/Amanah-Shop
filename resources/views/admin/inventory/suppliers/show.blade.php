@extends('layouts.admin')

@section('title', 'Detail Supplier')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('admin.inventory.suppliers.index') }}" class="hover:text-gray-900">Daftar Supplier</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900">Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Supplier</h1>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.inventory.suppliers.edit', $supplier) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            @if($supplier->inventoryMovements->count() == 0)
            <form action="{{ route('admin.inventory.suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Supplier Info Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Supplier</h2>
                    @if($supplier->is_active)
                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Aktif</span>
                    @else
                    <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Nonaktif</span>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kode Supplier</p>
                            <p class="text-base font-medium text-gray-900">{{ $supplier->code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nama Supplier</p>
                            <p class="text-base font-medium text-gray-900">{{ $supplier->name }}</p>
                        </div>
                    </div>

                    @if($supplier->contact_person)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kontak Person</p>
                        <p class="text-base text-gray-900">{{ $supplier->contact_person }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        @if($supplier->phone)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Telepon</p>
                            <p class="text-base text-gray-900">{{ $supplier->phone }}</p>
                        </div>
                        @endif
                        @if($supplier->email)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email</p>
                            <p class="text-base text-gray-900">{{ $supplier->email }}</p>
                        </div>
                        @endif
                    </div>

                    @if($supplier->address)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Alamat</p>
                        <p class="text-base text-gray-900">{{ $supplier->address }}</p>
                    </div>
                    @endif

                    @if($supplier->notes)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Catatan</p>
                        <p class="text-base text-gray-900">{{ $supplier->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Purchase History -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Riwayat Pembelian</h2>
                </div>
                @if($supplier->inventoryMovements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kuantitas</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($supplier->inventoryMovements as $movement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $movement->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $movement->product->name }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-green-600">
                                    +{{ $movement->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    @if($movement->total_price)
                                        Rp{{ number_format($movement->total_price, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada riwayat pembelian</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Pembelian</p>
                        <p class="text-2xl font-bold text-gray-900">Rp{{ number_format($totalPurchases, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transaksi Terakhir</p>
                        <p class="text-base text-gray-900">
                            @if($lastPurchase)
                                {{ $lastPurchase->created_at->diffForHumans() }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
                        <p class="text-base font-semibold text-gray-900">{{ $supplier->inventoryMovements->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.inventory.movements.stock-in-form') }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                        Buat Pembelian Baru
                    </a>
                    <a href="{{ route('admin.inventory.movements.index', ['supplier_id' => $supplier->id]) }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        Lihat Riwayat Lengkap
                    </a>
                    <form action="{{ route('admin.inventory.suppliers.toggle-status', $supplier) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-center {{ $supplier->is_active ? 'text-red-600 bg-red-50 hover:bg-red-100' : 'text-green-600 bg-green-50 hover:bg-green-100' }} rounded-lg transition-colors">
                            {{ $supplier->is_active ? 'Nonaktifkan Supplier' : 'Aktifkan Supplier' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
