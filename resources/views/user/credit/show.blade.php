@extends('layouts.app')

@section('title', 'Detail Cicilan - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('user.orders.index') }}" class="hover:text-green-600">Pesanan Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('user.credit.index') }}" class="hover:text-green-600">Kredit</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium">Detail</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Cicilan</h1>
            <p class="text-gray-600 mt-1 text-sm sm:text-base">Order #{{ $order->order_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Credit Summary -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Kredit</h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Total Kredit</p>
                            <p class="text-lg font-bold text-gray-900">
                                Rp{{ number_format($order->total_credit_amount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Sudah Dibayar</p>
                            <p class="text-lg font-bold text-green-600">
                                Rp{{ number_format($order->total_paid, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Sisa Hutang</p>
                            <p class="text-lg font-bold text-red-600">
                                Rp{{ number_format($order->remaining_balance, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Cicilan/Bulan</p>
                            <p class="text-lg font-bold text-purple-600">
                                Rp{{ number_format($order->monthly_installment, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress Pembayaran</span>
                            <span class="font-semibold">{{ $order->total_credit_amount > 0 ? round(($order->total_paid / $order->total_credit_amount) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-300"
                                 style="width: {{ $order->total_credit_amount > 0 ? ($order->total_paid / $order->total_credit_amount * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Installment Schedule -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-blue-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Jadwal Cicilan</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $order->installment_months }} kali pembayaran</p>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach($order->installmentPayments as $payment)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold text-sm rounded-full">
                                                {{ $payment->installment_number }}
                                            </span>
                                            <div>
                                                <p class="font-semibold text-gray-900">
                                                    Cicilan ke-{{ $payment->installment_number }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    Jatuh Tempo: {{ $payment->due_date->format('d M Y') }}
                                                    @if($payment->due_date->isPast() && $payment->status !== 'paid')
                                                        <span class="text-red-600 font-semibold">
                                                            (Lewat {{ $payment->due_date->diffForHumans() }})
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="ml-11">
                                            <p class="text-sm text-gray-700 mb-1">
                                                <span class="font-medium">Tagihan:</span>
                                                <span class="font-bold text-gray-900">Rp{{ number_format($payment->amount_due, 0, ',', '.') }}</span>
                                            </p>
                                            @if($payment->amount_paid > 0)
                                                <p class="text-sm text-green-600 mb-1">
                                                    <span class="font-medium">Dibayar:</span>
                                                    <span class="font-bold">Rp{{ number_format($payment->amount_paid, 0, ',', '.') }}</span>
                                                </p>
                                            @endif
                                            @if($payment->notes)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span class="font-medium">Catatan:</span> {{ $payment->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-start sm:items-end gap-2 ml-11 sm:ml-0">
                                        @switch($payment->status)
                                            @case('paid')
                                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                                    ✓ Lunas
                                                </span>
                                                @if($payment->verified_at)
                                                    <p class="text-xs text-gray-500">
                                                        {{ $payment->verified_at->format('d M Y') }}
                                                    </p>
                                                @endif
                                                @break
                                            @case('pending')
                                                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                                    ⏳ Menunggu Verifikasi
                                                </span>
                                                <a href="{{ route('user.credit.payment-proof-form', $payment) }}"
                                                   class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                    Lihat Bukti →
                                                </a>
                                                @break
                                            @case('partial')
                                                <span class="px-3 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded-full">
                                                    Sebagian
                                                </span>
                                                <a href="{{ route('user.credit.payment-proof-form', $payment) }}"
                                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors">
                                                    Upload Bukti
                                                </a>
                                                @break
                                            @default
                                                @if($payment->due_date->isPast())
                                                    <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                                        ⚠ Jatuh Tempo
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                                        Belum Dibayar
                                                    </span>
                                                @endif
                                                <a href="{{ route('user.credit.payment-proof-form', $payment) }}"
                                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors">
                                                    Upload Bukti
                                                </a>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Informasi Order</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">No. Order</p>
                            <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Paket Cicilan</p>
                            <p class="font-semibold text-gray-900">{{ $order->installmentPlan->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Durasi</p>
                            <p class="font-semibold text-gray-900">{{ $order->installment_months }} bulan</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Tanggal Order</p>
                            <p class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('user.orders.show', $order) }}"
                           class="block w-full text-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Lihat Detail Order
                        </a>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-lg shadow-sm border border-blue-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Cara Pembayaran</h3>
                    <ol class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 bg-blue-600 text-white text-xs font-bold rounded-full flex items-center justify-center">1</span>
                            <span>Lakukan transfer sesuai nominal cicilan</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 bg-blue-600 text-white text-xs font-bold rounded-full flex items-center justify-center">2</span>
                            <span>Upload bukti pembayaran</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 bg-blue-600 text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
                            <span>Tunggu verifikasi dari admin</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
