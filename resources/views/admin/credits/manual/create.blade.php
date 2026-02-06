@extends('layouts.admin')

@section('title', 'Tambah Kredit Baru')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('admin.credits.manual.index') }}" class="hover:text-blue-600">Kelola Kredit</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-gray-900 font-medium">Tambah Kredit</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Kredit Baru</h1>
            <p class="text-sm text-gray-600 mt-1">Buat catatan kredit baru untuk pelanggan</p>
        </div>
        <a href="{{ route('admin.credits.manual.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Info Box -->
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-5">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-blue-900 mb-1">📝 Panduan Pengisian</h3>
                <p class="text-sm text-blue-800 mb-2">
                    Anda bebas mengatur <strong>bunga</strong> dan <strong>jangka waktu</strong> sesuai kesepakatan dengan pelanggan.
                    Sistem akan otomatis menghitung total kredit dan cicilan per bulan.
                </p>
                <div class="text-xs text-blue-700 bg-blue-100 rounded-lg p-2 mt-2">
                    <strong>Contoh:</strong> Pinjaman Rp 10.000.000 dengan bunga 12% per tahun selama 12 bulan = Cicilan Rp 933.333/bulan
                </div>
            </div>
        </div>
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

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6"
         x-data="{
             loanAmount: {{ old('loan_amount', 0) }},
             downPayment: {{ old('down_payment', 0) }},
             interestRate: {{ old('interest_rate', 0) }},
             months: {{ old('installment_months', 12) }},
             get principalAmount() {
                 return this.loanAmount - this.downPayment;
             },
             get interestAmount() {
                 return (this.principalAmount * this.interestRate / 100) * (this.months / 12);
             },
             get totalAmount() {
                 return this.principalAmount + this.interestAmount;
             },
             get monthlyInstallment() {
                 return this.months > 0 ? this.totalAmount / this.months : 0;
             }
         }">
        <form action="{{ route('admin.credits.manual.store') }}" method="POST">
            @csrf

            <!-- Info Pelanggan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Pelanggan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pelanggan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="customer_name" id="customer_name"
                            value="{{ old('customer_name') }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('customer_name') border-red-300 @enderror"
                            placeholder="Masukkan nama pelanggan" required>
                        @error('customer_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="text" name="customer_phone" id="customer_phone"
                            value="{{ old('customer_phone') }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('customer_phone') border-red-300 @enderror"
                            placeholder="Contoh: 08123456789">
                        @error('customer_phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Pelanggan
                        </label>
                        <textarea name="customer_address" id="customer_address" rows="3"
                            class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('customer_address') border-red-300 @enderror"
                            placeholder="Masukkan alamat lengkap pelanggan">{{ old('customer_address') }}</textarea>
                        @error('customer_address')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Info Kredit -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Kredit</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan / Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-300 @enderror"
                            placeholder="Contoh: Kredit pembelian kulkas 2 pintu" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="loan_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pinjaman <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="loan_amount" id="loan_amount"
                                x-model.number="loanAmount"
                                value="{{ old('loan_amount') }}" min="1" step="1000"
                                class="w-full text-sm border border-gray-200 rounded-lg pl-12 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('loan_amount') border-red-300 @enderror"
                                placeholder="0" required>
                        </div>
                        @error('loan_amount')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="interest_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            Tingkat Bunga (% per tahun) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="interest_rate" id="interest_rate"
                                x-model.number="interestRate"
                                value="{{ old('interest_rate', 0) }}" min="0" max="100" step="0.01"
                                class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('interest_rate') border-red-300 @enderror"
                                placeholder="0" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                        @error('interest_rate')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Contoh: 12 untuk bunga 12% per tahun</p>
                    </div>

                    <div>
                        <label for="installment_months" class="block text-sm font-medium text-gray-700 mb-2">
                            Jangka Waktu (Bulan) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="installment_months" id="installment_months"
                                x-model.number="months"
                                value="{{ old('installment_months', 12) }}" min="1" max="360" step="1"
                                class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 pr-20 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('installment_months') border-red-300 @enderror"
                                placeholder="12" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">bulan</span>
                        </div>
                        @error('installment_months')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="down_payment" class="block text-sm font-medium text-gray-700 mb-2">
                            Uang Muka (DP)
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="down_payment" id="down_payment"
                                x-model.number="downPayment"
                                value="{{ old('down_payment', 0) }}" min="0" step="1000"
                                class="w-full text-sm border border-gray-200 rounded-lg pl-12 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('down_payment') border-red-300 @enderror"
                                placeholder="0">
                        </div>
                        @error('down_payment')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', date('Y-m-d')) }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('start_date') border-red-300 @enderror"
                            required>
                        @error('start_date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Ringkasan Perhitungan -->
            <div class="mb-8 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-blue-200">Ringkasan Perhitungan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-blue-100">
                        <span class="text-sm text-gray-600">Pokok Pinjaman:</span>
                        <span class="text-sm font-semibold text-gray-900">Rp<span x-text="principalAmount.toLocaleString('id-ID')">0</span></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-blue-100">
                        <span class="text-sm text-gray-600">Bunga:</span>
                        <span class="text-sm font-semibold text-orange-600">Rp<span x-text="interestAmount.toLocaleString('id-ID')">0</span></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md text-white md:col-span-2">
                        <span class="text-sm font-medium">Total Kredit:</span>
                        <span class="text-lg font-bold">Rp<span x-text="totalAmount.toLocaleString('id-ID')">0</span></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md text-white md:col-span-2">
                        <span class="text-sm font-medium">Cicilan per Bulan:</span>
                        <span class="text-lg font-bold">Rp<span x-text="monthlyInstallment.toLocaleString('id-ID')">0</span></span>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-3 italic">
                    * Perhitungan ini bersifat otomatis berdasarkan input Anda. Pastikan semua data sudah benar sebelum menyimpan.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.credits.manual.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Kredit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
