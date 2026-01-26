@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Kelola User</h1>
        <p class="text-gray-600 mt-2">Kelola akun pengguna biasa (pembeli produk)</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
        Tambah User
    </a>
</div>

<!-- Info Alert -->
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                <strong>Info:</strong> Halaman ini hanya untuk mengelola <strong>user biasa (role: user)</strong>.
                Untuk mengelola <strong>Admin Desa</strong> atau <strong>SuperAdmin</strong>, gunakan menu
                <a href="{{ route('admin.admins.index') }}" class="underline hover:text-blue-800 font-semibold">"Kelola Admin"</a>.
            </p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                <p class="text-sm text-gray-600">Total User Biasa</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama, email, telepon..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Search Button -->
        <div class="flex items-end">
            <button type="submit" class="w-full bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                Cari
            </button>
        </div>
    </form>
</div>

<!-- Removed Role Filter (tidak perlu lagi karena hanya user biasa) -->
<!-- <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                <option value="">Semua Role</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
            </select>
        </div>
        
        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                Reset
            </a>
        </div>
    </form>
</div>

@if($users->count() > 0)
    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 {{ $user->id === auth()->id() ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Anda</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->phone ?? '-' }}</div>
                                @if($user->address)
                                    <div class="text-sm text-gray-500">{{ Str::limit($user->address, 30) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    User Biasa
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->carts->count() }} keranjang</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-800">Lihat</a>
                                @if($user->id !== auth()->id())
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="text-green-600 hover:text-green-800">Edit</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                @else
                                    <a href="{{ route('profile') }}" class="text-purple-600 hover:text-purple-800">Profile</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">
            {{ request()->hasAny(['search', 'role']) ? 'User Tidak Ditemukan' : 'Belum Ada User' }}
        </h3>
        <p class="text-gray-600 mb-6">
            {{ request()->hasAny(['search', 'role']) ? 'Tidak ada user yang cocok dengan filter yang dipilih.' : 'Belum ada user yang terdaftar di sistem.' }}
        </p>
        @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('admin.users.index') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Lihat Semua User
            </a>
        @else
            <a href="{{ route('admin.users.create') }}" 
               class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                Tambah User Pertama
            </a>
        @endif
    </div>
@endif
@endsection