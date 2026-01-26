@extends('layouts.admin')

@section('title', 'Edit Desa')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Desa/Bumdes</h1>
        <p class="text-gray-600 mt-2">Perbarui informasi desa atau bumdes</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.villages.update', $village) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Desa -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Desa/Bumdes *</label>
                    <input type="text" name="name" value="{{ old('name', $village->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror">{{ old('description', $village->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="address" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">{{ old('address', $village->address) }}</textarea>
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province', $village->province) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Kota/Kabupaten -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                    <input type="text" name="city" value="{{ old('city', $village->city) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Kecamatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                    <input type="text" name="district" value="{{ old('district', $village->district) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $village->phone) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $village->email) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $village->whatsapp) }}" placeholder="628xxxxxxxxxx"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Logo URL (optional for now) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo URL (opsional)</label>
                    <input type="text" name="logo" value="{{ old('logo', $village->logo) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    <p class="text-sm text-gray-500 mt-1">Masukkan URL gambar logo desa</p>
                </div>
            </div>

            <!-- Lokasi Asal Pengiriman Section -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lokasi Asal Pengiriman</h3>
                <p class="text-sm text-gray-600 mb-6">Setting lokasi ini untuk perhitungan ongkos kirim dari desa ke pembeli</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Provinsi Asal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi Asal *</label>
                        <select name="origin_province_id" id="origin_province_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Provinsi</option>
                        </select>
                        <input type="hidden" name="origin_province_name" id="origin_province_name" value="{{ old('origin_province_name', $village->origin_province_name) }}">
                        @error('origin_province_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kota/Kabupaten Asal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten Asal *</label>
                        <select name="origin_city_id" id="origin_city_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Kota</option>
                        </select>
                        <input type="hidden" name="origin_city_name" id="origin_city_name" value="{{ old('origin_city_name', $village->origin_city_name) }}">
                        @error('origin_city_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" name="origin_postal_code" id="origin_postal_code"
                            value="{{ old('origin_postal_code', $village->origin_postal_code) }}"
                            placeholder="Contoh: 12345"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            <option value="active" {{ old('status', $village->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $village->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.villages.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                    Perbarui Desa
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript for RajaOngkir Integration -->
    <script>
        const savedProvinceId = '{{ old('origin_province_id', $village->origin_province_id) }}';
        const savedCityId = '{{ old('origin_city_id', $village->origin_city_id) }}';

        // Load provinces on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProvinces();
        });

        // Load provinces from RajaOngkir API
        async function loadProvinces() {
            try {
                const response = await fetch('/api/rajaongkir/provinces');
                const data = await response.json();

                if (data.success) {
                    const select = document.getElementById('origin_province_id');
                    select.innerHTML = '<option value="">Pilih Provinsi</option>';

                    data.data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.province_id;
                        option.textContent = province.province;
                        option.dataset.name = province.province;

                        if (savedProvinceId && province.province_id == savedProvinceId) {
                            option.selected = true;
                        }

                        select.appendChild(option);
                    });

                    // If there's saved province, load its cities
                    if (savedProvinceId) {
                        loadCities(savedProvinceId);
                    }
                }
            } catch (error) {
                console.error('Error loading provinces:', error);
                alert('Gagal memuat data provinsi');
            }
        }

        // Load cities when province changes
        document.getElementById('origin_province_id').addEventListener('change', function() {
            const provinceId = this.value;
            const provinceName = this.options[this.selectedIndex]?.dataset.name || '';
            document.getElementById('origin_province_name').value = provinceName;

            if (provinceId) {
                loadCities(provinceId);
            } else {
                const citySelect = document.getElementById('origin_city_id');
                citySelect.innerHTML = '<option value="">Pilih Kota</option>';
                document.getElementById('origin_city_name').value = '';
            }
        });

        // Load cities by province ID
        async function loadCities(provinceId) {
            try {
                const response = await fetch(`/api/rajaongkir/cities?province_id=${provinceId}`);
                const data = await response.json();

                if (data.success) {
                    const select = document.getElementById('origin_city_id');
                    select.innerHTML = '<option value="">Pilih Kota</option>';

                    data.data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.city_id;
                        option.textContent = `${city.type} ${city.city_name}`;
                        option.dataset.name = `${city.type} ${city.city_name}`;
                        option.dataset.postalCode = city.postal_code || '';

                        if (savedCityId && city.city_id == savedCityId) {
                            option.selected = true;
                        }

                        select.appendChild(option);
                    });

                    // Trigger change to fill city name and postal code if city is selected
                    if (savedCityId) {
                        select.dispatchEvent(new Event('change'));
                    }
                }
            } catch (error) {
                console.error('Error loading cities:', error);
                alert('Gagal memuat data kota');
            }
        }

        // Update city name and postal code when city changes
        document.getElementById('origin_city_id').addEventListener('change', function() {
            const cityName = this.options[this.selectedIndex]?.dataset.name || '';
            const postalCode = this.options[this.selectedIndex]?.dataset.postalCode || '';

            document.getElementById('origin_city_name').value = cityName;

            // Auto-fill postal code if available and current postal code is empty
            const postalCodeInput = document.getElementById('origin_postal_code');
            if (postalCode && !postalCodeInput.value) {
                postalCodeInput.value = postalCode;
            }
        });
    </script>
@endsection
