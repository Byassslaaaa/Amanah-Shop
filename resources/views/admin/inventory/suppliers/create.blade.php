@extends('layouts.admin')

@section('title', 'Tambah Supplier')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.inventory.suppliers.index') }}" class="hover:text-gray-900">Daftar Supplier</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">Tambah Supplier</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Supplier</h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.inventory.suppliers.store') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Supplier <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama supplier" required>
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Person -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kontak Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('contact_person') border-red-500 @enderror"
                    placeholder="Nama kontak person">
                @error('contact_person')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                    placeholder="Contoh: 08123456789">
                @error('phone')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="email@example.com">
                @error('email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="address" rows="3"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                    placeholder="Masukkan alamat lengkap supplier">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                    placeholder="Catatan tambahan tentang supplier...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-200 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Aktifkan supplier</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Simpan Supplier
                </button>
                <a href="{{ route('admin.inventory.suppliers.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
