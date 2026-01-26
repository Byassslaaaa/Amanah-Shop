@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.admins.index') }}"
               class="text-gray-600 hover:text-gray-800 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Edit Admin</h1>
        </div>
        <p class="text-gray-600 ml-9">Ubah data admin {{ $admin->name }}</p>
    </div>

    <!-- Form -->
    <div class="max-w-3xl">
        <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="bg-white rounded-xl shadow-md p-8">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $admin->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap"
                       required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $admin->email) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                       placeholder="admin@bumdes.com"
                       required>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nomor Telepon
                </label>
                <input type="text"
                       id="phone"
                       name="phone"
                       value="{{ old('phone', $admin->phone) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                       placeholder="081234567890">
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role"
                        name="role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('role') border-red-500 @enderror"
                        required
                        onchange="toggleVillageField(this.value)">
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Admin Desa</option>
                    <option value="superadmin" {{ old('role', $admin->role) === 'superadmin' ? 'selected' : '' }}>SuperAdmin</option>
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">
                    <strong>Admin Desa:</strong> Hanya bisa mengelola desa yang dipilih<br>
                    <strong>SuperAdmin:</strong> Bisa mengelola semua desa
                </p>
            </div>

            <!-- Village (hanya tampil jika role = admin) -->
            <div id="village-field" class="mb-6" style="display: {{ old('role', $admin->role) === 'admin' ? 'block' : 'none' }};">
                <label for="village_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Desa <span class="text-red-500">*</span>
                </label>
                <select id="village_id"
                        name="village_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('village_id') border-red-500 @enderror">
                    <option value="">Pilih Desa</option>
                    @foreach($villages as $village)
                        <option value="{{ $village->id }}" {{ old('village_id', $admin->village_id) == $village->id ? 'selected' : '' }}>
                            {{ $village->name }} - {{ $village->district }}, {{ $village->city }}
                        </option>
                    @endforeach
                </select>
                @error('village_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">Pilih desa yang akan dikelola oleh admin ini</p>
            </div>

            <hr class="my-8 border-gray-200">

            <!-- Password Section -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800 font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Kosongkan jika tidak ingin mengubah password
                </p>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password Baru
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                       placeholder="Minimal 3 karakter (opsional)">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Ulangi password (opsional)">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold shadow-md transition duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Admin
                </button>
                <a href="{{ route('admin.admins.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleVillageField(role) {
    const villageField = document.getElementById('village-field');
    const villageSelect = document.getElementById('village_id');

    if (role === 'admin') {
        villageField.style.display = 'block';
        villageSelect.required = true;
    } else {
        villageField.style.display = 'none';
        villageSelect.required = false;
        villageSelect.value = '';
    }
}

// Trigger on page load
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    if (roleSelect.value) {
        toggleVillageField(roleSelect.value);
    }
});
</script>
@endsection
