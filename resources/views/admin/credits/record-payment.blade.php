@extends('layouts.admin')

@section('title', 'Catat Pembayaran')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.credits.index') }}" class="hover:text-gray-900">Kredit Manual</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.credits.show', $credit) }}" class="hover:text-gray-900">{{ $credit->credit_number }}</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">Catat Pembayaran</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Catat Pembayaran</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <form action="{{ route('admin.credits.process-payment', $credit) }}" method="POST">
                    @csrf

                    <!-- Amount -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pembayaran <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                            <input type="number" name="amount" value="{{ old('amount', $credit->remaining_balance) }}"
                                min="1" max="{{ $credit->remaining_balance }}"
                                class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror"
                                placeholder="0" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Maksimal: Rp{{ number_format($credit->remaining_balance, 0, ',', '.') }}</p>
                        @error('amount')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('payment_date') border-red-500 @enderror" required>
                        @error('payment_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <select name="payment_method" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('payment_method') border-red-500 @enderror">
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('payment_method')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                            placeholder="Catatan pembayaran (opsional)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Cepat</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setAmount({{ $credit->remaining_balance }})"
                                class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors">
                                Lunasi (Rp{{ number_format($credit->remaining_balance, 0, ',', '.') }})
                            </button>
                            @if($credit->monthly_installment)
                            <button type="button" onclick="setAmount({{ $credit->monthly_installment }})"
                                class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors">
                                1 Cicilan (Rp{{ number_format($credit->monthly_installment, 0, ',', '.') }})
                            </button>
                            @endif
                            <button type="button" onclick="setAmount({{ round($credit->remaining_balance / 2) }})"
                                class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                50% (Rp{{ number_format(round($credit->remaining_balance / 2), 0, ',', '.') }})
                            </button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                            Simpan Pembayaran
                        </button>
                        <a href="{{ route('admin.credits.show', $credit) }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Credit Summary Sidebar -->
        <div class="space-y-6">
            <!-- Credit Info -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Info Kredit</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1">No. Kredit</p>
                        <p class="text-gray-900 font-medium">{{ $credit->credit_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Pelanggan</p>
                        <p class="text-gray-900 font-medium">{{ $credit->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Deskripsi</p>
                        <p class="text-gray-900">{{ Str::limit($credit->description, 50) }}</p>
                    </div>
                </div>
            </div>

            <!-- Amount Summary -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Ringkasan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Kredit</span>
                        <span class="text-gray-900">Rp{{ number_format($credit->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sudah Dibayar</span>
                        <span class="text-green-600 font-medium">Rp{{ number_format($credit->amount_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-100">
                        <span class="text-gray-900 font-medium">Sisa Hutang</span>
                        <span class="text-red-600 font-bold">Rp{{ number_format($credit->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Progress</span>
                        <span>{{ $credit->total_amount > 0 ? round(($credit->amount_paid / $credit->total_amount) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $credit->total_amount > 0 ? ($credit->amount_paid / $credit->total_amount * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Due Date Warning -->
            @if($credit->due_date->isPast())
            <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-900">Kredit Jatuh Tempo!</p>
                        <p class="text-xs text-red-700 mt-1">Lewat {{ $credit->due_date->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function setAmount(amount) {
    document.querySelector('input[name="amount"]').value = amount;
}
</script>
@endsection
