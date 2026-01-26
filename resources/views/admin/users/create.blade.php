@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Tambah User</h1>
        <p class="text-gray-600 mt-2">Tambahkan user baru ke sistem</p>
    </div>
    <a href="{{ route('admin.users.index') }}" 
       class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
        Kembali
    </a>
</div>

<form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: John Doe"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               placeholder="john@example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password *
                        </label>
                        <input type="password" id="password" name="password" required
                               placeholder="Minimal 8 karakter"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Password minimal 8 karakter</p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Kontak</h2>
                
                <div class="space-y-4">
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                               placeholder="081234567890"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  placeholder="Alamat lengkap..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Role & Permissions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Role & Permissions</h2>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role *
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('role') border-red-500 @enderror">
                        <option value="">Pilih Role</option>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User - Regular Customer</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin - Manage Products & Orders</option>
                        <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Super Admin - Full Access</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Role Description -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Deskripsi Role:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><strong>User:</strong> Dapat berbelanja dan melihat riwayat pesanan</li>
                        <li><strong>Admin:</strong> Dapat mengelola produk, kategori, dan pesanan</li>
                        <li><strong>Super Admin:</strong> Akses penuh termasuk mengelola user</li>
                    </ul>
                </div>
            </div>
            
            <!-- Account Status -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-green-900">Auto Verified</h3>
                        <p class="text-sm text-green-800">Account akan otomatis diverifikasi</p>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="bg-white rounded-lg shadow p-6">
                <button type="submit" 
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Buat User
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Security Notice -->
<div class="mt-8 bg-yellow-50 rounded-lg p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 14.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
        <div>
            <h3 class="font-semibold text-yellow-900 mb-2">Keamanan Account</h3>
            <ul class="text-sm text-yellow-800 space-y-1">
                <li>• Pastikan email yang digunakan valid dan dapat diakses</li>
                <li>• Gunakan password yang kuat (minimal 8 karakter)</li>
                <li>• Role Super Admin memiliki akses penuh ke sistem</li>
                <li>• User akan mendapat email dengan detail login mereka</li>
            </ul>
        </div>
    </div>
</div>
@endsection