@extends('layouts.admin')

@section('title', 'Catat Pembayaran Kredit')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('admin.credits.manual.index') }}" class="hover:text-blue-600">Kelola Kredit</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('admin.credits.manual.show', $credit) }}" class="hover:text-blue-600">{{ $credit->credit_number }}</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-gray-900 font-medium">Catat Pembayaran</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Catat Pembayaran</h1>
            <p class="text-sm text-gray-600 mt-1">Catat pembayaran cicilan untuk kredit {{ $credit->credit_number }}</p>
        </div>
        <a href="{{ route('admin.credits.manual.show', $credit) }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Terdapat kesalahan pada form:</span>
        </div>
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Pembayaran (2 cols) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    <svg class="w-5 h-5 inline mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Form Pembayaran
                </h2>

                <form action="{{ route('admin.credits.manual.store-payment', $credit) }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Pilih Cicilan -->
                        <div>
                            <label for="payment_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Cicilan <span class="text-red-500">*</span>
                            </label>
                            @if($pendingPayments->count() > 0)
                            <select name="payment_id" id="payment_id"
                                class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('payment_id') border-red-300 @enderror"
                                required>
                                <option value="">-- Pilih Cicilan yang Akan Dibayar --</option>
                                @foreach($pendingPayments as $payment)
                                    <option value="{{ $payment->id }}" {{ old('payment_id') == $payment->id ? 'selected' : '' }}
                                        data-amount-due="{{ $payment->amount_due }}"
                                        data-amount-paid="{{ $payment->amount_paid }}">
                                        Cicilan ke-{{ $payment->installment_number }} - Rp{{ number_format($payment->amount_due, 0, ',', '.') }} - Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-sm text-yellow-700">Tidak ada cicilan yang menunggu pembayaran.</p>
                            </div>
                            @endif
                            @error('payment_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Bayar -->
                        <div>
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="amount_paid" id="amount_paid"
                                    value="{{ old('amount_paid') }}" min="0" step="1000"
                                    class="w-full text-sm border border-gray-200 rounded-lg pl-12 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('amount_paid') border-red-300 @enderror"
                                    placeholder="0" required>
                            </div>
                            @error('amount_paid')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Bayar -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="payment_date" id="payment_date"
                                value="{{ old('payment_date', date('Y-m-d')) }}"
                                class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('payment_date') border-red-300 @enderror"
                                required>
                            @error('payment_date')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (opsional)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('notes') border-red-300 @enderror"
                                placeholder="Catatan tambahan untuk pembayaran ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.credits.manual.show', $credit) }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        @if($pendingPayments->count() > 0)
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Pembayaran
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar: Ringkasan Kredit -->
        <div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Ringkasan Kredit
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">No. Kredit</p>
                        <p class="text-sm font-semibold text-blue-600 mt-0.5">{{ $credit->credit_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pelanggan</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $credit->customer_name }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <div class="flex justify-between mb-1">
                            <span class="text-xs text-gray-500">Total Kredit</span>
                            <span class="text-sm font-medium text-gray-900">Rp{{ number_format($credit->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span class="text-xs text-gray-500">Terbayar</span>
                            <span class="text-sm font-semibold text-green-600">Rp{{ number_format($credit->total_paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs font-semibold text-gray-700">Sisa</span>
                            <span class="text-sm font-bold text-red-600">Rp{{ number_format($credit->remaining_balance, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $progressPercent = $credit->total_amount > 0 ? min(100, ($credit->total_paid / $credit->total_amount) * 100) : 0;
                    @endphp
                    <div class="pt-3 border-t border-gray-100">
                        <div class="flex justify-between mb-2">
                            <span class="text-xs text-gray-500">Progres</span>
                            <span class="text-xs font-semibold text-blue-600">{{ number_format($progressPercent, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
