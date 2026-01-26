# WhatsApp Integration - Inventory Management

## Overview

Amanah Shop mendukung pencatatan **laporan keluar masuk barang** melalui **WhatsApp**. Admin yang terdaftar dapat mengirim pesan WhatsApp dengan format khusus untuk:

- ✅ Mencatat stock masuk (pembelian dari supplier)
- ✅ Mencatat stock keluar (barang rusak, hilang, dll)
- ✅ Otomatis update stok produk
- ✅ Otomatis catat transaksi keuangan
- ✅ Mendapat konfirmasi real-time

---

## Cara Kerja

```
┌──────────────┐       ┌──────────────────┐       ┌─────────────────┐
│   Admin      │       │  WhatsApp Server │       │  Amanah Shop    │
│   WhatsApp   │──────▶│   (Webhook)      │──────▶│   Backend       │
└──────────────┘       └──────────────────┘       └─────────────────┘
                                                            │
                                                            ▼
                         ┌──────────────────────────────────────────┐
                         │  1. Verify admin phone number            │
                         │  2. Parse message format                 │
                         │  3. Update product stock                 │
                         │  4. Create inventory movement record     │
                         │  5. Create financial transaction (jika ada harga)│
                         │  6. Send WhatsApp confirmation           │
                         └──────────────────────────────────────────┘
```

---

## Format Pesan WhatsApp

### 1. STOCK MASUK (Pembelian Barang)

#### Format Sederhana:
```
MASUK <kode_produk> <qty> <harga> <kode_supplier>
```

**Contoh:**
```
MASUK BR5KG 100 50000 SUP001
```

**Keterangan:**
- `BR5KG` = Kode SKU produk atau nama produk
- `100` = Quantity (jumlah barang masuk)
- `50000` = Harga satuan (opsional, kosongkan jika tidak ada)
- `SUP001` = Kode supplier (opsional)

#### Format Detail (Multi-line):
```
MASUK
Produk: BR5KG
Qty: 100
Harga: 50000
Supplier: SUP001
Notes: PO2026-001
```

**Penjelasan Field:**
- **Produk**: SKU atau nama produk (wajib)
- **Qty**: Jumlah barang masuk (wajib)
- **Harga**: Harga satuan pembelian (opsional)
- **Supplier**: Kode supplier (opsional)
- **Notes**: Keterangan tambahan, misal nomor PO/Invoice (opsional)

**Respon WhatsApp:**
```
✅ *STOCK MASUK BERHASIL*

📦 Produk: Beras Premium 5kg
➕ Qty: 100
📊 Stock Sebelum: 250
📊 Stock Sekarang: 350
💰 Harga: Rp 50.000
💵 Total: Rp 5.000.000
🏪 Supplier: PT Sumber Pangan Jaya

📝 Keterangan: PO2026-001
```

---

### 2. STOCK KELUAR (Barang Rusak/Hilang)

#### Format Sederhana:
```
KELUAR <kode_produk> <qty> <keterangan>
```

**Contoh:**
```
KELUAR BR5KG 50 Rusak
```

**Keterangan:**
- `BR5KG` = Kode SKU produk
- `50` = Quantity keluar
- `Rusak` = Alasan/keterangan (opsional)

#### Format Detail:
```
KELUAR
Produk: BR5KG
Qty: 50
Notes: Kemasan robek saat bongkar muat
```

**Respon WhatsApp:**
```
✅ *STOCK KELUAR BERHASIL*

📦 Produk: Beras Premium 5kg
➖ Qty: 50
📊 Stock Sebelum: 350
📊 Stock Sekarang: 300

📝 Keterangan: Kemasan robek saat bongkar muat
```

---

### 3. BANTUAN

Ketik `HELP` atau `BANTUAN` untuk melihat panduan lengkap:

```
HELP
```

**Respon:**
```
*📚 PANDUAN WHATSAPP INVENTORY*

*1️⃣ STOCK MASUK (Simple):*
`MASUK <kode> <qty> <harga> <supplier>`
Contoh:
`MASUK BR5KG 100 50000 SUP001`

*2️⃣ STOCK MASUK (Detail):*
```MASUK
Produk: BR5KG
Qty: 100
Harga: 50000
Supplier: SUP001
Notes: PO2026-001```

*3️⃣ STOCK KELUAR (Simple):*
`KELUAR <kode> <qty> <keterangan>`
Contoh:
`KELUAR BR5KG 50 Rusak`

*4️⃣ STOCK KELUAR (Detail):*
```KELUAR
Produk: BR5KG
Qty: 50
Notes: Barang rusak```

💡 *Tips:*
- Kode produk bisa SKU atau nama
- Supplier code opsional
- Harga opsional (untuk stock in)
- Gunakan HELP untuk melihat panduan ini
```

---

## Setup WhatsApp Business API

### Pilihan 1: Official WhatsApp Business API (Meta)

**1. Daftar di Meta for Developers:**
   - Buka: https://developers.facebook.com/
   - Create App → Business
   - Add WhatsApp Product

**2. Dapatkan Credentials:**
   - **Phone Number ID**: Settings → WhatsApp → Phone Numbers
   - **Access Token**: Settings → WhatsApp → API Setup
   - **Business Account ID**: Settings → WhatsApp

**3. Configure Webhook:**
   - URL: `https://yourdomain.com/api/webhook/whatsapp`
   - Verify Token: `amanah_shop_webhook_token` (sesuai .env)
   - Subscribe to: `messages`

**4. Update `.env`:**
```env
WHATSAPP_PROVIDER=official
WHATSAPP_ACCESS_TOKEN=your_access_token_here
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id
WHATSAPP_VERIFY_TOKEN=amanah_shop_webhook_token
```

**5. Test Webhook:**
```bash
curl -X GET "https://yourdomain.com/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=amanah_shop_webhook_token&hub.challenge=12345"
```

**6. Verify di Meta Dashboard:**
   - Jika berhasil, webhook status akan "Connected"

---

### Pilihan 2: Twilio WhatsApp

**1. Daftar di Twilio:**
   - Buka: https://www.twilio.com/whatsapp
   - Get WhatsApp Sandbox atau Apply for Production Access

**2. Dapatkan Credentials:**
   - Account SID
   - Auth Token
   - WhatsApp From Number (sandbox: +1 415 523 8886)

**3. Configure Webhook:**
   - Messaging → Settings → WhatsApp Sandbox
   - When a message comes in: `https://yourdomain.com/api/webhook/whatsapp`
   - HTTP POST

**4. Update `.env`:**
```env
WHATSAPP_PROVIDER=twilio
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_WHATSAPP_FROM=+14155238886
```

**5. Test:**
   - Join WhatsApp Sandbox: kirim "join <sandbox-code>" ke nomor Twilio
   - Kirim "HELP" untuk test

---

### Pilihan 3: Vonage (Nexmo) WhatsApp

**1. Daftar di Vonage:**
   - Buka: https://dashboard.nexmo.com/
   - Messages and Dispatch → WhatsApp

**2. Configure Webhook:**
   - Inbound Messages Webhook: `https://yourdomain.com/api/webhook/whatsapp`

**3. Update `.env`:**
```env
WHATSAPP_PROVIDER=vonage
VONAGE_API_KEY=your_api_key
VONAGE_API_SECRET=your_api_secret
VONAGE_WHATSAPP_NUMBER=your_whatsapp_number
```

---

## Otorisasi Admin

**Hanya admin yang terdaftar di database** yang dapat menggunakan fitur WhatsApp inventory.

### Verifikasi Admin:

Service akan mencocokkan nomor WhatsApp pengirim dengan data di tabel `users`:

```sql
SELECT * FROM users
WHERE role IN ('admin', 'superadmin')
AND phone LIKE '%08123456789%'
```

### Format Nomor yang Didukung:

Service otomatis membersihkan format nomor untuk matching:

- `081234567890` → `81234567890`
- `+6281234567890` → `81234567890`
- `6281234567890` → `81234567890`
- `0081234567890` → `81234567890`

**Contoh:**

Jika admin di database punya phone: `081234567890`

WhatsApp bisa kirim dari:
- `+6281234567890` ✅
- `6281234567890` ✅
- `081234567890` ✅

**Jika Nomor Tidak Terdaftar:**
```
⛔ Nomor tidak terdaftar sebagai admin. Hubungi SuperAdmin untuk mendaftar.
```

---

## Integrasi dengan Sistem Keuangan

### Jika Harga Dicantumkan (Stock In):

Sistem otomatis membuat **Financial Transaction** dengan kategori "Pembelian Barang":

```php
FinancialTransaction::create([
    'transaction_number' => 'FT20260126XXXX',
    'type' => 'expense',
    'category_id' => 1, // Pembelian Barang
    'amount' => $totalPrice,
    'description' => "Pembelian Beras Premium 5kg via WhatsApp",
    'reference_type' => InventoryMovement::class,
    'reference_id' => $movementId,
]);
```

**Dashboard Admin:**
- Transaksi muncul di **Laporan Keuangan → Pengeluaran**
- Referensi link ke Inventory Movement

---

## Testing Lokal (Development)

### 1. Gunakan Ngrok untuk Public URL:

```bash
ngrok http 8000
```

Output:
```
Forwarding    https://abc123.ngrok.io -> http://localhost:8000
```

### 2. Configure Webhook:

Gunakan URL ngrok di WhatsApp provider:
```
https://abc123.ngrok.io/api/webhook/whatsapp
```

### 3. Test Manual via cURL:

**Stock In:**
```bash
curl -X POST https://abc123.ngrok.io/api/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "6281234567890",
          "text": {
            "body": "MASUK BR5KG 100 50000 SUP001"
          }
        }]
      }
    }]
  }]
}'
```

**Stock Out:**
```bash
curl -X POST https://abc123.ngrok.io/api/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "6281234567890",
          "text": {
            "body": "KELUAR BR5KG 50 Rusak"
          }
        }]
      }
    }]
  }]
}'
```

### 4. Check Logs:

```bash
tail -f storage/logs/laravel.log
```

Lihat:
```
[2026-01-26 10:30:00] local.INFO: WhatsApp Webhook Received
[2026-01-26 10:30:00] local.INFO: Processing stock in: BR5KG, qty: 100
[2026-01-26 10:30:00] local.INFO: WhatsApp reply sent
```

---

## Error Handling

### Produk Tidak Ditemukan:
```
❌ Produk dengan kode 'XXX' tidak ditemukan
```

**Solusi:** Cek SKU atau nama produk di database

### Stock Tidak Cukup (Stock Out):
```
❌ Stock tidak cukup!

📦 Beras Premium 5kg
📊 Stock: 30
❌ Diminta: 50
```

**Solusi:** Kurangi jumlah stock out

### Format Pesan Salah:
```
*📚 PANDUAN WHATSAPP INVENTORY*
[Help message...]
```

**Solusi:** Ikuti format yang benar (lihat HELP)

### Nomor Tidak Terdaftar:
```
⛔ Nomor tidak terdaftar sebagai admin. Hubungi SuperAdmin untuk mendaftar.
```

**Solusi:**
1. Login ke admin panel
2. User Management → Edit Admin
3. Pastikan phone number sesuai
4. Role harus `admin` atau `superadmin`

---

## Security

### 1. Webhook Verification Token:

WhatsApp Business API menggunakan verify token untuk keamanan:

```php
if ($mode === 'subscribe' && $token === config('services.whatsapp.verify_token')) {
    return response($challenge, 200);
}
```

**Best Practice:**
- Gunakan token yang unik dan kompleks
- Jangan commit token di git (gunakan .env)
- Rotate token secara berkala

### 2. Admin Authorization:

Hanya user dengan role `admin` atau `superadmin` yang bisa akses:

```php
private function isAuthorizedAdmin($phone)
{
    return User::whereIn('role', ['admin', 'superadmin'])
                ->where('phone', 'like', '%' . $cleanPhone . '%')
                ->exists();
}
```

### 3. HTTPS Required:

WhatsApp Business API **WAJIB** menggunakan HTTPS:
- Production: gunakan SSL certificate (Let's Encrypt, Cloudflare)
- Development: gunakan ngrok atau Cloudflare Tunnel

### 4. Rate Limiting:

Tambahkan di `routes/api.php` (opsional):

```php
Route::match(['get', 'post'], '/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->middleware('throttle:60,1'); // Max 60 requests per minute
```

---

## Monitoring & Logs

### 1. Check Processing Logs:

```bash
tail -f storage/logs/laravel.log | grep WhatsApp
```

### 2. Failed Messages:

Jika ada error, cek:
```bash
grep "WhatsApp.*Error" storage/logs/laravel.log
```

### 3. Database Audit:

Query terakhir inventory movements:
```sql
SELECT * FROM inventory_movements
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
ORDER BY created_at DESC;
```

---

## FAQ

### Q: Apakah bisa pakai WhatsApp pribadi?
**A:** Tidak. Harus menggunakan **WhatsApp Business API** (bukan WhatsApp Business App biasa). Butuh verifikasi Meta atau gunakan provider seperti Twilio.

### Q: Berapa biaya WhatsApp Business API?
**A:**
- **Meta Official**: Free untuk 1,000 conversations/month
- **Twilio**: $0.005/message (sandbox gratis)
- **Vonage**: Pricing varies

### Q: Apakah bisa kirim gambar/foto produk?
**A:** Saat ini hanya support text. Image support bisa ditambahkan di versi mendatang.

### Q: Bagaimana jika salah input?
**A:** Stock sudah terupdate. Bisa koreksi manual via admin panel:
- Dashboard → Inventory → Inventory Movements
- Buat adjustment manual untuk koreksi

### Q: Apakah ada batasan jumlah pesan?
**A:** Tergantung provider:
- Meta: 1,000 free/month
- Twilio: Unlimited (bayar per message)
- Tambahkan rate limiting jika perlu

---

## Roadmap

**Future Enhancements:**

- [ ] Support gambar/foto untuk bukti barang
- [ ] Bulk import (multiple products in one message)
- [ ] Stock opname via WhatsApp
- [ ] Barcode scanning via WhatsApp camera
- [ ] WhatsApp notification untuk stock habis
- [ ] WhatsApp reminder untuk stock kadaluarsa
- [ ] Multi-language support (English, Indonesia)
- [ ] Voice message parsing

---

## Support

**Butuh bantuan?**

1. **Documentation**: Baca file ini lengkap
2. **Logs**: Check `storage/logs/laravel.log`
3. **Test**: Gunakan cURL untuk debug
4. **GitHub Issues**: Report bugs di repo project

**WhatsApp Provider Support:**
- Meta: https://developers.facebook.com/support/
- Twilio: https://support.twilio.com/
- Vonage: https://developer.vonage.com/en/support

---

## Changelog

**v1.0.0 - 2026-01-26**
- Initial release
- Support stock in/out via WhatsApp
- Multi-provider support (Meta, Twilio, Vonage)
- Auto-create financial transactions
- Admin authorization
- Help command

---

**Dokumentasi ini adalah bagian dari Amanah Shop - Sistem Koperasi Simpan Pinjam**
