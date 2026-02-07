# 🎨 Frontend Components & Usage Guide

Dokumentasi lengkap untuk komponen frontend yang sudah dibuat dan cara penggunaannya.

---

## 📦 Komponen yang Tersedia

### 1. Flash Message Component
**File**: `resources/views/components/flash-message.blade.php`

Flash message otomatis dengan fitur:
- ✅ Auto-dismiss setelah 5 detik
- ✅ Close button (tombol X)
- ✅ Smooth animation (slide in from right)
- ✅ 4 jenis pesan: success, error, info, warning
- ✅ Accessible (ARIA labels, keyboard navigation)

#### Cara Penggunaan:

Flash message sudah otomatis include di semua layout:
- `layouts/app.blade.php` (User layout)
- `layouts/admin.blade.php` (Admin layout)
- `layouts/guest.blade.php` (Guest layout)

**Di Controller:**
```php
// Success message
return redirect()->back()->with('success', 'Data berhasil disimpan!');

// Error message
return redirect()->back()->with('error', 'Gagal menyimpan data.');

// Info message
return redirect()->back()->with('info', 'Harap verifikasi email Anda.');

// Warning message
return redirect()->back()->with('warning', 'Stok produk hampir habis.');
```

**Preview:**
- Success: Green dengan ikon ✓
- Error: Red dengan ikon X
- Info: Blue dengan ikon ℹ
- Warning: Yellow dengan ikon ⚠

---

### 2. Loading Spinner Component
**File**: `resources/views/components/loading-spinner.blade.php`

Loading indicator yang bisa dikustomisasi dengan berbagai ukuran dan warna.

#### Cara Penggunaan:

**Di Blade Template:**
```blade
{{-- Default (medium green) --}}
<x-loading-spinner />

{{-- Custom size --}}
<x-loading-spinner size="sm" />   {{-- Small --}}
<x-loading-spinner size="md" />   {{-- Medium (default) --}}
<x-loading-spinner size="lg" />   {{-- Large --}}
<x-loading-spinner size="xl" />   {{-- Extra large --}}

{{-- Custom color --}}
<x-loading-spinner color="blue" />
<x-loading-spinner color="red" />
<x-loading-spinner color="gray" />
<x-loading-spinner color="white" />

{{-- Kombinasi --}}
<x-loading-spinner size="lg" color="blue" />
```

**Contoh dalam Button:**
```blade
<button type="submit" class="btn btn-primary" x-data="{ loading: false }" @click="loading = true">
    <span x-show="!loading">Simpan</span>
    <span x-show="loading" x-cloak class="flex items-center gap-2">
        <x-loading-spinner size="sm" color="white" />
        <span>Processing...</span>
    </span>
</button>
```

**Contoh dalam AJAX Call:**
```blade
<div x-data="{ loading: false, data: null }">
    <button @click="loading = true; fetchData()" class="btn">
        Load Data
    </button>

    <div x-show="loading" class="flex justify-center py-8">
        <x-loading-spinner size="lg" />
    </div>

    <div x-show="!loading && data" x-cloak>
        {{-- Display data here --}}
    </div>
</div>
```

---

### 3. Form Validation Helper
**File**: `resources/js/form-validation.js`

Client-side form validation dengan real-time feedback.

#### Cara Penggunaan:

**Auto-init (Recommended):**

Tambahkan attribute `data-validate` pada form:
```blade
<form method="POST" action="{{ route('register') }}" data-validate>
    @csrf

    {{-- Email field --}}
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email"
               name="email"
               id="email"
               required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
    </div>

    {{-- Password field --}}
    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password"
               name="password"
               id="password"
               required
               minlength="8"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
    </div>

    {{-- Password confirmation --}}
    <div class="mb-4">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input type="password"
               name="password_confirmation"
               id="password_confirmation"
               required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
</form>
```

**Manual Init:**
```javascript
import FormValidator from './form-validation';

const form = document.getElementById('my-form');
const validator = new FormValidator(form);
```

#### Fitur Validasi:

1. **Required Fields**
   ```html
   <input type="text" name="name" required>
   ```

2. **Email Format**
   ```html
   <input type="email" name="email" required>
   ```

3. **Number Min/Max**
   ```html
   <input type="number" name="quantity" min="1" max="100" required>
   ```

4. **String Min/Max Length**
   ```html
   <input type="text" name="username" minlength="3" maxlength="20" required>
   ```

5. **Password Confirmation**
   ```html
   <input type="password" name="password" required>
   <input type="password" name="password_confirmation" required>
   ```

#### Validation Behavior:

- **On Blur**: Validates when user leaves the field
- **On Input**: Clears error message when user starts typing
- **On Submit**: Validates all required fields before submission
- **Auto-Focus**: Automatically focuses first error field

#### Error Display:

Validation errors muncul di bawah input field dengan:
- Red border pada input (`border-red-500`)
- Error message text berwarna merah
- ARIA attributes untuk accessibility

---

## 🚀 Contoh Implementasi Lengkap

### Contoh 1: Form Login dengan Validation

```blade
<form method="POST" action="{{ route('login') }}" data-validate class="space-y-4">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email"
               name="email"
               id="email"
               required
               value="{{ old('email') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
               placeholder="email@example.com">
        @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password"
               name="password"
               id="password"
               required
               minlength="8"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
        @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md">
        Login
    </button>
</form>
```

---

### Contoh 2: Button dengan Loading State

```blade
<div x-data="{ loading: false }">
    <form @submit="loading = true" method="POST" action="{{ route('admin.products.store') }}">
        @csrf

        {{-- Form fields here --}}

        <button type="submit"
                :disabled="loading"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="!loading">Simpan Produk</span>
            <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
                <x-loading-spinner size="sm" color="white" />
                <span>Menyimpan...</span>
            </span>
        </button>
    </form>
</div>
```

---

### Contoh 3: AJAX with Loading & Flash Message

```blade
<div x-data="{
    loading: false,
    addToCart(productId) {
        this.loading = true;

        fetch('{{ route('user.cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                window.location.href = '{{ route('user.cart.index') }}';
            }
        })
        .catch(error => {
            alert('Gagal menambahkan ke keranjang');
        })
        .finally(() => {
            this.loading = false;
        });
    }
}">
    <button @click="addToCart({{ $product->id }})"
            :disabled="loading"
            class="btn btn-primary">
        <span x-show="!loading">
            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
        </span>
        <span x-show="loading" x-cloak class="flex items-center gap-2">
            <x-loading-spinner size="sm" color="white" />
            <span>Menambahkan...</span>
        </span>
    </button>
</div>
```

---

### Contoh 4: Shipping Rates with Loading

```blade
<div x-data="{
    loading: false,
    rates: [],
    calculateShipping() {
        this.loading = true;
        this.rates = [];

        fetch('{{ route('api.biteship.rates') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                destination_latitude: this.latitude,
                destination_longitude: this.longitude,
                items: this.cartItems
            })
        })
        .then(response => response.json())
        .then(data => {
            this.rates = data.pricing;
        })
        .catch(error => {
            alert('Gagal menghitung ongkir');
        })
        .finally(() => {
            this.loading = false;
        });
    }
}">
    <button @click="calculateShipping()" class="btn btn-primary">
        <span x-show="!loading">Hitung Ongkir</span>
        <span x-show="loading" x-cloak class="flex items-center gap-2">
            <x-loading-spinner size="sm" color="white" />
            <span>Menghitung...</span>
        </span>
    </button>

    <div x-show="loading" class="flex justify-center py-8">
        <x-loading-spinner size="lg" />
    </div>

    <div x-show="!loading && rates.length > 0" x-cloak>
        <template x-for="rate in rates" :key="rate.company">
            {{-- Display shipping rates --}}
        </template>
    </div>
</div>
```

---

## ♿ Accessibility Features

Semua komponen sudah include accessibility features:

### 1. Flash Messages
- `role="alert"` - Screen readers announce immediately
- `aria-live="assertive"` - Priority announcement
- `aria-label="Close notification"` - Close button label
- Keyboard navigation support

### 2. Loading Spinner
- `role="status"` - Indicates status change
- `aria-label="Loading"` - Describes loading state
- `.sr-only` text for screen readers

### 3. Form Validation
- `aria-invalid="true"` - Marks invalid fields
- `role="alert"` on error messages
- Auto-focus first error for keyboard users
- Clear error messages in Bahasa Indonesia

---

## 🎨 Customization

### Mengubah Flash Message Duration

Edit `resources/views/components/flash-message.blade.php`:
```javascript
// Ganti 5000 (5 detik) ke durasi lain dalam milliseconds
x-init="setTimeout(() => show = false, 5000)"

// Contoh: 10 detik
x-init="setTimeout(() => show = false, 10000)"
```

### Mengubah Spinner Colors

Edit `resources/views/components/loading-spinner.blade.php`:
```php
$colorClasses = [
    'green' => 'text-green-600',
    'blue' => 'text-blue-600',
    'red' => 'text-red-600',
    'gray' => 'text-gray-600',
    'white' => 'text-white',
    'custom' => 'text-purple-600', // Add custom color
];
```

### Custom Validation Messages

Edit `resources/js/form-validation.js`:
```javascript
getRequiredMessage(input) {
    // Customize required message
    return `Field ini wajib diisi`;
}

isValidEmail(email) {
    // Customize email regex
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
```

---

## 🐛 Troubleshooting

### Flash Message Tidak Muncul
- Pastikan `@include('components.flash-message')` ada di layout
- Check apakah Alpine.js sudah loaded
- Verify session flash message dikirim dari controller

### Form Validation Tidak Jalan
- Pastikan `data-validate` attribute ada di `<form>`
- Check apakah `resources/js/form-validation.js` di-import di `app.js`
- Rebuild assets: `npm run dev` atau `npm run build`

### Loading Spinner Tidak Muncul
- Pastikan Alpine.js sudah loaded
- Check `x-show` directive syntax
- Verify `x-cloak` CSS ada di layout

---

## 📚 Resources

- [Alpine.js Documentation](https://alpinejs.dev/)
- [Tailwind CSS Components](https://tailwindcss.com/docs)
- [Laravel Blade Components](https://laravel.com/docs/blade#components)
- [Accessibility Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

---

**Created:** 2026-02-07
**Last Updated:** 2026-02-07
