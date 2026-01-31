@extends('layouts.admin')

@section('title', 'Daftar Supplier')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Supplier</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola supplier dan mitra bisnis</p>
        </div>
        <a href="{{ route('admin.inventory.suppliers.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Supplier
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.inventory.suppliers.index') }}" class="flex items-center space-x-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Cari supplier...">
            </div>
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.inventory.suppliers.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Suppliers Grid -->
    @if($suppliers->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @foreach($suppliers as $supplier)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <h3 class="text-base font-semibold text-gray-900">{{ $supplier->name }}</h3>
                            @if($supplier->is_active)
                            <span class="px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Aktif</span>
                            @else
                            <span class="px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Nonaktif</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">{{ $supplier->code }}</p>
                    </div>
                </div>

                @if($supplier->contact_person)
                <div class="mb-3">
                    <p class="text-xs text-gray-500">Kontak Person</p>
                    <p class="text-sm text-gray-900">{{ $supplier->contact_person }}</p>
                </div>
                @endif

                @if($supplier->phone)
                <div class="mb-3">
                    <p class="text-xs text-gray-500">Telepon</p>
                    <p class="text-sm text-gray-900">{{ $supplier->phone }}</p>
                </div>
                @endif

                @if($supplier->email)
                <div class="mb-3">
                    <p class="text-xs text-gray-500">Email</p>
                    <p class="text-sm text-gray-900">{{ $supplier->email }}</p>
                </div>
                @endif

                <div class="flex items-center space-x-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.inventory.suppliers.show', $supplier) }}"
                       class="flex-1 px-3 py-2 text-xs font-medium text-center text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        Detail
                    </a>
                    <a href="{{ route('admin.inventory.suppliers.edit', $supplier) }}"
                       class="flex-1 px-3 py-2 text-xs font-medium text-center text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.inventory.suppliers.toggle-status', $supplier) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-2 text-xs font-medium text-center {{ $supplier->is_active ? 'text-red-600 bg-red-50 hover:bg-red-100' : 'text-green-600 bg-green-50 hover:bg-green-100' }} rounded-lg transition-colors">
                            {{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        {{ $suppliers->links() }}
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada supplier</h3>
        <p class="text-gray-500 mb-4">Mulai tambahkan supplier untuk mengelola pembelian.</p>
        <a href="{{ route('admin.inventory.suppliers.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
            Tambah Supplier Pertama
        </a>
    </div>
    @endif
</div>
@endsection
