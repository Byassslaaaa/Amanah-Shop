@extends('layouts.admin')

@section('title', 'Detail Transaksi')

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
                <a href="{{ route('admin.finance.transactions.index') }}" class="hover:text-gray-900">Transaksi Keuangan</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900">Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi</h1>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.finance.transactions.edit', $transaction) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.finance.transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Info Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h2>

                <div class="space-y-4">
                    <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">No. Transaksi</p>
                            <p class="text-base font-medium text-gray-900">{{ $transaction->transaction_number }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $transaction->type == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Transaksi</p>
                            <p class="text-base text-gray-900">{{ $transaction->transaction_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kategori</p>
                            <p class="text-base text-gray-900">{{ $transaction->category->name }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jumlah</p>
                        <p class="text-2xl font-bold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                            Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Deskripsi</p>
                        <p class="text-base text-gray-900">{{ $transaction->description }}</p>
                    </div>

                    @if($transaction->notes)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Catatan</p>
                        <p class="text-base text-gray-900">{{ $transaction->notes }}</p>
                    </div>
                    @endif

                    @if($transaction->attachment)
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Lampiran</p>
                        <a href="{{ Storage::url($transaction->attachment) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            Lihat Lampiran
                        </a>
                    </div>
                    @endif
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
                        <p class="text-gray-600 mb-1">Dibuat oleh</p>
                        <p class="text-gray-900 font-medium">{{ $transaction->creator->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Tanggal dibuat</p>
                        <p class="text-gray-900">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($transaction->updated_at != $transaction->created_at)
                    <div>
                        <p class="text-gray-600 mb-1">Terakhir diupdate</p>
                        <p class="text-gray-900">{{ $transaction->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.finance.transactions.index', ['category_id' => $transaction->category_id]) }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        Lihat Transaksi Serupa
                    </a>
                    <a href="{{ route('admin.finance.categories.index') }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        Kelola Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
