@extends('layouts.admin')

@section('title', 'Tambah Transaksi Keuangan')

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
            <span class="text-gray-900">Tambah Transaksi</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Transaksi Keuangan</h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-3xl">
        <form action="{{ route('admin.finance.transactions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi <span class="text-red-500">*</span></label>
                    <select name="type" id="type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror" required>
                        <option value="">Pilih Tipe</option>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                    @error('type')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror" required>
                        <option value="">Pilih kategori terlebih dahulu tipe transaksi</option>
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Amount -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-sm text-gray-500">Rp</span>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="1"
                        class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror"
                        placeholder="0" required>
                </div>
                @error('amount')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transaction Date -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('transaction_date') border-red-500 @enderror" required>
                @error('transaction_date')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Masukkan deskripsi transaksi..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Method -->
            <div class="mt-6">
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

            <!-- Attachment -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran (Opsional)</label>
                <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('attachment') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @error('attachment')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                    placeholder="Catatan tambahan (opsional)...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 mt-6 pt-6 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Simpan Transaksi
                </button>
                <a href="{{ route('admin.finance.transactions.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');

    const categories = @json(\App\Models\Finance\TransactionCategory::orderBy('name')->get()->groupBy('type'));

    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        categorySelect.innerHTML = '<option value="">Pilih Kategori</option>';

        if (selectedType && categories[selectedType]) {
            categories[selectedType].forEach(function(category) {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                if ('{{ old("category_id") }}' == category.id) {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });
        }
    });

    // Trigger on page load if type is already selected
    if (typeSelect.value) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
