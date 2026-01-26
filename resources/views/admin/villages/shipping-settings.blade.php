@extends('layouts.admin')

@section('title', 'Lokasi Pengiriman Desa')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b bg-gradient-to-r from-green-600 to-green-700">
            <h2 class="text-xl font-bold text-white">Pengaturan Lokasi Pengiriman</h2>
            <p class="text-green-100 text-sm mt-1">Atur lokasi asal pengiriman untuk semua produk dari desa Anda</p>
        </div>

        <!-- Info Alert -->
        <div class="mx-6 mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3 flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-blue-800">Mengapa harus mengatur lokasi pengiriman?</h3>
                        <button type="button" id="clearCacheBtn" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition-colors">
                            Refresh Data
                        </button>
                    </div>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Lokasi ini digunakan untuk menghitung ongkos kirim otomatis</li>
                            <li>Semua produk dari desa Anda akan menggunakan lokasi yang sama</li>
                            <li>Pembeli dapat melihat estimasi ongkir sebelum checkout</li>
                            <li>Tanpa setting ini, produk Anda tidak dapat dibeli secara online</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.shipping-settings.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Current Village Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Desa Anda:</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $village->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $village->address }}</p>
                </div>

                <!-- Province -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <select name="origin_province_id" id="province_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Pilih Provinsi</option>
                    </select>
                    <input type="hidden" name="origin_province_name" id="province_name" value="{{ $village->origin_province_name }}">
                    @error('origin_province_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kota/Kabupaten <span class="text-red-500">*</span>
                    </label>
                    <select name="origin_city_id" id="city_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                    <input type="hidden" name="origin_city_name" id="city_name" value="{{ $village->origin_city_name }}">
                    @error('origin_city_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Postal Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Pos
                    </label>
                    <input type="text" name="origin_postal_code" id="postal_code"
                           value="{{ old('origin_postal_code', $village->origin_postal_code) }}"
                           placeholder="Akan terisi otomatis"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Kode pos akan terisi otomatis saat Anda memilih kota</p>
                    @error('origin_postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Coordinates (Biteship Required) -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Koordinat Lokasi (Wajib untuk Biteship)</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Koordinat diperlukan untuk menghitung ongkir yang akurat dengan Biteship.</p>
                                <button type="button" id="autoFillCoords" class="mt-2 text-sm bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded transition-colors">
                                    Auto-isi dari Lokasi Kota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Latitude -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="latitude" id="latitude"
                               value="{{ old('latitude', $village->latitude) }}"
                               placeholder="-6.175110"
                               required
                               step="any"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Contoh: -6.175110</p>
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="longitude" id="longitude"
                               value="{{ old('longitude', $village->longitude) }}"
                               placeholder="106.865036"
                               required
                               step="any"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Contoh: 106.865036</p>
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Settings Display -->
                @if($village->origin_city_id || $village->latitude)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-green-800 mb-2">✅ Lokasi Pengiriman Saat Ini:</h3>
                    <div class="text-sm text-green-700 space-y-1">
                        @if($village->origin_province_name)
                        <p><span class="font-medium">Provinsi:</span> {{ $village->origin_province_name }}</p>
                        @endif
                        @if($village->origin_city_name)
                        <p><span class="font-medium">Kota:</span> {{ $village->origin_city_name }}</p>
                        @endif
                        @if($village->origin_postal_code)
                        <p><span class="font-medium">Kode Pos:</span> {{ $village->origin_postal_code }}</p>
                        @endif
                        @if($village->latitude && $village->longitude)
                        <p><span class="font-medium">Koordinat:</span> {{ $village->latitude }}, {{ $village->longitude }}</p>
                        @else
                        <p class="text-red-600"><span class="font-medium">⚠️ Koordinat:</span> Belum diatur (wajib untuk Biteship)</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mt-8 pt-6 border-t">
                <button type="submit"
                        class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Lokasi Pengiriman
                </button>
                <a href="{{ route('admin.dashboard') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Store current values
const currentProvinceId = '{{ $village->origin_province_id }}';
const currentCityId = '{{ $village->origin_city_id }}';

// Load provinces on page load
document.addEventListener('DOMContentLoaded', function() {
    loadProvinces();
});

// Load provinces from Raja Ongkir API
async function loadProvinces() {
    try {
        const response = await fetch('/api/rajaongkir/provinces');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('province_id');
            select.innerHTML = '<option value="">Pilih Provinsi</option>';

            data.data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.province_id;
                option.textContent = province.province;
                option.dataset.name = province.province;

                // Select current province if exists
                if (province.province_id == currentProvinceId) {
                    option.selected = true;
                }

                select.appendChild(option);
            });

            // Load cities if province is already selected
            if (currentProvinceId) {
                loadCities(currentProvinceId);
            }
        }
    } catch (error) {
        console.error('Error loading provinces:', error);
        alert('Gagal memuat data provinsi. Silakan refresh halaman.');
    }
}

// When province changes, load cities
document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const provinceName = this.options[this.selectedIndex]?.dataset.name || '';
    document.getElementById('province_name').value = provinceName;

    if (provinceId) {
        loadCities(provinceId);
    } else {
        const citySelect = document.getElementById('city_id');
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        document.getElementById('city_name').value = '';
        document.getElementById('postal_code').value = '';
    }
});

// Load cities based on province
async function loadCities(provinceId) {
    try {
        const response = await fetch(`/api/rajaongkir/cities?province_id=${provinceId}`);
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('city_id');
            select.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';

            data.data.forEach(city => {
                const option = document.createElement('option');
                option.value = city.city_id;
                option.textContent = `${city.type} ${city.city_name}`;
                option.dataset.name = `${city.type} ${city.city_name}`;
                option.dataset.postalCode = city.postal_code || '';

                // Select current city if exists
                if (city.city_id == currentCityId) {
                    option.selected = true;
                    // Set postal code if available
                    if (city.postal_code) {
                        document.getElementById('postal_code').value = city.postal_code;
                    }
                }

                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading cities:', error);
        alert('Gagal memuat data kota. Silakan coba lagi.');
    }
}

// When city changes, auto-fill postal code
document.getElementById('city_id').addEventListener('change', function() {
    const cityName = this.options[this.selectedIndex]?.dataset.name || '';
    const postalCode = this.options[this.selectedIndex]?.dataset.postalCode || '';

    document.getElementById('city_name').value = cityName;

    // Auto-fill postal code if available
    if (postalCode) {
        document.getElementById('postal_code').value = postalCode;
    }
});

// Auto-fill coordinates from Biteship
document.getElementById('autoFillCoords').addEventListener('click', async function() {
    const btn = this;
    const cityName = document.getElementById('city_name').value;

    if (!cityName) {
        alert('Silakan pilih Kota/Kabupaten terlebih dahulu');
        return;
    }

    try {
        btn.disabled = true;
        btn.textContent = 'Mencari koordinat...';
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        const response = await fetch(`/api/biteship/postal-code/search?q=${encodeURIComponent(cityName)}`);
        const data = await response.json();

        if (data.success && data.data.length > 0) {
            const area = data.data[0];
            document.getElementById('latitude').value = area.latitude;
            document.getElementById('longitude').value = area.longitude;

            if (area.postal_code && !document.getElementById('postal_code').value) {
                document.getElementById('postal_code').value = area.postal_code;
            }

            btn.textContent = '✓ Berhasil!';
            btn.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
            btn.classList.add('bg-green-600');

            setTimeout(() => {
                btn.textContent = 'Auto-isi dari Lokasi Kota';
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
            }, 2000);
        } else {
            alert('Koordinat tidak ditemukan. Silakan isi manual atau pilih kota yang lebih spesifik.');
            btn.textContent = 'Auto-isi dari Lokasi Kota';
        }
    } catch (error) {
        console.error('Error getting coordinates:', error);
        alert('Gagal mendapatkan koordinat. Silakan coba lagi atau isi manual.');
        btn.textContent = 'Auto-isi dari Lokasi Kota';
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
});

// Clear cache button handler
document.getElementById('clearCacheBtn').addEventListener('click', async function() {
    const btn = this;
    const originalText = btn.textContent;

    if (!confirm('Apakah Anda yakin ingin me-refresh data? Ini akan membersihkan cache dan memuat ulang data dari API.')) {
        return;
    }

    try {
        btn.disabled = true;
        btn.textContent = 'Memproses...';
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        const response = await fetch('{{ route("admin.shipping-settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            // Reload provinces after cache clear
            await loadProvinces();
            btn.textContent = '✓ Berhasil';
            setTimeout(() => {
                btn.textContent = originalText;
            }, 2000);
        } else {
            alert('Error: ' + data.message);
            btn.textContent = originalText;
        }
    } catch (error) {
        console.error('Error clearing cache:', error);
        alert('Terjadi kesalahan saat membersihkan cache. Silakan coba lagi.');
        btn.textContent = originalText;
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
});
</script>
@endsection
