@extends('layouts.admin')

@section('title', 'Detail User: ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- User Header -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
                    </div>
                    
                    @if($user->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nomor Telepon</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->phone }}</p>
                        </div>
                    @endif
                </div>
                
                <!-- Account Info -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Role</label>
                        <span class="mt-1 inline-flex px-3 py-1 rounded-full text-sm font-semibold
                            {{ $user->role === 'superadmin' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'user' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status Email</label>
                        <span class="mt-1 inline-flex px-3 py-1 rounded-full text-sm font-semibold
                            {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Bergabung Sejak</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Cart Items -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-gray-900">Keranjang User</h2>
        </div>
        
        @if($user->carts->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($user->carts as $cart)
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            @if($cart->product->images && count($cart->product->images) > 0)
                                <img class="h-12 w-12 rounded-lg object-cover" src="{{ $cart->product->getImageDataUri(0) }}" alt="{{ $cart->product->name }}">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $cart->product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $cart->product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">Qty: {{ $cart->quantity }}</p>
                                <p class="text-sm text-green-600">Rp{{ number_format($cart->product->price) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M17 13l-1.5 6M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM20.5 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Keranjang kosong</h3>
                <p class="text-gray-500">User ini belum menambahkan produk ke keranjang.</p>
            </div>
        @endif
    </div>
</div>
@endsection