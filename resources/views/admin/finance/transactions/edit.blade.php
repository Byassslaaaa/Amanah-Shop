@extends('layouts.admin')

@section('title', 'Edit Transaksi Keuangan')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.finance.transactions.index') }}" class="hover:text-gray-900">Transaksi Keuangan</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">Edit Transaksi</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Transaksi Keuangan</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $transaction->transaction_number }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.finance.transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Transaction Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi <span class="text-red-500">*</span></label>
                <select name="type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror" required>
                    <option value="">Pilih Tipe</option>
                    <option value="income" {{ old('type', $transaction->type) == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ old('type', $transaction->type) == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                @error('type')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transaction Date -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('transaction_date') border-red-500 @enderror" required>
                @error('transaction_date')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                    <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}"
                        class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror"
                        placeholder="0" step="0.01" required>
                </div>
                @error('amount')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Masukkan deskripsi transaksi..." required>{{ old('description', $transaction->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Attachment -->
            @if($transaction->attachment)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran Saat Ini</label>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    <a href="{{ Storage::url($transaction->attachment) }}" target="_blank" class="text-blue-600 hover:underline">
                        Lihat Lampiran
                    </a>
                </div>
            </div>
            @endif

            <!-- New Attachment -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ganti Lampiran</label>
                <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('attachment') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.</p>
                @error('attachment')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                    placeholder="Catatan tambahan...">{{ old('notes', $transaction->notes) }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Update Transaksi
                </button>
                <a href="{{ route('admin.finance.transactions.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
