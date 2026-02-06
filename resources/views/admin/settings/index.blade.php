@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Pengaturan Sistem</h1>
        <p class="text-gray-600 mt-2">Kelola konfigurasi toko dan pengiriman</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Contact Information Section -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Informasi Kontak
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Admin Email -->
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Admin <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="admin_email"
                               id="admin_email"
                               value="{{ old('admin_email', $settings['admin_email']->value ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('admin_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin WhatsApp -->
                    <div>
                        <label for="admin_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                            WhatsApp Admin <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="admin_whatsapp"
                               id="admin_whatsapp"
                               value="{{ old('admin_whatsapp', $settings['admin_whatsapp']->value ?? '') }}"
                               placeholder="081234567890"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('admin_whatsapp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Shipping Origin Section -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Koordinat Toko (Untuk Perhitungan Ongkir)
                </h2>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-yellow-800 font-semibold">Penting!</p>
                            <p class="text-xs text-yellow-700 mt-1">
                                Koordinat toko digunakan untuk menghitung ongkir pengiriman via Biteship.
                                Pastikan koordinat akurat agar perhitungan ongkir tepat.
                            </p>
                            <p class="text-xs text-yellow-700 mt-2">
                                💡 <strong>Cara mendapatkan koordinat:</strong> Buka Google Maps → Klik kanan lokasi toko → Klik koordinat untuk menyalin
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Latitude -->
                    <div>
                        <label for="shipping_origin_latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               step="0.000001"
                               name="shipping_origin_latitude"
                               id="shipping_origin_latitude"
                               value="{{ old('shipping_origin_latitude', $settings['shipping_origin_latitude']->value ?? '') }}"
                               placeholder="-6.200000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 font-mono"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: -6.200000 (Jakarta)</p>
                        @error('shipping_origin_latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="shipping_origin_longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               step="0.000001"
                               name="shipping_origin_longitude"
                               id="shipping_origin_longitude"
                               value="{{ old('shipping_origin_longitude', $settings['shipping_origin_longitude']->value ?? '') }}"
                               placeholder="106.816666"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 font-mono"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: 106.816666 (Jakarta)</p>
                        @error('shipping_origin_longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Store Address -->
                    <div class="md:col-span-2">
                        <label for="shipping_origin_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Toko <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="shipping_origin_address"
                            id="shipping_origin_address"
                            rows="2"
                            placeholder="Jl. Contoh No. 123, Jakarta"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>{{ old('shipping_origin_address', $settings['shipping_origin_address']->value ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Alamat lengkap toko untuk ditampilkan ke customer</p>
                        @error('shipping_origin_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview Map Link -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2">🗺️ Preview lokasi di Google Maps:</p>
                    <a href="#"
                       id="maps-preview"
                       target="_blank"
                       class="text-blue-600 hover:text-blue-800 text-sm underline">
                        Klik untuk membuka Google Maps
                    </a>
                </div>
            </div>

            <!-- SMTP Configuration (Collapsible) -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center justify-between mb-4 cursor-pointer" onclick="toggleSection('smtp')">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Konfigurasi SMTP (Opsional)
                    </h2>
                    <svg id="smtp-icon" class="w-6 h-6 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div id="smtp-section" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                            <input type="text" name="smtp_host" id="smtp_host"
                                   value="{{ old('smtp_host', $settings['smtp_host']->value ?? '') }}"
                                   placeholder="smtp.gmail.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="number" name="smtp_port" id="smtp_port"
                                   value="{{ old('smtp_port', $settings['smtp_port']->value ?? '') }}"
                                   placeholder="587"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label for="smtp_username" class="block text-sm font-medium text-gray-700 mb-2">Username/Email</label>
                            <input type="text" name="smtp_username" id="smtp_username"
                                   value="{{ old('smtp_username', $settings['smtp_username']->value ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label for="smtp_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="smtp_password" id="smtp_password"
                                   value="{{ old('smtp_password', $settings['smtp_password']->value ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                            <select name="smtp_encryption" id="smtp_encryption"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                <option value="tls" {{ old('smtp_encryption', $settings['smtp_encryption']->value ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption', $settings['smtp_encryption']->value ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim</label>
                            <input type="text" name="mail_from_name" id="mail_from_name"
                                   value="{{ old('mail_from_name', $settings['mail_from_name']->value ?? '') }}"
                                   placeholder="Amanah Shop"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Toggle collapsible sections
function toggleSection(sectionId) {
    const section = document.getElementById(`${sectionId}-section`);
    const icon = document.getElementById(`${sectionId}-icon`);

    section.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Update Google Maps preview link
function updateMapsPreview() {
    const lat = document.getElementById('shipping_origin_latitude').value;
    const lng = document.getElementById('shipping_origin_longitude').value;
    const link = document.getElementById('maps-preview');

    if (lat && lng) {
        link.href = `https://www.google.com/maps?q=${lat},${lng}`;
        link.textContent = `https://www.google.com/maps?q=${lat},${lng}`;
    }
}

// Initialize and add listeners
document.addEventListener('DOMContentLoaded', function() {
    updateMapsPreview();

    document.getElementById('shipping_origin_latitude').addEventListener('input', updateMapsPreview);
    document.getElementById('shipping_origin_longitude').addEventListener('input', updateMapsPreview);
});
</script>
@endpush
@endsection
