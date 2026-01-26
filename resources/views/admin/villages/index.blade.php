@extends('layouts.admin')

@section('title', 'Kelola Desa')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Desa/Bumdes</h1>
            <p class="text-gray-600 mt-2">Kelola semua desa dan bumdes di marketplace</p>
        </div>
        <a href="{{ route('admin.villages.create') }}"
            class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Tambah Desa
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Villages List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Desa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($villages as $village)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $village->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $village->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $village->district }}, {{ $village->city }}</div>
                            <div class="text-sm text-gray-500">{{ $village->province }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $village->products()->count() }} Produk
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $village->phone }}</div>
                            <div class="text-sm text-gray-500">{{ $village->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.villages.toggle-status', $village) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $village->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $village->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.villages.show', $village) }}"
                                class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                            <a href="{{ route('admin.villages.edit', $village) }}"
                                class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            <form action="{{ route('admin.villages.destroy', $village) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus desa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada desa terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($villages->hasPages())
        <div class="mt-6">
            {{ $villages->links() }}
        </div>
    @endif
@endsection
