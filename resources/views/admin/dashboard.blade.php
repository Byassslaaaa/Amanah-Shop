@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-600 mt-1">Ringkasan data toko Anda - {{ now()->format('d F Y') }}</p>
        </div>

        <!-- Financial Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Monthly Income -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pemasukan Bulan Ini</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp{{ number_format($monthlyIncome, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Expense -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pengeluaran Bulan Ini</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Balance -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Saldo Bersih Bulan Ini</p>
                        <p class="text-2xl font-bold {{ $monthlyBalance >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                            Rp{{ number_format($monthlyBalance, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pesanan</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $pendingOrders }} pending</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory & Credit Alerts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Low Stock Alert -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Stok Menipis</p>
                            <p class="text-xl font-bold text-gray-900">{{ $lowStockProducts }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Out of Stock Alert -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Stok Habis</p>
                            <p class="text-xl font-bold text-gray-900">{{ $outOfStockProducts }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Payments -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jatuh Tempo</p>
                            <p class="text-xl font-bold text-gray-900">{{ $overduePayments }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Financial Trend Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Tren Keuangan 6 Bulan Terakhir</h3>
                    <a href="{{ route('admin.finance.report') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Detail
                    </a>
                </div>
                <div class="relative" style="height: 300px;">
                    <canvas id="financeTrendChart"></canvas>
                </div>
            </div>

            <!-- Credit Summary -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan Kredit</h3>
                    <a href="{{ route('admin.credits.manual.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Kelola
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-600 mb-1">Kredit Aktif</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $activeCredits }}</p>
                    </div>

                    <div class="p-4 bg-orange-50 rounded-lg">
                        <p class="text-sm text-orange-600 mb-1">Total Piutang</p>
                        <p class="text-xl font-bold text-orange-900">Rp{{ number_format($totalReceivable, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-600 mb-1">Total Terbayar</p>
                        <p class="text-xl font-bold text-green-900">Rp{{ number_format($totalCreditPaid, 0, ',', '.') }}
                        </p>
                    </div>

                    @if ($overdueAmount > 0)
                        <div class="p-4 bg-red-50 rounded-lg">
                            <p class="text-sm text-red-600 mb-1">Tunggakan</p>
                            <p class="text-xl font-bold text-red-900">Rp{{ number_format($overdueAmount, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Low Stock Products -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Produk Stok Menipis</h3>
                    <a href="{{ route('admin.products.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($lowStockList as $product)
                        <div
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center flex-1 min-w-0">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 overflow-hidden">
                                    @if ($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getImageDataUri(0) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Tanpa kategori' }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="ml-3 px-2 py-1 text-xs font-semibold {{ $product->stock == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full">
                                Stok: {{ $product->stock }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">Semua produk stoknya aman</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Payments -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Pembayaran Akan Datang (7 Hari)</h3>
                    <a href="{{ route('admin.credits.payments.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingPayments as $payment)
                        <div
                            class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $payment->manualCredit->customer_name }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->manualCredit->credit_number }}</p>
                                <p class="text-xs text-orange-600 font-medium mt-1">
                                    Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                                </p>
                            </div>
                            <div class="ml-3 text-right">
                                <p class="text-sm font-semibold text-gray-900">
                                    Rp{{ number_format($payment->amount_due - $payment->amount_paid, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">Tidak ada pembayaran dalam 7 hari ke depan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Transaksi Keuangan Terakhir</h3>
                    <a href="{{ route('admin.finance.transactions.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentTransactions as $transaction)
                        <div
                            class="flex items-center justify-between p-3 border-l-4 {{ $transaction->type == 'income' ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }} rounded">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $transaction->category->name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $transaction->transaction_date->format('d M Y') }}</p>
                            </div>
                            <p
                                class="ml-3 text-sm font-semibold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type == 'income' ? '+' : '-' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada transaksi</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Inventory Movements -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Pergerakan Stok Terakhir</h3>
                    <a href="{{ route('admin.inventory.movements.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentMovements as $movement)
                        <div
                            class="flex items-center justify-between p-3 border-l-4 {{ $movement->type == 'in' ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }} rounded">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $movement->product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $movement->created_at->diffForHumans() }}</p>
                            </div>
                            <p
                                class="ml-3 text-sm font-semibold {{ $movement->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada pergerakan stok</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Pesanan Terakhir</h3>
                    <a href="{{ route('admin.orders.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                        <div
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-500">#{{ $order->order_number }}</p>
                            </div>
                            <div class="ml-3 text-right">
                                <p class="text-sm font-semibold text-gray-900">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                @if ($order->status === 'completed')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada pesanan</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Financial Trend Chart
            const financeTrendCtx = document.getElementById('financeTrendChart');
            if (financeTrendCtx) {
                new Chart(financeTrendCtx, {
                    type: 'line',
                    data: {
                        labels: @json(array_column($financeTrend, 'month')),
                        datasets: [{
                                label: 'Pemasukan',
                                data: @json(array_map(function ($item) {
                                        return $item['income'];
                                    }, $financeTrend)),
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Pengeluaran',
                                data: @json(array_map(function ($item) {
                                        return $item['expense'];
                                    }, $financeTrend)),
                                borderColor: '#EF4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp' + new Intl.NumberFormat('id-ID').format(
                                                context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F3F4F6'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + new Intl.NumberFormat('id-ID', {
                                            notation: 'compact'
                                        }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
