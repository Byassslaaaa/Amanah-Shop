@extends('layouts.admin')

@section('title', 'Laporan Pembayaran Kredit')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Pembayaran Kredit</h1>
            <p class="text-sm text-gray-600 mt-1">Ringkasan pembayaran kredit manual</p>
        </div>
        <a href="{{ route('admin.credit-payments.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Kredit Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeCredits }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Piutang</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">Rp{{ number_format($totalReceivable, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Diterima</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">Rp{{ number_format($totalCollected, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Jatuh Tempo</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">Rp{{ number_format($totalOverdue, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Payment Trend -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tren Pembayaran 6 Bulan Terakhir</h2>
            @if(count($monthlyPayments) > 0)
            <div class="space-y-3">
                @php
                    $maxPayment = collect($monthlyPayments)->max('total');
                @endphp
                @foreach($monthlyPayments as $data)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-600">{{ $data['month'] }}</span>
                        <span class="text-sm font-semibold text-green-600">Rp{{ number_format($data['total'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $maxPayment > 0 ? ($data['total'] / $maxPayment * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-sm text-gray-500">Tidak ada data pembayaran</p>
            </div>
            @endif
        </div>

        <!-- Payment Status Distribution -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Pembayaran</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">Lunas</span>
                    </div>
                    <span class="text-sm font-semibold text-green-600">{{ $statusCounts['paid'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">Sebagian</span>
                    </div>
                    <span class="text-sm font-semibold text-yellow-600">{{ $statusCounts['partial'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">Menunggu</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-600">{{ $statusCounts['pending'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Debtors -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mt-6">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Pelanggan dengan Sisa Hutang Terbesar</h2>
        </div>
        @if(count($topDebtors) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kredit Aktif</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Kredit</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sudah Dibayar</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sisa Hutang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($topDebtors as $debtor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $debtor->customer_name }}</p>
                            @if($debtor->customer_phone)
                            <p class="text-xs text-gray-500">{{ $debtor->customer_phone }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $debtor->credit_count }} kredit
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            Rp{{ number_format($debtor->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">
                            Rp{{ number_format($debtor->total_paid, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600">
                            Rp{{ number_format($debtor->remaining, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <p class="text-sm text-gray-500">Tidak ada data hutang</p>
        </div>
        @endif
    </div>
</div>
@endsection
