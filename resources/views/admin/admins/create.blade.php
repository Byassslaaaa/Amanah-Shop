@extends('layouts.admin')

@section('title', 'Tambah Admin')

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
            <h1 class="text-3xl font-bold text-gray-800">Tambah Admin Baru</h1>
        </div>
        <p class="text-gray-600 ml-9">Buat akun admin atau superadmin baru</p>
    </div>

    <!-- Form -->
    <div class="max-w-3xl">
        <form action="{{ route('admin.admins.store') }}" method="POST" class="bg-white rounded-xl shadow-md p-8">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
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
                       value="{{ old('email') }}"
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
                       value="{{ old('phone') }}"
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
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Desa</option>
                    <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>SuperAdmin</option>
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">
                    <strong>Admin Desa:</strong> Hanya bisa mengelola desa yang dipilih<br>
                    <strong>SuperAdmin:</strong> Bisa mengelola semua desa
                </p>
            </div>

            <!-- Village (hanya aktif jika role = admin) -->
            <div id="village-field" class="mb-6">
                <label for="village_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Desa <span id="village-required-mark" class="text-red-500" style="display: {{ old('role') === 'admin' ? 'inline' : 'none' }};">*</span>
                </label>
                <select id="village_id"
                        name="village_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('village_id') border-red-500 @enderror"
                        {{ old('role') !== 'admin' ? 'disabled' : '' }}>
                    <option value="">Pilih Desa (pilih role terlebih dahulu)</option>
                    @foreach($villages as $village)
                        <option value="{{ $village->id }}" {{ old('village_id') == $village->id ? 'selected' : '' }}>
                            {{ $village->name }} - {{ $village->district }}, {{ $village->city }}
                        </option>
                    @endforeach
                </select>
                @error('village_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="village-help-text" class="mt-2 text-xs text-gray-500">
                    {{ old('role') === 'admin' ? 'Pilih desa yang akan dikelola oleh admin ini' : 'Field ini akan aktif setelah Anda memilih role "Admin Desa"' }}
                </p>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                       placeholder="Minimal 3 karakter"
                       required>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Ulangi password"
                       required>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold shadow-md transition duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Admin
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
    const villageSelect = document.getElementById('village_id');
    const villageRequiredMark = document.getElementById('village-required-mark');
    const villageHelpText = document.getElementById('village-help-text');

    if (role === 'admin') {
        // Enable field untuk Admin Desa
        villageSelect.disabled = false;
        villageSelect.required = true;
        villageRequiredMark.style.display = 'inline';
        villageHelpText.textContent = 'Pilih desa yang akan dikelola oleh admin ini';

        // Update placeholder
        villageSelect.options[0].text = 'Pilih Desa';
    } else {
        // Disable field untuk SuperAdmin atau belum pilih
        villageSelect.disabled = true;
        villageSelect.required = false;
        villageSelect.value = '';
        villageRequiredMark.style.display = 'none';

        if (role === 'superadmin') {
            villageHelpText.textContent = 'SuperAdmin tidak memerlukan desa (dapat mengelola semua desa)';
            villageSelect.options[0].text = 'Tidak diperlukan untuk SuperAdmin';
        } else {
            villageHelpText.textContent = 'Field ini akan aktif setelah Anda memilih role "Admin Desa"';
            villageSelect.options[0].text = 'Pilih Desa (pilih role terlebih dahulu)';
        }
    }
}

// Trigger on page load if old value exists
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    if (roleSelect.value) {
        toggleVillageField(roleSelect.value);
    }
});
</script>
@endsection
