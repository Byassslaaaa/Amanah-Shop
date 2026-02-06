@extends('layouts.app')

@section('title', 'Kredit Saya - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kredit Saya</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Kelola pembayaran cicilan Anda</p>
        </div>

        @if($creditOrders->count() > 0)
            <div class="space-y-4">
                @foreach($creditOrders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-blue-50 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base">
                                        Order #{{ $order->order_number }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($order->payment_status === 'paid')
                                        <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Lunas
                                        </span>
                                    @elseif($order->payment_status === 'partial')
                                        <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                            Cicilan Berjalan
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                            Menunggu Pembayaran
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Paket Cicilan</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $order->installmentPlan->name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ $order->installment_months }} bulan
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Total Kredit</p>
                                    <p class="text-sm font-bold text-gray-900">
                                        Rp{{ number_format($order->total_credit_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Sudah Dibayar</p>
                                    <p class="text-sm font-bold text-green-600">
                                        Rp{{ number_format($order->total_paid, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Sisa Hutang</p>
                                    <p class="text-sm font-bold text-red-600">
                                        Rp{{ number_format($order->remaining_balance, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span>Progress Pembayaran</span>
                                    <span>{{ $order->total_credit_amount > 0 ? round(($order->total_paid / $order->total_credit_amount) * 100) : 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                         style="width: {{ $order->total_credit_amount > 0 ? ($order->total_paid / $order->total_credit_amount * 100) : 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('user.credit.show', $order) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Lihat Detail Cicilan
                                </a>
                                <a href="{{ route('user.orders.show', $order) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Lihat Detail Order
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $creditOrders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kredit</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki pembelian dengan sistem kredit/cicilan.</p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Belanja Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
