# 🛒 Amanah Shop - E-commerce UMKM

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss" alt="Tailwind">
</p>

**Amanah Shop** adalah platform e-commerce berbasis Laravel yang dirancang khusus untuk UMKM (Usaha Mikro, Kecil, dan Menengah) di Indonesia. Sistem ini menyediakan fitur lengkap untuk mengelola toko online dengan fokus pada kemudahan penggunaan dan keamanan.

## ✨ Fitur Utama

### 🛍️ Untuk Customer (User)
- **Product Browsing** - Pencarian, filter kategori, pagination
- **Shopping Cart** - Multi-item selection dengan quantity management
- **Checkout System** - Shipping calculation dengan Biteship API
- **Payment Gateway** - Integrasi Midtrans (credit card, e-wallet, bank transfer)
- **Credit/Installment** - Sistem kredit dengan down payment dan cicilan
- **Order Tracking** - Real-time status pesanan dan tracking resi
- **Self-Service Cancellation** - Cancel order sebelum payment dengan stock restoration
- **Product Reviews** - Rating dan review produk
- **Favorites** - Simpan produk favorit

### 👨‍💼 Untuk Admin
- **Dashboard** - Overview penjualan, order statistics
- **Product Management** - CRUD produk dengan SKU, stock, kategori, images
- **Order Management** - Status updates, shipping tracking, payment verification
- **Inventory Management** - Stock in/out dengan supplier tracking
- **Credit Management** - Installment plans, payment verification, overdue tracking
- **Financial Management** - Income/expense tracking, reports, transaction categories
- **Customer Management** - View customer orders, credit history
- **Reports** - Inventory, payments, credit orders dengan export Excel

### 🔐 Untuk SuperAdmin
- **User Management** - CRUD customers dengan email verification
- **Admin Management** - Create/edit/delete admin accounts dengan role assignment
- **Settings** - Shop configuration, shipping origin, WhatsApp contact
- **Audit Logs** - Track role changes dan critical admin actions

## 🚀 Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **Queue**: Redis (optional, for background jobs)

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: TailwindCSS 3.x
- **Build Tool**: Vite
- **JavaScript**: Alpine.js (untuk interaktivitas)

### Integrations
- **Payment**: [Midtrans](https://midtrans.com/) - Payment gateway Indonesia
- **Shipping**: [Biteship](https://biteship.com/) - Multi-courier shipping rates
- **Notifications**: WhatsApp Business API (webhook ready)

## 📋 Requirements

- PHP >= 8.2
- MySQL >= 8.0 atau MariaDB >= 10.3
- Composer
- Node.js & NPM (untuk build assets)
- Git

## 🔧 Installation

### 1. Clone Repository
```bash
git clone https://github.com/Byassslaaaa/Amanah-Shop.git
cd Amanah-Shop
```

### 2. Install Dependencies
```bash
# PHP dependencies
composer install

# Frontend dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy .env.example
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amanah_shop
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Configure Payment Gateway (Midtrans)
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false  # Set true untuk production
```

### 6. Configure Shipping (Biteship)
```env
BITESHIP_API_KEY=your_api_key
BITESHIP_ENVIRONMENT=sandbox  # atau "production"
```

### 7. Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Seed database with demo data
php artisan db:seed
```

### 8. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Start Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👥 Default Users

Setelah menjalankan seeder, gunakan credentials berikut:

### SuperAdmin
- **Email**: `superadmin@amanahshop.com`
- **Password**: `password`
- **Akses**: Full system access

### Admin
- **Email**: `admin@amanahshop.com`
- **Password**: `password`
- **Akses**: Product, order, inventory, customer management

### Customer
- **Email**: `customer@example.com`
- **Password**: `password`
- **Akses**: Shopping, orders, reviews

## 📖 Usage Guide

### Untuk Customer

#### 1. Registrasi & Login
- Klik **Register** di navbar
- Isi form registrasi dengan email valid
- Login menggunakan email dan password

#### 2. Browsing Products
- Lihat semua produk di halaman **Products**
- Filter berdasarkan kategori
- Search produk by name
- Klik produk untuk detail lengkap

#### 3. Add to Cart
- Klik **Add to Cart** pada product detail
- Pilih quantity (max sesuai stock)
- Akses cart via icon di navbar
- Bisa memilih/unselect items yang ingin di-checkout

#### 4. Checkout & Payment
1. Klik **Checkout** di halaman cart
2. Isi alamat pengiriman lengkap (dengan latitude/longitude)
3. Pilih courier (JNE, TIKI, J&T, dll)
4. Pilih payment method:
   - **Cash**: Bayar lunas sekarang
   - **Credit**: Bayar down payment + cicilan
5. Klik **Place Order**
6. Redirect ke Midtrans untuk payment
7. Selesaikan pembayaran

#### 5. Track Order
- Lihat semua order di **My Orders**
- Klik order untuk detail tracking
- Lihat status: Pending → Processing → Shipped → Completed
- Cancel order jika status masih **Pending** dan belum paid

#### 6. Credit/Installment
- Akses **My Credit** di menu
- Lihat active credit orders
- Lihat upcoming payments
- Upload payment proof untuk cicilan
- Admin akan verify payment

### Untuk Admin

#### 1. Login Admin
- Login dengan email admin
- Akses dashboard di `/admin`

#### 2. Manage Products
- **Products** → Create New Product
- Upload product image (max 2MB)
- Set SKU, price, stock, category
- Toggle status (Active/Inactive)

#### 3. Process Orders
- **Orders** → View All Orders
- Update order status:
  - **Pending**: Order baru masuk
  - **Processing**: Sedang diproses
  - **Shipped**: Sudah dikirim (input resi)
  - **Completed**: Pesanan selesai
  - **Cancelled**: Dibatalkan (stock otomatis kembali)
- Add shipping tracking number
- Add admin notes

#### 4. Inventory Management
- **Inventory** → Stock In
  - Tambah stock baru (dengan/tanpa supplier)
  - Bisa create product baru langsung
- **Inventory** → Stock Out
  - Catat stock keluar manual (rusak, sample, dll)
- **Inventory** → Movements
  - Lihat history semua stock movements

#### 5. Credit Management
- **Credit** → Installment Plans
  - Create/edit rencana cicilan (3 bulan, 6 bulan, dll)
  - Set interest rate
- **Credit** → Installments
  - Lihat semua pembayaran cicilan
  - Verify payment proofs uploaded by customers
  - Track overdue payments

#### 6. Financial Management
- **Finance** → Transactions
  - Record income/expense
  - Categorize transactions
- **Finance** → Report
  - View financial summary by date range

#### 7. Reports
- **Reports** → Inventory
  - Stock levels, low stock alerts
- **Reports** → Payments
  - Payment history dengan export Excel
- **Reports** → Credit Orders
  - Credit order statistics

### Untuk SuperAdmin

#### 1. User Management
- **Users** → Manage all customers
- Create/edit/delete user accounts
- Reset passwords
- Verify emails manually

#### 2. Admin Management
- **Admins** → Manage admin accounts
- Create new admin (role: admin)
- Edit admin details
- **⚠️ Warning**: Mengubah role admin memerlukan approval

#### 3. Settings
- **Settings** → Shop configuration
- Set shipping origin (lat/lng untuk Biteship)
- Set WhatsApp contact number
- Configure shop information

## 🔒 Security Features

### Implemented (Post-Audit 2026-02-07)
✅ **Pessimistic Locking** - Prevent race conditions on stock/payment
✅ **Mass Assignment Protection** - Role field tidak bisa di-mass-assign
✅ **Webhook Signature Verification** - SHA512 validation untuk Midtrans
✅ **Server-side Validation** - Re-validate shipping cost, down payment
✅ **Authorization Checks** - Ownership verification pada orders
✅ **Audit Logging** - Role changes tracked dengan `Log::alert`
✅ **Rate Limiting** - API endpoints protected (30-60 req/min)
✅ **CSRF Protection** - All state-changing routes
✅ **Error Sanitization** - Generic error messages untuk users
✅ **Stock Restoration** - Auto-restore stock on cancel/failed payment

### Recommendations for Production
- [ ] Enable HTTPS (SSL certificate)
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Use strong `APP_KEY`
- [ ] Configure CORS restrictively
- [ ] Enable rate limiting on all routes
- [ ] Setup daily database backups
- [ ] Monitor Laravel logs regularly

## 📚 Documentation

Dokumentasi lengkap tersedia di folder `docs/`:

- **[Setup Guide](docs/setup/SETUP_GUIDE.md)** - Detailed installation instructions
- **[Quick Setup](docs/setup/QUICK_SETUP.md)** - Fast setup for developers
- **[Audit Summary](docs/audit/AUDIT_SUMMARY.md)** - Security audit findings & fixes
- **[Deployment Checklist](docs/audit/DEPLOYMENT_CHECKLIST.md)** - Production deployment guide
- **[Changelog](docs/CHANGELOG.md)** - Version history

## 🐛 Known Issues & Fixes

Semua critical issues sudah diperbaiki pada audit tanggal **2026-02-07**:

- ✅ Mass assignment privilege escalation
- ✅ Payment webhook race conditions
- ✅ Order cancellation race conditions
- ✅ Shipping cost manipulation
- ✅ Down payment bypass
- ✅ Stock not restored on failed payments
- ✅ Admin can't cancel with stock restoration
- ✅ Missing SuperAdmin middleware
- ✅ Insufficient input validation on Biteship API

Total **16 security fixes** diterapkan. Lihat [Audit Summary](docs/audit/AUDIT_SUMMARY.md) untuk detail lengkap.

## 🤝 Contributing

Pull requests are welcome! Untuk perubahan major, harap buka issue terlebih dahulu untuk diskusi.

### Development Workflow
1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### Code Style
- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
- Use meaningful variable/function names
- Add comments for complex logic
- Write tests for new features

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Credits

### Developed By
- **Development Team** - Amanah Shop Development

### Built With
- [Laravel](https://laravel.com/) - PHP Framework
- [TailwindCSS](https://tailwindcss.com/) - CSS Framework
- [Midtrans](https://midtrans.com/) - Payment Gateway
- [Biteship](https://biteship.com/) - Shipping Integration
- [Alpine.js](https://alpinejs.dev/) - JavaScript Framework

### Special Thanks
- Laravel community
- All contributors

## 📞 Support

Jika mengalami masalah atau butuh bantuan:
1. Check [Documentation](docs/)
2. Open [GitHub Issue](https://github.com/Byassslaaaa/Amanah-Shop/issues)
3. Contact: [Your Email/WhatsApp]

---

<p align="center">Made with ❤️ for Indonesian UMKM</p>
