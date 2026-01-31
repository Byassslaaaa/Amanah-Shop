@extends('layouts.admin')

@section('title', 'Tambah Kredit Baru')

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
            <span class="text-gray-900">Tambah Baru</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Kredit Baru</h1>
        <p class="text-sm text-gray-600 mt-1">Catat kredit atau hutang pelanggan diluar transaksi toko</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-3xl">
        <form action="{{ route('admin.credits.store') }}" method="POST">
            @csrf

            <!-- Customer Info Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Pelanggan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror"
                            placeholder="Masukkan nama pelanggan" required>
                        @error('customer_name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
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
                        placeholder="Alamat pelanggan (opsional)">{{ old('customer_address') }}</textarea>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kredit <span class="text-red-500">*</span></label>
                        <input type="date" name="credit_date" value="{{ old('credit_date', date('Y-m-d')) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('credit_date') border-red-500 @enderror" required>
                        @error('credit_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
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
                        placeholder="Jelaskan untuk apa kredit ini (contoh: Pembelian material bangunan, dll.)" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Amount Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Jumlah Kredit</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pinjaman <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                            <input type="number" name="loan_amount" value="{{ old('loan_amount') }}" min="1"
                                class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('loan_amount') border-red-500 @enderror"
                                placeholder="0" required>
                        </div>
                        @error('loan_amount')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uang Muka (DP)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                            <input type="number" name="down_payment" value="{{ old('down_payment', 0) }}" min="0"
                                class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('down_payment') border-red-500 @enderror"
                                placeholder="0">
                        </div>
                        @error('down_payment')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Skema Cicilan</label>
                    <select name="installment_plan_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('installment_plan_id') border-red-500 @enderror">
                        <option value="">Bayar Langsung (Tanpa Cicilan)</option>
                        @foreach($installmentPlans as $plan)
                        <option value="{{ $plan->id }}" {{ old('installment_plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} - {{ $plan->duration_months }} bulan ({{ $plan->interest_rate }}% bunga)
                        </option>
                        @endforeach
                    </select>
                    @error('installment_plan_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes Section -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                    placeholder="Catatan internal (opsional)">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Simpan Kredit
                </button>
                <a href="{{ route('admin.credits.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
