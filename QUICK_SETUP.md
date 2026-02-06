# ⚡ QUICK SETUP GUIDE - AMANAH SHOP

Panduan cepat untuk setup dalam 30 menit!

---

## 🎯 SETUP WAJIB (3 Langkah)

### 1️⃣ MIDTRANS PAYMENT (15 menit)

```bash
# 1. Daftar di https://midtrans.com/ (pilih Sandbox untuk testing)
# 2. Login → Settings → Access Keys
# 3. Copy 3 credentials ke .env:

MIDTRANS_MERCHANT_ID=G123456789
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false

# 4. Setup webhook di Midtrans dashboard:
#    https://your-domain.com/payment/notification

# 5. Test
php artisan midtrans:test info
```

**Test Payment**: Gunakan card `4811 1111 1111 1114`, CVV: `123`, Exp: `12/26`

---

### 2️⃣ BITESHIP SHIPPING (10 menit)

```bash
# 1. Daftar di https://biteship.com/
# 2. Dashboard → Settings → API Keys
# 3. Copy API key ke .env:

BITESHIP_API_KEY=biteship_xxx_your_key_here

# 4. Isi data toko (WAJIB):
BITESHIP_ORIGIN_CONTACT_NAME="Amanah Shop"
BITESHIP_ORIGIN_CONTACT_PHONE=081234567890
BITESHIP_ORIGIN_ADDRESS="Jl. Raya Bogor No. 123, Jakarta Pusat"
BITESHIP_ORIGIN_POSTAL_CODE=10310
BITESHIP_ORIGIN_LAT=-6.175110    # Dari Google Maps
BITESHIP_ORIGIN_LNG=106.865036   # Dari Google Maps
```

**Cara dapat koordinat GPS**:
1. Buka Google Maps
2. Cari alamat toko
3. Klik kanan → "Salin koordinat"
4. Format: `-6.175110, 106.865036` (latitude, longitude)

---

### 3️⃣ WHATSAPP (5 menit) - OPSIONAL

```bash
# Setup nomor WhatsApp Customer Service:
# 1. Login admin panel: /admin
# 2. Menu: Settings
# 3. Isi "Admin WhatsApp": 081234567890
# 4. Save

# Test: akses /contact → klik tombol WhatsApp
```

✅ **Link wa.me sudah otomatis jalan!**

---

## 🚀 QUICK START COMMANDS

### Development

```bash
# Clone & setup
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link

# Database
php artisan migrate --seed

# Run server
php artisan serve        # Terminal 1
npm run dev             # Terminal 2
```

**Default Login Admin**:
- Email: `admin@amanah.shop`
- Password: `password`

---

## ✅ CHECKLIST TESTING

### Basic Flow
- [ ] Login admin → `/admin`
- [ ] Tambah produk baru
- [ ] Logout → Login sebagai user
- [ ] Tambah produk ke cart
- [ ] Checkout → Isi alamat
- [ ] **Cek ongkir muncul** (Biteship)
- [ ] Pilih kurir → Bayar
- [ ] **Popup Midtrans muncul** (Payment)
- [ ] Test payment dengan card: `4811 1111 1111 1114`
- [ ] **Order status = Processing** (Success!)

### WhatsApp
- [ ] Akses `/contact`
- [ ] Klik tombol WhatsApp
- [ ] **Redirect ke wa.me** (Success!)

---

## 📋 .ENV MINIMAL (WAJIB ISI)

```env
# Application
APP_NAME="Amanah Shop"
APP_ENV=local
APP_KEY=base64:xxxxx
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_DATABASE=amanah_shop
DB_USERNAME=root
DB_PASSWORD=

# Midtrans (WAJIB)
MIDTRANS_MERCHANT_ID=
MIDTRANS_CLIENT_KEY=
MIDTRANS_SERVER_KEY=
MIDTRANS_IS_PRODUCTION=false

# Biteship (WAJIB)
BITESHIP_API_KEY=
BITESHIP_ORIGIN_CONTACT_NAME="Amanah Shop"
BITESHIP_ORIGIN_CONTACT_PHONE=
BITESHIP_ORIGIN_ADDRESS=
BITESHIP_ORIGIN_POSTAL_CODE=
BITESHIP_ORIGIN_LAT=
BITESHIP_ORIGIN_LNG=

# WhatsApp (Opsional - isi via admin panel)
# WHATSAPP_PROVIDER=official
# WHATSAPP_ACCESS_TOKEN=
```

---

## 🆘 COMMON ERRORS

### ❌ "No shipping rates available"
**Solusi**: Cek `BITESHIP_ORIGIN_LAT` dan `BITESHIP_ORIGIN_LNG` sudah diisi

### ❌ "Midtrans snap token failed"
**Solusi**: Cek `MIDTRANS_SERVER_KEY` sudah benar

### ❌ "Payment notification not received"
**Solusi**:
- Development: Pakai ngrok untuk expose localhost
- Production: Cek webhook URL di Midtrans dashboard

---

## 📱 PHONE & EMAIL

Format nomor HP: `081234567890` (tanpa +62, tanpa 0 di depan 8)

Setup email production:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx  # App password
MAIL_ENCRYPTION=tls
```

---

## 🎓 NEXT STEPS

1. ✅ Setup 3 integrasi di atas
2. 📝 Tambah produk via admin panel
3. 🧪 Test full checkout flow
4. 📸 Upload gambar produk berkualitas
5. 📄 Isi halaman About & Contact
6. 🚀 Deploy ke production

**Panduan Lengkap**: Baca `SETUP_GUIDE.md` untuk detail

---

## 📞 TESTING COMMANDS

```bash
# Cek konfigurasi Midtrans
php artisan midtrans:test info

# Buat payment token untuk order #1
php artisan midtrans:test create-token --order=1

# Cek status payment
php artisan midtrans:test check-status --order=1

# Cek email settings
php artisan mail:test  # (buat command ini jika perlu)

# Update shipping tracking
php artisan shipping:update-tracking

# Refresh database (HATI-HATI: hapus semua data!)
php artisan migrate:fresh --seed
```

---

## 🎯 PRODUCTION DEPLOYMENT

```bash
# Update .env untuk production
APP_ENV=production
APP_DEBUG=false
MIDTRANS_IS_PRODUCTION=true
BITESHIP_ENVIRONMENT=production

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**SSL Certificate (Let's Encrypt)**:
```bash
sudo certbot --nginx -d amanahshop.com
```

---

## 💡 TIPS

1. **Development**: Gunakan Sandbox untuk Midtrans & Test mode untuk Biteship
2. **Backup**: Backup database sebelum migrate: `mysqldump amanah_shop > backup.sql`
3. **Git**: Jangan commit `.env` file ke repository
4. **Security**: Ganti password admin setelah first login
5. **Performance**: Setup Redis untuk cache di production

---

**🎉 SELAMAT! Anda sudah siap untuk go-live!**

Jika ada masalah, cek `SETUP_GUIDE.md` atau `storage/logs/laravel.log`
