@extends('layouts.admin')

@section('title', 'Detail Kredit')

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
                <a href="{{ route('admin.credits.index') }}" class="hover:text-gray-900">Kredit Manual</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900">Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $credit->credit_number }}</h1>
        </div>
        <div class="flex items-center space-x-2">
            @if($credit->status == 'active')
            <a href="{{ route('admin.credits.record-payment', $credit) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Catat Pembayaran
            </a>
            @endif
            @if($credit->payments->count() == 0)
            <a href="{{ route('admin.credits.edit', $credit) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Credit Info Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Kredit</h2>
                    @switch($credit->status)
                        @case('active')
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Aktif</span>
                            @break
                        @case('paid_off')
                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Lunas</span>
                            @break
                        @case('overdue')
                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Jatuh Tempo</span>
                            @break
                        @case('cancelled')
                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Dibatalkan</span>
                            @break
                    @endswitch
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Kredit</p>
                            <p class="text-base text-gray-900">{{ $credit->credit_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Jatuh Tempo</p>
                            <p class="text-base {{ $credit->due_date->isPast() && $credit->status == 'active' ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                {{ $credit->due_date->format('d M Y') }}
                                @if($credit->due_date->isPast() && $credit->status == 'active')
                                    <span class="text-xs">(Lewat {{ $credit->due_date->diffForHumans() }})</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Deskripsi</p>
                        <p class="text-base text-gray-900">{{ $credit->description }}</p>
                    </div>

                    @if($credit->notes)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Catatan</p>
                        <p class="text-base text-gray-900">{{ $credit->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelanggan</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nama Pelanggan</p>
                            <p class="text-base font-medium text-gray-900">{{ $credit->customer_name }}</p>
                        </div>
                        @if($credit->customer_phone)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">No. Telepon</p>
                            <p class="text-base text-gray-900">{{ $credit->customer_phone }}</p>
                        </div>
                        @endif
                    </div>

                    @if($credit->customer_address)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Alamat</p>
                        <p class="text-base text-gray-900">{{ $credit->customer_address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h2>
                </div>

                @if($credit->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($credit->payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    Cicilan ke-{{ $payment->installment_number ?? '-' }}
                                    @if($payment->notes)
                                        <p class="text-xs text-gray-500">{{ $payment->notes }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-green-600">
                                    Rp{{ number_format($payment->amount_paid, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @switch($payment->status)
                                        @case('paid')
                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Lunas</span>
                                            @break
                                        @case('partial')
                                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Sebagian</span>
                                            @break
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Menunggu</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 mb-4">Belum ada pembayaran tercatat</p>
                    @if($credit->status == 'active')
                    <a href="{{ route('admin.credits.record-payment', $credit) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                        Catat Pembayaran Pertama
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Amount Summary Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Ringkasan Keuangan</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jumlah Pinjaman</p>
                        <p class="text-lg font-semibold text-gray-900">Rp{{ number_format($credit->loan_amount, 0, ',', '.') }}</p>
                    </div>

                    @if($credit->down_payment > 0)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Uang Muka</p>
                        <p class="text-base text-gray-900">Rp{{ number_format($credit->down_payment, 0, ',', '.') }}</p>
                    </div>
                    @endif

                    @if($credit->interest_amount > 0)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Bunga</p>
                        <p class="text-base text-gray-900">Rp{{ number_format($credit->interest_amount, 0, ',', '.') }}</p>
                    </div>
                    @endif

                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Total Kredit</p>
                        <p class="text-xl font-bold text-gray-900">Rp{{ number_format($credit->total_amount, 0, ',', '.') }}</p>
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm text-gray-600">Sudah Dibayar</p>
                            <p class="text-base font-semibold text-green-600">Rp{{ number_format($credit->amount_paid, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600">Sisa Hutang</p>
                            <p class="text-base font-semibold text-red-600">Rp{{ number_format($credit->remaining_balance, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="pt-3">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Progress Pembayaran</span>
                            <span>{{ $credit->total_amount > 0 ? round(($credit->amount_paid / $credit->total_amount) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $credit->total_amount > 0 ? ($credit->amount_paid / $credit->total_amount * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Installment Info -->
            @if($credit->installmentPlan)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Info Cicilan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Skema</span>
                        <span class="text-gray-900 font-medium">{{ $credit->installmentPlan->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durasi</span>
                        <span class="text-gray-900">{{ $credit->installmentPlan->duration_months }} bulan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bunga</span>
                        <span class="text-gray-900">{{ $credit->installmentPlan->interest_rate }}%</span>
                    </div>
                    @if($credit->monthly_installment)
                    <div class="flex justify-between pt-2 border-t border-gray-100">
                        <span class="text-gray-600">Cicilan/bulan</span>
                        <span class="text-gray-900 font-semibold">Rp{{ number_format($credit->monthly_installment, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1">Dibuat oleh</p>
                        <p class="text-gray-900 font-medium">{{ $credit->creator->name ?? 'System' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Tanggal dibuat</p>
                        <p class="text-gray-900">{{ $credit->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($credit->updated_at != $credit->created_at)
                    <div>
                        <p class="text-gray-600 mb-1">Terakhir diupdate</p>
                        <p class="text-gray-900">{{ $credit->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
