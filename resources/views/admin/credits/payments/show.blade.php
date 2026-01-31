@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.credit-payments.index') }}" class="hover:text-gray-900">Pembayaran Kredit</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">Detail</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pembayaran</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Info -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pembayaran</h2>
                    @switch($payment->status)
                        @case('paid')
                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Lunas</span>
                            @break
                        @case('partial')
                            <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Sebagian</span>
                            @break
                        @case('pending')
                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Menunggu</span>
                            @break
                    @endswitch
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Cicilan Ke-</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $payment->installment_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Jatuh Tempo</p>
                            <p class="text-base {{ $payment->due_date->isPast() && $payment->status != 'paid' ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                {{ $payment->due_date->format('d M Y') }}
                                @if($payment->due_date->isPast() && $payment->status != 'paid')
                                <span class="text-xs">(Lewat {{ $payment->due_date->diffForHumans() }})</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tagihan</p>
                            <p class="text-xl font-bold text-gray-900">Rp{{ number_format($payment->amount_due, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Sudah Dibayar</p>
                            <p class="text-xl font-bold text-green-600">Rp{{ number_format($payment->amount_paid, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($payment->amount_due - $payment->amount_paid > 0)
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600">Kurang Bayar</p>
                            <p class="text-lg font-semibold text-red-600">Rp{{ number_format($payment->amount_due - $payment->amount_paid, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($payment->payment_date)
                    <div class="pt-4 border-t border-gray-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tanggal Bayar</p>
                                <p class="text-base text-gray-900">{{ $payment->payment_date->format('d M Y') }}</p>
                            </div>
                            @if($payment->payment_method)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Metode</p>
                                <p class="text-base text-gray-900 capitalize">{{ $payment->payment_method }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($payment->notes)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Catatan</p>
                        <p class="text-base text-gray-900">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Credit Info -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kredit</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">No. Kredit</p>
                            <a href="{{ route('admin.credits.show', $payment->manualCredit) }}" class="text-base font-medium text-blue-600 hover:text-blue-900">
                                {{ $payment->manualCredit->credit_number }}
                            </a>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status Kredit</p>
                            @switch($payment->manualCredit->status)
                                @case('active')
                                    <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Aktif</span>
                                    @break
                                @case('paid_off')
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Lunas</span>
                                    @break
                                @case('overdue')
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Jatuh Tempo</span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pelanggan</p>
                        <p class="text-base font-medium text-gray-900">{{ $payment->manualCredit->customer_name }}</p>
                        @if($payment->manualCredit->customer_phone)
                        <p class="text-sm text-gray-500">{{ $payment->manualCredit->customer_phone }}</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Deskripsi</p>
                        <p class="text-base text-gray-900">{{ $payment->manualCredit->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            @if($payment->status != 'paid')
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.credits.record-payment', $payment->manualCredit) }}"
                       class="block w-full px-4 py-2 text-sm text-center font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                        Catat Pembayaran
                    </a>
                </div>
            </div>
            @endif

            <!-- Credit Summary -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Ringkasan Kredit</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Kredit</span>
                        <span class="text-gray-900">Rp{{ number_format($payment->manualCredit->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sudah Dibayar</span>
                        <span class="text-green-600">Rp{{ number_format($payment->manualCredit->amount_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-100">
                        <span class="text-gray-900 font-medium">Sisa Hutang</span>
                        <span class="text-red-600 font-bold">Rp{{ number_format($payment->manualCredit->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Progress</span>
                        <span>{{ $payment->manualCredit->total_amount > 0 ? round(($payment->manualCredit->amount_paid / $payment->manualCredit->total_amount) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $payment->manualCredit->total_amount > 0 ? ($payment->manualCredit->amount_paid / $payment->manualCredit->total_amount * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-3 text-sm">
                    @if($payment->verified_by)
                    <div>
                        <p class="text-gray-600 mb-1">Diverifikasi oleh</p>
                        <p class="text-gray-900 font-medium">{{ $payment->verifier->name ?? 'System' }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-gray-600 mb-1">Tanggal dibuat</p>
                        <p class="text-gray-900">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($payment->updated_at != $payment->created_at)
                    <div>
                        <p class="text-gray-600 mb-1">Terakhir diupdate</p>
                        <p class="text-gray-900">{{ $payment->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
