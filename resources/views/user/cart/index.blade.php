@extends('layouts.app')

@section('title', 'Keranjang Belanja - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Kelola produk yang akan Anda beli</p>
        </div>

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b flex items-center gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" id="select-all" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="toggleSelectAll()">
                                <span class="ml-2 text-sm font-medium text-gray-700">Pilih Semua</span>
                            </label>
                            <h2 class="text-lg font-semibold text-gray-900">Produk dalam Keranjang ({{ $cartItems->count() }})</h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <div class="p-4 sm:p-6 {{ $item->is_selected ? 'bg-green-50/30' : '' }}">
                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                        <!-- Checkbox -->
                                        <div class="flex-shrink-0">
                                            <input type="checkbox"
                                                   class="item-checkbox w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer"
                                                   data-cart-id="{{ $item->id }}"
                                                   {{ $item->is_selected ? 'checked' : '' }}
                                                   onchange="toggleItemSelection({{ $item->id }})">
                                        </div>

                                        <!-- Product Image & Info -->
                                        <div class="flex items-start space-x-3 sm:space-x-4 flex-1">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('products.show', $item->product) }}">
                                                    @if($item->product->images && count($item->product->images) > 0)
                                                        <img src="{{ $item->product->getImageDataUri(0) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg">
                                                    @else
                                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </a>
                                            </div>
                                            
                                            <!-- Product Info -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">
                                                    <a href="{{ route('products.show', $item->product) }}" class="hover:text-green-600 line-clamp-2">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-xs sm:text-sm text-gray-500 mb-1">{{ $item->product->category->name }}</p>
                                                <p class="text-sm sm:text-lg font-bold text-green-600">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Controls & Actions -->
                                        <div class="flex flex-col gap-3">
                                            <!-- Quantity Controls -->
                                            <div class="flex items-center justify-center">
                                                <form action="{{ route('user.cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="flex items-center border rounded-lg">
                                                        <button type="button" onclick="decrementQuantity({{ $item->id }})" 
                                                                class="bg-gray-100 text-gray-700 px-3 py-2 hover:bg-gray-200 text-sm">-</button>
                                                        <input type="number" id="quantity-{{ $item->id }}" name="quantity" 
                                                               value="{{ $item->quantity }}" min="1" max="999"
                                                               class="w-16 px-2 py-2 text-center border-0 focus:outline-none text-sm font-semibold">
                                                        <button type="button" onclick="incrementQuantity({{ $item->id }})" 
                                                                class="bg-gray-100 text-gray-700 px-3 py-2 hover:bg-gray-200 text-sm">+</button>
                                                    </div>
                                                    <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-xs hover:bg-blue-700">
                                                        Update
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <!-- Subtotal -->
                                            <div class="text-center">
                                                <p class="text-base font-bold text-gray-900">
                                                    Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            
                                            <!-- Remove Button -->
                                            <div class="flex flex-col gap-2">
                                                <form action="{{ route('user.cart.remove', $item) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus produk ini dari keranjang?')"
                                                            class="w-full bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 lg:sticky lg:top-8">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>

                        @if($hasUnConfiguredShipping)
                        <!-- Warning for unconfigured shipping -->
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Tidak Dapat Checkout</h3>
                                    <p class="mt-1 text-xs text-red-700">
                                        Lokasi pengiriman toko belum dikonfigurasi. Silakan hubungi admin untuk informasi lebih lanjut.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm sm:text-base">
                                <span class="text-gray-600">Total Item di Keranjang</span>
                                <span class="font-semibold">{{ $cartItems->count() }} produk</span>
                            </div>
                            <div class="flex justify-between text-sm sm:text-base">
                                <span class="text-gray-600">Item Dipilih</span>
                                <span class="font-semibold text-green-600" id="selected-count">{{ $selectedCount }} produk</span>
                            </div>
                            <hr>
                            <div class="flex justify-between text-base sm:text-lg">
                                <span class="text-gray-900 font-semibold">Total Harga</span>
                                <span class="font-bold text-green-600" id="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if($hasUnConfiguredShipping || $selectedCount == 0)
                            <button disabled
                               class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg font-semibold cursor-not-allowed text-center block flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                {{ $hasUnConfiguredShipping ? 'Lokasi Pengiriman Belum Diatur' : 'Pilih Produk Terlebih Dahulu' }}
                            </button>
                            @else
                            <a href="{{ route('user.orders.checkout') }}"
                               class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors text-center block flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Lanjut ke Checkout
                            </a>
                            @endif
                            <a href="{{ route('products.index') }}"
                               class="w-full bg-white border-2 border-green-600 text-green-600 py-3 px-4 rounded-lg font-semibold hover:bg-green-50 transition-colors text-center block">
                                Lanjut Belanja
                            </a>
                        </div>

                        <!-- Checkout Info -->
                        <div class="mt-6 p-4 bg-green-50 rounded-lg">
                            <h3 class="font-semibold text-green-900 mb-3 text-sm">🛒 Cara Checkout:</h3>
                            <ul class="text-xs text-green-800 space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="text-green-600 font-bold">1.</span>
                                    <span>Klik tombol <strong>"Lanjut ke Checkout"</strong></span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-600 font-bold">2.</span>
                                    <span>Isi data pengiriman dan pilih metode pembayaran</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-600 font-bold">3.</span>
                                    <span>Konfirmasi pesanan dan selesaikan pembayaran</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-600 font-bold">4.</span>
                                    <span>Pantau status pesanan di menu Profil</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Benefits -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-blue-900 mb-2 text-sm">✨ Keuntungan Belanja Online:</h3>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• Proses pemesanan mudah & cepat</li>
                                <li>• Transaksi aman & terpercaya</li>
                                <li>• Lacak pesanan real-time</li>
                                <li>• Produk berkualitas & terjangkau</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-lg p-12">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M17 13l-1.5 6M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM20.5 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
                    <p class="text-gray-600 mb-6">Anda belum menambahkan produk apapun ke keranjang</p>
                    <div class="space-x-4">
                        <a href="{{ route('products.index') }}" 
                           class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors inline-block">
                            Mulai Berbelanja
                        </a>
                        <a href="{{ route('home') }}" 
                           class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-50 transition-colors inline-block">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Cart item data for calculation
const cartItems = [
    @foreach($cartItems as $item)
    {
        id: {{ $item->id }},
        price: {{ $item->product->price }},
        quantity: {{ $item->quantity }},
        is_selected: {{ $item->is_selected ? 'true' : 'false' }}
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
];

// Toggle individual item selection
function toggleItemSelection(cartId) {
    fetch(`/cart/${cartId}/toggle-selection`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart item data
            const item = cartItems.find(i => i.id === cartId);
            if (item) {
                item.is_selected = data.is_selected;
            }
            updateSummary();
            updateSelectAllCheckbox();

            // Update row background
            const row = document.querySelector(`[data-cart-id="${cartId}"]`).closest('.p-4');
            if (data.is_selected) {
                row.classList.add('bg-green-50/30');
            } else {
                row.classList.remove('bg-green-50/30');
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Toggle select all
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('select-all');
    const selected = selectAllCheckbox.checked;

    fetch('/cart/select-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ selected: selected })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all checkboxes
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.checked = selected;
                const cartId = parseInt(checkbox.dataset.cartId);
                const item = cartItems.find(i => i.id === cartId);
                if (item) {
                    item.is_selected = selected;
                }

                // Update row background
                const row = checkbox.closest('.p-4');
                if (selected) {
                    row.classList.add('bg-green-50/30');
                } else {
                    row.classList.remove('bg-green-50/30');
                }
            });
            updateSummary();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Update select all checkbox state
function updateSelectAllCheckbox() {
    const selectAllCheckbox = document.getElementById('select-all');
    const allCheckboxes = document.querySelectorAll('.item-checkbox');
    const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;

    selectAllCheckbox.checked = checkedCount === allCheckboxes.length && allCheckboxes.length > 0;
    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
}

// Update summary (selected count and total price)
function updateSummary() {
    const selectedItems = cartItems.filter(item => item.is_selected);
    const selectedCount = selectedItems.length;
    const totalPrice = selectedItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    document.getElementById('selected-count').textContent = `${selectedCount} produk`;
    document.getElementById('total-price').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectAllCheckbox();
});

function incrementQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    const current = parseInt(input.value);

    input.value = current + 1;
}

function decrementQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    const min = parseInt(input.getAttribute('min'));
    const current = parseInt(input.value);

    if (current > min) {
        input.value = current - 1;
    }
}

</script>
@endsection