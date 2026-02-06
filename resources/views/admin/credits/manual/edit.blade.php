@extends('layouts.admin')

@section('title', 'Edit Data Kredit')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('admin.credits.manual.index') }}" class="hover:text-blue-600">Kelola Kredit</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-gray-900 font-medium">Edit</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Data Kredit</h1>
            <p class="text-sm text-gray-600 mt-1">Perbarui data kredit {{ $credit->credit_number }}</p>
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
        <!-- Form Edit (2 cols) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Edit Data Pelanggan</h2>
                <form action="{{ route('admin.credits.manual.update', $credit) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pelanggan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_name" id="customer_name"
                                value="{{ old('customer_name', $credit->customer_name) }}"
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
                                value="{{ old('customer_phone', $credit->customer_phone) }}"
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
                                placeholder="Masukkan alamat lengkap pelanggan">{{ old('customer_address', $credit->customer_address) }}</textarea>
                            @error('customer_address')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan / Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-300 @enderror"
                                placeholder="Keterangan kredit" required>{{ old('description', $credit->description) }}</textarea>
                            @error('description')
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
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Kredit (read-only sidebar) -->
        <div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Detail Kredit</h2>
                <p class="text-xs text-gray-500 mb-4">Informasi berikut tidak dapat diubah.</p>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">No. Kredit</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $credit->credit_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Jumlah Pinjaman</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">Rp{{ number_format($credit->loan_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Kredit</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">Rp{{ number_format($credit->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Tenor & Bunga</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $credit->installment_months }} bulan ({{ $credit->interest_rate }}% p.a.)</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                        <div class="mt-1">
                            @switch($credit->status)
                                @case('active')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Aktif</span>
                                    @break
                                @case('completed')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Selesai</span>
                                    @break
                                @case('overdue')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Jatuh Tempo</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Dibatalkan</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
