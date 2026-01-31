@extends('layouts.admin')

@section('title', 'Pembayaran Jatuh Tempo')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pembayaran Jatuh Tempo</h1>
            <p class="text-sm text-gray-600 mt-1">Daftar pembayaran yang sudah melewati jatuh tempo</p>
        </div>
        <a href="{{ route('admin.credit-payments.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Warning Banner -->
    <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="text-base font-semibold text-red-900">{{ $payments->total() }} Pembayaran Jatuh Tempo</p>
                <p class="text-sm text-red-700 mt-1">Total tunggakan: <span class="font-semibold">Rp{{ number_format($totalOverdue, 0, ',', '.') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @if($payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kredit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Cicilan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tunggakan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($payments as $payment)
                    <tr class="bg-red-50 hover:bg-red-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-red-600 font-semibold">
                                {{ $payment->due_date->format('d M Y') }}
                            </span>
                            <p class="text-xs text-red-500">{{ $payment->due_date->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.credits.show', $payment->manualCredit) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">
                                {{ $payment->manualCredit->credit_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $payment->manualCredit->customer_name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($payment->manualCredit->customer_phone)
                            <a href="tel:{{ $payment->manualCredit->customer_phone }}" class="text-sm text-blue-600 hover:text-blue-900">
                                {{ $payment->manualCredit->customer_phone }}
                            </a>
                            @else
                            <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            Ke-{{ $payment->installment_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600">
                            Rp{{ number_format($payment->amount_due - $payment->amount_paid, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.credit-payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.credits.record-payment', $payment->manualCredit) }}" class="text-green-600 hover:text-green-900" title="Catat Pembayaran">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-500 text-lg font-medium">Tidak ada pembayaran jatuh tempo</p>
            <p class="text-sm text-gray-400 mt-1">Semua pembayaran berjalan lancar</p>
        </div>
        @endif
    </div>
</div>
@endsection
