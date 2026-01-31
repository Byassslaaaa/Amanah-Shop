@extends('layouts.admin')

@section('title', 'Edit Kredit')

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
            <span class="text-gray-900">Edit</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Kredit</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $credit->credit_number }}</p>
    </div>

    <!-- Warning if has payments -->
    @if($credit->payments->count() > 0)
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-100 rounded-xl">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="text-sm font-medium text-yellow-900">Kredit ini sudah memiliki pembayaran</p>
                <p class="text-xs text-yellow-700 mt-1">Beberapa field tidak dapat diubah karena sudah ada pembayaran tercatat.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-3xl">
        <form action="{{ route('admin.credits.update', $credit) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Customer Info Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Pelanggan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $credit->customer_name) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror"
                            placeholder="Masukkan nama pelanggan" required>
                        @error('customer_name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $credit->customer_phone) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('customer_phone') border-red-500 @enderror"
                            placeholder="08xxxxxxxxxx">
                        @error('customer_phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="customer_address" rows="2"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('customer_address') border-red-500 @enderror"
                        placeholder="Alamat pelanggan (opsional)">{{ old('customer_address', $credit->customer_address) }}</textarea>
                    @error('customer_address')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Credit Info Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Detail Kredit</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kredit</label>
                        <input type="date" name="credit_date" value="{{ old('credit_date', $credit->credit_date->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
                        <input type="date" name="due_date" value="{{ old('due_date', $credit->due_date->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('due_date') border-red-500 @enderror" required>
                        @error('due_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kredit <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Jelaskan untuk apa kredit ini" required>{{ old('description', $credit->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Amount Section (Read-only if has payments) -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Jumlah Kredit</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pinjaman</label>
                        <div class="px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg">
                            Rp{{ number_format($credit->loan_amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uang Muka (DP)</label>
                        <div class="px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg">
                            Rp{{ number_format($credit->down_payment, 0, ',', '.') }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Kredit</label>
                        <div class="px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg font-semibold">
                            Rp{{ number_format($credit->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sudah Dibayar</label>
                        <div class="px-3 py-2 text-sm bg-green-50 border border-green-200 rounded-lg text-green-700 font-semibold">
                            Rp{{ number_format($credit->amount_paid, 0, ',', '.') }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sisa Hutang</label>
                        <div class="px-3 py-2 text-sm bg-red-50 border border-red-200 rounded-lg text-red-700 font-semibold">
                            Rp{{ number_format($credit->remaining_balance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Status Kredit</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $credit->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="paid_off" {{ old('status', $credit->status) == 'paid_off' ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ old('status', $credit->status) == 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                        <option value="cancelled" {{ old('status', $credit->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes Section -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                    placeholder="Catatan internal (opsional)">{{ old('notes', $credit->notes) }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Update Kredit
                </button>
                <a href="{{ route('admin.credits.show', $credit) }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
