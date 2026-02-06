@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
            <p class="text-sm text-gray-600 mt-1">Ringkasan pemasukan dan pengeluaran</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.finance.transactions.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.finance.report') }}" class="flex items-end space-x-4">
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
            <a href="{{ route('admin.finance.report') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
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
            <p class="text-sm opacity-90 mb-1">Total Pemasukan</p>
            <p class="text-3xl font-bold">Rp{{ number_format($totalIncome, 0, ',', '.') }}</p>
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
            <p class="text-sm opacity-90 mb-1">Total Pengeluaran</p>
            <p class="text-3xl font-bold">Rp{{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>

        <div class="bg-gradient-to-br {{ $balance >= 0 ? 'from-blue-500 to-blue-600' : 'from-orange-500 to-orange-600' }} rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium opacity-90">Saldo Periode</span>
            </div>
            <p class="text-sm opacity-90 mb-1">{{ $balance >= 0 ? 'Surplus' : 'Defisit' }}</p>
            <p class="text-3xl font-bold">Rp{{ number_format(abs($balance), 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Income by Category -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Pemasukan per Kategori</h2>
                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                    {{ $incomeByCategory->count() }} kategori
                </span>
            </div>
            @if($incomeByCategory->count() > 0)
            <div class="space-y-3">
                @php $maxIncome = $incomeByCategory->max(); @endphp
                @foreach($incomeByCategory->sortByDesc(fn($amount) => $amount) as $categoryName => $amount)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $categoryName }}</span>
                        <span class="text-sm font-semibold text-green-600">Rp{{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $maxIncome > 0 ? ($amount / $maxIncome * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $totalIncome > 0 ? round($amount / $totalIncome * 100, 1) : 0 }}% dari total pemasukan</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-sm text-gray-500">Tidak ada pemasukan dalam periode ini</p>
            </div>
            @endif
        </div>

        <!-- Expense by Category -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Pengeluaran per Kategori</h2>
                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                    {{ $expenseByCategory->count() }} kategori
                </span>
            </div>
            @if($expenseByCategory->count() > 0)
            <div class="space-y-3">
                @php $maxExpense = $expenseByCategory->max(); @endphp
                @foreach($expenseByCategory->sortByDesc(fn($amount) => $amount) as $categoryName => $amount)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $categoryName }}</span>
                        <span class="text-sm font-semibold text-red-600">Rp{{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $maxExpense > 0 ? ($amount / $maxExpense * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $totalExpense > 0 ? round($amount / $totalExpense * 100, 1) : 0 }}% dari total pengeluaran</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-sm text-gray-500">Tidak ada pengeluaran dalam periode ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Transactions Detail -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Detail Transaksi</h2>
                <span class="text-sm text-gray-600">{{ $transactions->count() }} transaksi</span>
            </div>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $transaction->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $transaction->transaction_number }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="inline-flex items-center">
                                <span class="w-2 h-2 rounded-full {{ $transaction->type === 'income' ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                                {{ $transaction->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            {{ $transaction->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.finance.transactions.show', $transaction) }}"
                               class="text-blue-600 hover:text-blue-900" title="Detail">
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
                        <td colspan="5" class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                            Total (Pemasukan - Pengeluaran):
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $balance >= 0 ? '+' : '-' }}Rp{{ number_format(abs($balance), 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500">Tidak ada transaksi dalam periode ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
