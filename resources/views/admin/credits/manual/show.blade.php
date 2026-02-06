@extends('layouts.admin')

@section('title', 'Detail Kredit')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('admin.credits.manual.index') }}" class="hover:text-blue-600">Kelola Kredit</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-gray-900 font-medium">{{ $credit->credit_number }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kredit</h1>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap kredit {{ $credit->credit_number }}</p>
        </div>
        <div class="flex items-center space-x-2">
            @if($credit->status == 'active' || $credit->status == 'overdue')
            <a href="{{ route('admin.credits.manual.payment-form', $credit) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Catat Pembayaran
            </a>
            @endif
            <a href="{{ route('admin.credits.manual.edit', $credit) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.credits.manual.destroy', $credit) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kredit ini? Data yang sudah dihapus tidak dapat dikembalikan.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 hover:bg-red-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Info Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Info Pelanggan -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                <svg class="w-4 h-4 inline mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Info Pelanggan
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Nama Pelanggan</p>
                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->customer_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Nomor Telepon</p>
                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->customer_phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Alamat</p>
                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->customer_address ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Info Kredit -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                <svg class="w-4 h-4 inline mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Info Kredit
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">No. Kredit</p>
                    <p class="text-sm font-semibold text-blue-600 mt-0.5">{{ $credit->credit_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Keterangan</p>
                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->description ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Mulai</p>
                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Berakhir</p>
                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->end_date ? $credit->end_date->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <div class="mt-1">
                        @switch($credit->status)
                            @case('active')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Aktif</span>
                                @break
                            @case('completed')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Selesai</span>
                                @break
                            @case('overdue')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Jatuh Tempo</span>
                                @break
                            @case('cancelled')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Dibatalkan</span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Keuangan -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                <svg class="w-4 h-4 inline mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Info Keuangan
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Jumlah Pinjaman</span>
                    <span class="text-sm font-medium text-gray-900">Rp{{ number_format($credit->loan_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Uang Muka (DP)</span>
                    <span class="text-sm font-medium text-gray-900">Rp{{ number_format($credit->down_payment, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Pokok Pinjaman</span>
                    <span class="text-sm font-medium text-gray-900">Rp{{ number_format($credit->principal_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Bunga</span>
                    <span class="text-sm font-medium text-gray-900">Rp{{ number_format($credit->interest_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-700">Total Kredit</span>
                    <span class="text-sm font-bold text-gray-900">Rp{{ number_format($credit->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Cicilan / Bulan</span>
                    <span class="text-sm font-medium text-blue-600">Rp{{ number_format($credit->monthly_installment, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Jangka Waktu</span>
                    <span class="text-sm font-medium text-gray-900">{{ $credit->installment_months }} bulan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress & Additional Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Progress Pembayaran -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Progres Pembayaran
                </h3>
                @php
                    $progressPercent = $credit->total_amount > 0 ? min(100, ($credit->total_paid / $credit->total_amount) * 100) : 0;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Total Terbayar</p>
                        <p class="text-xl font-bold text-green-600">Rp{{ number_format($credit->total_paid, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Sisa Tagihan</p>
                        <p class="text-xl font-bold text-red-600">Rp{{ number_format($credit->remaining_balance, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Progres</p>
                        <p class="text-xl font-bold text-blue-600">{{ number_format($progressPercent, 1) }}%</p>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
                <div class="flex justify-between mt-2 text-xs text-gray-500">
                    <span>0%</span>
                    <span>{{ number_format($progressPercent, 1) }}% terbayar</span>
                    <span>100%</span>
                </div>
            </div>
        </div>

        <!-- Tenor & Bunga, Dicatat Oleh -->
        <div class="space-y-6">
            <!-- Tenor & Bunga -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Tenor & Bunga
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Jangka Waktu</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->installment_months }} bulan</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Suku Bunga</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->interest_rate }}% per tahun</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Cicilan per Bulan</p>
                        <p class="text-sm font-semibold text-blue-600 mt-0.5">Rp{{ number_format($credit->monthly_installment, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Dicatat Oleh -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Dicatat Oleh
                </h3>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold text-sm">
                        {{ $credit->creator ? strtoupper(substr($credit->creator->name, 0, 1)) : '?' }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $credit->creator->name ?? 'Tidak diketahui' }}</p>
                        <p class="text-xs text-gray-500">{{ $credit->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Pembayaran Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Jadwal Pembayaran</h3>
            <p class="text-sm text-gray-500 mt-0.5">Rincian cicilan dan status pembayaran</p>
        </div>

        @if($credit->payments && $credit->payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tagihan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Dibayar</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($credit->payments as $payment)
                    <tr class="hover:bg-gray-50 {{ $payment->due_date->isPast() && !in_array($payment->status, ['paid']) ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            {{ $payment->installment_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $payment->payment_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm {{ $payment->due_date->isPast() && $payment->status != 'paid' ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                {{ $payment->due_date->format('d M Y') }}
                            </span>
                            @if($payment->due_date->isPast() && $payment->status != 'paid')
                            <p class="text-xs text-red-500">Lewat {{ $payment->due_date->diffForHumans() }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            Rp{{ number_format($payment->amount_due, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-green-600">
                            Rp{{ number_format($payment->amount_paid, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @switch($payment->status)
                                @case('pending')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Menunggu</span>
                                    @break
                                @case('paid')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Lunas</span>
                                    @break
                                @case('partial')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Sebagian</span>
                                    @break
                                @case('overdue')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Jatuh Tempo</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $payment->paid_date ? $payment->paid_date->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-gray-500">Belum ada jadwal pembayaran</p>
        </div>
        @endif
    </div>
</div>
@endsection
