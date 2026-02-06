# 🚀 PANDUAN SETUP AMANAH SHOP

Panduan lengkap untuk setup Amanah Shop dari development hingga production.

---

## 📋 TABLE OF CONTENTS

1. [Setup Awal (Development)](#1-setup-awal-development)
2. [Setup Midtrans Payment Gateway](#2-setup-midtrans-payment-gateway-wajib)
3. [Setup Biteship Shipping API](#3-setup-biteship-shipping-api-wajib)
4. [Setup WhatsApp (Opsional)](#4-setup-whatsapp-opsional)
5. [Setup Email (Production)](#5-setup-email-production)
6. [Testing](#6-testing)
7. [Deploy ke Production](#7-deploy-ke-production)

---

## 1. SETUP AWAL (Development)

### 1.1. Clone & Install Dependencies

```bash
# Clone repository (jika dari git)
git clone <repository-url>
cd SiDesa-1-salman-2

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create storage link
php artisan storage:link
```

### 1.2. Setup Database

```bash
# Buat database MySQL
mysql -u root -p
CREATE DATABASE amanah_shop;
EXIT;

# Edit .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amanah_shop
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

# Jalankan migration & seeder
php artisan migrate --seed
```

### 1.3. Setup Admin Account

Setelah seeder, login dengan:
- **Email**: `admin@amanah.shop`
- **Password**: `password`

**⚠️ PENTING**: Ganti password setelah login pertama!

### 1.4. Jalankan Development Server

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite (assets)
npm run dev
```

Akses: `http://localhost:8000`

---

## 2. SETUP MIDTRANS PAYMENT GATEWAY (WAJIB)

### 2.1. Daftar Akun Midtrans

1. **Sandbox (Testing)** - GRATIS
   - Buka: https://midtrans.com/
   - Klik "Sign Up" → Pilih "Sandbox"
   - Isi data bisnis
   - Verifikasi email

2. **Production** - Berbayar
   - Upgrade dari Sandbox atau daftar baru
   - Perlu dokumen bisnis (NPWP, SIUP, dll)
   - Proses approval 1-3 hari kerja

### 2.2. Dapatkan API Credentials

1. Login ke Midtrans Dashboard:
   - **Sandbox**: https://dashboard.sandbox.midtrans.com/
   - **Production**: https://dashboard.midtrans.com/

2. Menu: **Settings → Access Keys**

3. Copy 3 credentials:
   ```
   Merchant ID: G123456789
   Client Key: SB-Mid-client-xxxxxxxxxxxxx
   Server Key: SB-Mid-server-xxxxxxxxxxxxx
   ```

### 2.3. Setup di .env

```env
MIDTRANS_MERCHANT_ID=G123456789
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 2.4. Setup Webhook URL

1. Di Midtrans Dashboard: **Settings → Configuration**
2. Isi **Payment Notification URL**:
   ```
   Development: http://localhost/payment/notification (pakai ngrok)
   Production: https://amanahshop.com/payment/notification
   ```
3. Isi **Finish Redirect URL**:
   ```
   https://amanahshop.com/payment/finish
   ```

### 2.5. Testing Midtrans

```bash
# Cek konfigurasi
php artisan midtrans:test info

# Buat order dummy dulu via aplikasi, lalu:
php artisan midtrans:test create-token --order=1
```

**Test Cards (Sandbox)**:
| Card Number | CVV | Exp | Result |
|-------------|-----|-----|--------|
| 4811 1111 1111 1114 | 123 | 12/26 | ✅ Success |
| 5211 1111 1111 1117 | 123 | 12/26 | ✅ Success (MC) |
| 4911 1111 1111 1113 | 123 | 12/26 | ⚠️ Challenge FDS |
| 4411 1111 1111 1118 | 123 | 12/26 | ❌ Denied |

**Manual Testing**:
1. Login sebagai user
2. Tambah produk ke cart → checkout
3. Klik "Bayar Sekarang"
4. Popup Midtrans muncul
5. Gunakan test card di atas
6. Cek order status berubah ke "Processing"

---

## 3. SETUP BITESHIP SHIPPING API (WAJIB)

### 3.1. Daftar Akun Biteship

1. Buka: https://biteship.com/
2. Klik "Daftar Sekarang"
3. Isi data:
   - Email bisnis
   - Nama toko: Amanah Shop
   - Nomor HP
   - Alamat toko lengkap
4. Verifikasi email
5. Login ke dashboard: https://dashboard.biteship.com/

### 3.2. Dapatkan API Key

1. Di Dashboard: **Settings → API Keys**
2. Copy **API Key**: `biteship_xxx...`
3. Mode:
   - **Test**: Untuk development
   - **Live**: Untuk production (perlu approval)

### 3.3. Dapatkan Data Lokasi Toko

**Alamat Lengkap**:
```
Contoh: Jl. Raya Bogor No. 123, RT.01/RW.05,
Kelurahan Menteng, Kecamatan Menteng, Jakarta Pusat
```

**Kode Pos**:
- Cari di Google: "kode pos [alamat toko]"
- Atau cek di: https://kodepos.nomor.net/

**Koordinat GPS (Latitude & Longitude)**:
1. Buka Google Maps
2. Cari alamat toko Anda
3. Klik kanan pada lokasi → "Salin koordinat" atau lihat di URL
4. Format: `-6.175110, 106.865036`
   - Latitude: `-6.175110` (angka pertama)
   - Longitude: `106.865036` (angka kedua)

### 3.4. Setup di .env

```env
# API Key
BITESHIP_API_KEY=biteship_xxx_your_api_key_here
BITESHIP_BASE_URL=https://api.biteship.com/v1
BITESHIP_ENVIRONMENT=development

# Data Toko (WAJIB ISI SEMUA)
BITESHIP_ORIGIN_CONTACT_NAME="Amanah Shop"
BITESHIP_ORIGIN_CONTACT_PHONE=081234567890
BITESHIP_ORIGIN_ADDRESS="Jl. Raya Bogor No. 123, RT.01/RW.05, Kelurahan Menteng"
BITESHIP_ORIGIN_POSTAL_CODE=10310
BITESHIP_ORIGIN_LAT=-6.175110
BITESHIP_ORIGIN_LNG=106.865036
BITESHIP_ORIGIN_NOTE="Toko"
```

### 3.5. Testing Biteship

**Via Aplikasi**:
1. Login sebagai user
2. Tambah produk ke cart
3. Checkout → Isi alamat lengkap
4. Harus muncul list ongkir dari berbagai kurir (JNE, J&T, SiCepat, dll)
5. Jika tidak muncul: cek log error di `storage/logs/laravel.log`

**Via Command** (buat command ini jika perlu):
```bash
php artisan tinker
>>> $service = app(\App\Services\BiteshipService::class);
>>> $service->getCouriers();
```

---

## 4. SETUP WHATSAPP (OPSIONAL)

### 4.1. Basic Setup (SUDAH JALAN)

**Link WhatsApp Customer Service** sudah otomatis jalan tanpa setup API.

**Cara Setting Nomor**:
1. Login admin panel: `/admin`
2. Menu: **Settings**
3. Isi **Admin WhatsApp**: `081234567890` (format Indonesia)
4. Save

**Testing**:
1. Akses halaman `/contact`
2. Klik tombol WhatsApp
3. Harus buka wa.me dengan pesan pre-filled

✅ **Untuk 90% kasus, ini sudah cukup!**

---

### 4.2. Advanced Setup (Inventory Automation)

Jika ingin fitur inventory management via WhatsApp chat (MASUK/KELUAR command):

#### Option A: Official WhatsApp Business API (Meta) - GRATIS

**Pros**: Gratis, official
**Cons**: Setup ribet, perlu verifikasi bisnis

**Steps**:
1. Buat **Meta Business Account**:
   - Buka: https://business.facebook.com/
   - Create Account → Isi data bisnis

2. Tambah **WhatsApp Product**:
   - Dashboard → Add Product → WhatsApp
   - Ikuti setup wizard

3. Dapatkan Credentials:
   - Menu: **WhatsApp → API Setup**
   - Copy:
     - Access Token
     - Phone Number ID
     - Business Account ID

4. Setup Webhook:
   - Webhook URL: `https://your-domain.com/api/whatsapp/webhook`
   - Verify Token: `amanah_shop_webhook_token`
   - Subscribe ke: `messages`

5. Update `.env`:
   ```env
   WHATSAPP_PROVIDER=official
   WHATSAPP_ACCESS_TOKEN=your_access_token
   WHATSAPP_PHONE_NUMBER_ID=123456789
   WHATSAPP_BUSINESS_ACCOUNT_ID=987654321
   ```

#### Option B: Twilio WhatsApp - BERBAYAR (Recommended)

**Pros**: Setup mudah, cepat, reliable
**Cons**: Berbayar (~$1/user/month)

**Steps**:
1. Daftar di: https://www.twilio.com/
2. Verify email & phone
3. Dashboard → Messaging → WhatsApp
4. **Get Sandbox Number** (untuk testing) atau **Request Production Access**
5. Copy credentials:
   ```env
   WHATSAPP_PROVIDER=twilio
   TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxx
   TWILIO_AUTH_TOKEN=your_auth_token
   TWILIO_WHATSAPP_FROM=+14155238886
   ```
6. Setup webhook di Twilio:
   - URL: `https://your-domain.com/api/whatsapp/webhook`
   - Method: POST

#### Option C: Vonage WhatsApp - BERBAYAR

**Steps**:
1. Daftar di: https://dashboard.nexmo.com/
2. Menu: Messages & Dispatch → Sandbox
3. Get API Key & Secret
4. Update `.env`:
   ```env
   WHATSAPP_PROVIDER=vonage
   VONAGE_API_KEY=your_api_key
   VONAGE_API_SECRET=your_secret
   VONAGE_WHATSAPP_NUMBER=your_number
   ```

### 4.3. Testing WhatsApp Automation

1. Kirim pesan ke nomor WhatsApp yang dikonfigurasi:
   ```
   HELP
   ```
2. Harus dapat balasan dengan panduan

3. Test stock in:
   ```
   MASUK BR5KG 100 50000 SUP001
   ```

4. Test stock out:
   ```
   KELUAR BR5KG 50 Rusak
   ```

5. Cek database:
   ```sql
   SELECT * FROM inventory_movements ORDER BY id DESC LIMIT 5;
   ```

---

## 5. SETUP EMAIL (PRODUCTION)

### 5.1. Development (Mailpit/Mailtrap)

Sudah otomatis jalan dengan Laravel:
```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

Buka: http://localhost:8025 untuk lihat email

### 5.2. Production (Gmail SMTP)

**Steps**:
1. **Enable 2-Factor Authentication** di akun Google
2. **Generate App Password**:
   - Google Account → Security → 2-Step Verification
   - App passwords → Select app: Mail
   - Select device: Other (Amanah Shop)
   - Generate → Copy password

3. Update `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx  # App password (16 karakter)
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   MAIL_FROM_NAME="Amanah Shop"
   ```

4. Test email:
   ```bash
   php artisan tinker
   >>> Mail::raw('Test email', fn($msg) => $msg->to('test@example.com')->subject('Test'));
   ```

### 5.3. Alternative: Mailtrap (Recommended)

**Pros**: Khusus untuk aplikasi, reliable, tracking bagus
**Cons**: Berbayar setelah 1000 email/bulan gratis

1. Daftar: https://mailtrap.io/
2. Create Inbox
3. Copy SMTP credentials
4. Update `.env` dengan credentials dari Mailtrap

---

## 6. TESTING

### 6.1. Checklist Testing

**Database & Auth**:
- [ ] Register user baru
- [ ] Login/logout user
- [ ] Login admin panel (`/admin`)
- [ ] Update profile

**Products & Cart**:
- [ ] Browse products
- [ ] Search products
- [ ] Add to cart
- [ ] Update quantity cart
- [ ] Remove from cart

**Checkout & Shipping**:
- [ ] Isi alamat lengkap
- [ ] Cek ongkir muncul dari berbagai kurir
- [ ] Pilih kurir & lanjut

**Payment**:
- [ ] Klik bayar → Midtrans popup muncul
- [ ] Test payment dengan test card
- [ ] Payment success → order status "Processing"
- [ ] Cek email notification (jika sudah setup)

**Admin Panel**:
- [ ] Dashboard stats muncul
- [ ] Kelola produk (CRUD)
- [ ] Kelola kategori
- [ ] Kelola orders
- [ ] Update order status
- [ ] Input resi & tracking
- [ ] Kelola users
- [ ] Settings

**WhatsApp**:
- [ ] Klik tombol WA di contact page
- [ ] Redirect ke wa.me dengan pesan

### 6.2. Test Commands

```bash
# Midtrans
php artisan midtrans:test info
php artisan midtrans:test create-token --order=1
php artisan midtrans:test check-status --order=1

# Email
php artisan mail:test  # buat command ini jika perlu

# Database
php artisan migrate:fresh --seed  # HATI-HATI: hapus semua data!
```

---

## 7. DEPLOY KE PRODUCTION

### 7.1. Setup Server

**Requirements**:
- PHP 8.1+
- MySQL 8.0+
- Nginx/Apache
- Composer
- Node.js & npm

**Recommended Hosting**:
- VPS: DigitalOcean, Linode, Vultr
- Shared: Niagahoster, Hostinger (dengan SSH access)
- Cloud: AWS, Google Cloud

### 7.2. Deploy Steps

1. **Clone Repository**:
   ```bash
   cd /var/www/html
   git clone <repository-url> amanah-shop
   cd amanah-shop
   ```

2. **Install Dependencies**:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

3. **Setup Environment**:
   ```bash
   cp .env.example .env
   nano .env  # Edit dengan credentials production
   ```

4. **Update .env untuk Production**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://amanahshop.com

   # Update semua API keys ke production
   MIDTRANS_IS_PRODUCTION=true
   BITESHIP_ENVIRONMENT=production
   ```

5. **Setup Database**:
   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

6. **Set Permissions**:
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

7. **Setup Nginx**:
   ```nginx
   server {
       listen 80;
       server_name amanahshop.com www.amanahshop.com;
       root /var/www/html/amanah-shop/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

8. **Setup SSL (Let's Encrypt)**:
   ```bash
   sudo apt install certbot python3-certbot-nginx
   sudo certbot --nginx -d amanahshop.com -d www.amanahshop.com
   ```

9. **Setup Cron (untuk queue, cache, dll)**:
   ```bash
   crontab -e

   # Add:
   * * * * * cd /var/www/html/amanah-shop && php artisan schedule:run >> /dev/null 2>&1
   ```

10. **Optimize**:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

### 7.3. Production Checklist

**Security**:
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Generate new `APP_KEY`
- [ ] Update admin password
- [ ] Setup SSL certificate (HTTPS)
- [ ] Setup firewall (ufw/fail2ban)
- [ ] Disable directory listing

**APIs**:
- [ ] Update Midtrans ke production credentials
- [ ] Set `MIDTRANS_IS_PRODUCTION=true`
- [ ] Update Biteship ke production API key
- [ ] Set `BITESHIP_ENVIRONMENT=production`
- [ ] Update webhook URLs di Midtrans dashboard
- [ ] Update webhook URLs di WhatsApp provider (jika ada)

**Performance**:
- [ ] Run `php artisan optimize`
- [ ] Setup Redis untuk cache & session (optional)
- [ ] Setup queue worker dengan Supervisor (optional)
- [ ] Enable OPcache di PHP
- [ ] Setup CDN untuk assets (optional)

**Backup**:
- [ ] Setup automated database backup (daily)
- [ ] Setup file backup (weekly)
- [ ] Test restore backup

**Monitoring**:
- [ ] Setup error logging (Sentry/Bugsnag)
- [ ] Setup uptime monitoring (UptimeRobot)
- [ ] Setup Google Analytics
- [ ] Monitor log files: `storage/logs/laravel.log`

---

## 🆘 TROUBLESHOOTING

### Error: "Midtrans snap token not created"
**Solusi**:
- Cek API credentials di `.env`
- Cek log: `storage/logs/laravel.log`
- Test dengan: `php artisan midtrans:test info`

### Error: "Biteship: No courier available"
**Solusi**:
- Cek API key valid
- Cek koordinat toko sudah benar
- Cek kode pos valid
- Cek alamat tujuan lengkap dengan kode pos

### Error: "Payment notification not received"
**Solusi**:
- Cek webhook URL di Midtrans dashboard
- Untuk local dev, gunakan ngrok:
  ```bash
  ngrok http 8000
  # Update webhook URL di Midtrans dengan ngrok URL
  ```

### Error: "WhatsApp command not working"
**Solusi**:
- Cek webhook configured di provider
- Cek log: `storage/logs/laravel.log`
- Cek admin user punya nomor HP yang match

### Performance Slow
**Solusi**:
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan optimize
```

---

## 📞 SUPPORT

- **Documentation**: `README.md`
- **Issues**: GitHub Issues
- **Email**: admin@amanah.shop

---

## 📄 LICENSE

Proprietary - Amanah Shop © 2026
