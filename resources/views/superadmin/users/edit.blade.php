@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Edit User: {{ $user->name }}</h1>
        <p class="text-gray-600 mt-2">Update informasi user</p>
    </div>
    <div class="space-x-3">
        <a href="{{ route('admin.users.show', $user) }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
            Lihat Detail
        </a>
        <a href="{{ route('admin.users.index') }}" 
           class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
            Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
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
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
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
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               placeholder="john@example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <input type="password" id="password" name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password. Minimal 8 karakter jika diisi.</p>
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
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
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
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Saat Ini</h2>
                
                <div class="text-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center mx-auto mb-3">
                        <span class="text-white font-bold text-xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">User ID:</span>
                        <span class="font-medium">#{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bergabung:</span>
                        <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pesanan:</span>
                        <span class="font-medium">{{ $user->orders->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium text-green-600">
                            {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Role & Permissions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Role & Permissions</h2>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role *
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('role') border-red-500 @enderror">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User - Regular Customer</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin - Manage Products & Orders</option>
                        <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Super Admin - Full Access</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Current Role Display -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Role Saat Ini:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $user->role === 'superadmin' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $user->role === 'admin' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $user->role === 'user' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                
                <!-- Role Description -->
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">Deskripsi Role:</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li><strong>User:</strong> Dapat berbelanja dan melihat riwayat pesanan</li>
                        <li><strong>Admin:</strong> Dapat mengelola produk, kategori, dan pesanan</li>
                        <li><strong>Super Admin:</strong> Akses penuh termasuk mengelola user</li>
                    </ul>
                </div>
            </div>
            
            <!-- Submit Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                        Simpan Perubahan
                    </button>
                    
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="w-full bg-gray-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-600 transition-colors text-center block">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Danger Zone -->
@if($user->orders->count() == 0)
    <div class="mt-8 bg-red-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-2">Zona Bahaya</h3>
        <p class="text-red-700 mb-4">
            Menghapus user akan menghapus semua data terkait. Aksi ini tidak dapat dibatalkan.
            User ini tidak memiliki riwayat pesanan sehingga dapat dihapus.
        </p>
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
              onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}? Aksi ini tidak dapat dibatalkan!')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                Hapus User
            </button>
        </form>
    </div>
@else
    <div class="mt-8 bg-yellow-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-yellow-900 mb-2">Informasi Penting</h3>
        <p class="text-yellow-800">
            User ini memiliki {{ $user->orders->count() }} riwayat pesanan sehingga tidak dapat dihapus.
            Ini untuk menjaga integritas data pesanan di sistem.
        </p>
    </div>
@endif
@endsection