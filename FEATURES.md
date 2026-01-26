# Amanah Shop - Complete Feature List

## 🎯 Overview

**Amanah Shop** adalah sistem manajemen toko koperasi simpan pinjam yang lengkap dengan fitur:

1. **E-Commerce** (Frontend - Customer facing)
2. **Credit/Installment System** (Cicilan dengan bunga flat)
3. **Backend Business Management** (Admin only)
4. **WhatsApp Integration** (Pencatatan via WhatsApp)

---

## 📦 1. E-Commerce Features (Frontend)

### Customer Features:

✅ **Product Browsing**
- Katalog produk dengan filter kategori
- Search produk
- Product detail dengan gambar
- Stock availability real-time

✅ **Shopping Cart**
- Add/remove items
- Update quantity
- Multi-product checkout
- Shopping cart persistence

✅ **Checkout & Shipping**
- Integrated with Biteship API
- Multi-courier options (JNE, J&T, SiCepat, dll)
- Real-time shipping cost calculation
- Address management

✅ **Payment Methods**
- **Cash Payment**: Bayar penuh via Midtrans
- **Credit Payment**: Cicilan dengan bunga flat
  - Pilih jangka waktu: 3, 6, 12, 24 bulan
  - Optional down payment (DP)
  - Real-time credit calculator
  - Auto-generate installment schedule

✅ **Order Tracking**
- Order history
- Installment payment schedule
- Payment status tracking
- Shipping tracking (Binderbyte API)

---

## 💳 2. Credit/Installment System

### Interest Calculation:

**Bunga Flat System:**
```
Principal = Order Total - Down Payment
Interest = Principal × (Interest Rate / 100)
Total Credit = Principal + Interest
Monthly Installment = Total Credit / Months
```

**Available Plans:**
- **3 Bulan**: 5% flat interest
- **6 Bulan**: 8% flat interest
- **12 Bulan**: 12% flat interest
- **24 Bulan**: 20% flat interest

### Example Calculation:

```
Order Total: Rp 5,000,000
Down Payment: Rp 1,000,000 (20%)
Plan: 6 bulan (8% flat)

Principal = 5,000,000 - 1,000,000 = Rp 4,000,000
Interest = 4,000,000 × 0.08 = Rp 320,000
Total Credit = 4,000,000 + 320,000 = Rp 4,320,000
Monthly = 4,320,000 / 6 = Rp 720,000 per bulan
```

### Customer Features:

✅ **Credit Checkout**
- Choose installment plan at checkout
- Real-time credit calculator
- Optional down payment entry
- See monthly payment before confirm

✅ **Installment Management**
- View payment schedule (12 months detail)
- Upload payment proof per installment
- Payment history
- Overdue detection
- Remaining balance tracking

### Admin Features:

✅ **Credit Verification**
- Approve/reject credit applications
- Verify installment payments
- Approve payment proofs
- Manual payment recording

✅ **Overdue Management**
- Auto-detect overdue payments (daily cron)
- Overdue status alerts
- Customer credit history

---

## 🏪 3. Backend Business Management (Admin Only)

### Manual Credit System (Offline Loans)

**Use Case:** Customer datang ke toko, pinjam uang tunai (bukan beli barang dari website).

✅ **Features:**
- Create manual credit entry
- Customer name & phone (tidak harus registered user)
- Free-text description (e.g., "Pinjaman modal usaha")
- Same installment plans (3, 6, 12, 24 bulan)
- Payment tracking separate from e-commerce orders
- Generate credit number: `MC20260126XXXX`

**Database Tables:**
- `manual_credits`
- `manual_credit_payments`

**Routes:**
```php
Route::resource('manual-credits', ManualCreditController::class);
Route::post('manual-credits/{credit}/record-payment', ...);
```

---

### Financial Transaction System

**Use Case:** Pencatatan lengkap semua pemasukan & pengeluaran toko (bukan hanya dari penjualan).

✅ **Income Categories:**
1. Penjualan Online (auto dari orders)
2. Penjualan Offline (manual entry)
3. Pembayaran Cicilan (auto dari installment payments)
4. Bunga Pinjaman (auto calculated)
5. Lain-lain (manual entry)

✅ **Expense Categories:**
1. Pembelian Barang (auto dari stock in)
2. Gaji Karyawan
3. Sewa Tempat
4. Listrik & Air
5. Transport & Pengiriman
6. Perawatan & Perbaikan
7. Promosi & Marketing
8. Operasional Lainnya

✅ **Features:**
- Manual transaction entry (income/expense)
- Auto-link to orders/credits via polymorphic relationships
- Upload attachment (receipt/invoice)
- Date range filtering
- Category-based reporting
- Export to Excel/PDF

**Database Tables:**
- `transaction_categories`
- `financial_transactions`

**Transaction Number:** `FT20260126XXXX`

---

### Supplier Management

**Use Case:** Track suppliers untuk pembelian barang.

✅ **Features:**
- CRUD suppliers (name, contact, phone, email, address)
- Supplier code: `SUP001`, `SUP002`, etc.
- Link suppliers to inventory movements
- Total purchases per supplier
- Active/inactive status
- Soft deletes

**Database Table:** `suppliers`

**Fields:**
```php
- code (unique)
- name
- contact_person
- phone
- email
- address
- notes
- is_active
```

---

### Enhanced Inventory Management

**Use Case:** Track semua pergerakan stock (in/out) dengan detail lengkap.

✅ **Stock In Sources:**
1. Purchase from supplier (via admin panel)
2. **WhatsApp message** (admin kirim pesan)
3. Manual adjustment

✅ **Stock Out Sources:**
1. Customer order (auto)
2. Damaged goods
3. Lost items
4. **WhatsApp message** (admin kirim pesan)
5. Manual adjustment

✅ **Enhanced Inventory Movement Fields:**
```php
- product_id
- type (in/out)
- quantity
- stock_before
- stock_after
- reference_type (Order, ManualCredit, etc.)
- reference_id
- supplier_id (NEW - untuk stock in)
- document_number (NEW - PO/Invoice number)
- unit_price (NEW)
- total_price (NEW)
- notes
- created_by (admin user)
```

✅ **Reports:**
- Inventory movements by date range
- Stock in/out summary
- By product
- By supplier
- By reference type
- Export to Excel

---

## 📱 4. WhatsApp Integration

**Use Case:** Admin dapat mencatat stock masuk/keluar **TANPA login ke admin panel**, cukup kirim WhatsApp.

### Supported Commands:

#### 1. Stock In (Simple):
```
MASUK BR5KG 100 50000 SUP001
```
→ Stock in 100 unit Beras 5kg, harga @50,000 dari supplier SUP001

#### 2. Stock In (Detailed):
```
MASUK
Produk: BR5KG
Qty: 100
Harga: 50000
Supplier: SUP001
Notes: PO2026-001
```

#### 3. Stock Out (Simple):
```
KELUAR BR5KG 50 Rusak
```
→ Stock out 50 unit Beras 5kg, alasan: rusak

#### 4. Stock Out (Detailed):
```
KELUAR
Produk: BR5KG
Qty: 50
Notes: Kemasan robek saat bongkar muat
```

#### 5. Help:
```
HELP
```
atau
```
BANTUAN
```

### Features:

✅ **Admin Authorization**
- Only registered admin phone numbers can use
- Auto-match phone formats (+62, 62, 0)
- Reject unauthorized numbers

✅ **Auto-Processing**
- Update product stock
- Create inventory movement record
- Create financial transaction (if price provided)
- Link to supplier (if provided)
- Send confirmation message

✅ **Multi-Provider Support**
- Official WhatsApp Business API (Meta)
- Twilio WhatsApp
- Vonage WhatsApp

✅ **Response Messages**
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

### Setup:

**Webhook URL:**
```
POST https://yourdomain.com/api/webhook/whatsapp
```

**Files:**
- `app/Services/WhatsAppInventoryService.php` - Message processing
- `app/Http/Controllers/Api/WhatsAppWebhookController.php` - Webhook handler
- `routes/api.php` - Webhook route
- `config/services.php` - WhatsApp config

**Documentation:**
- `WHATSAPP_INTEGRATION.md` - Complete setup guide

---

## 📊 5. Admin Reports

### Sales Reports:
- Total sales (cash vs credit)
- Sales by period (daily, monthly, yearly)
- Sales by product category
- Top selling products
- Revenue breakdown

### Installment Reports:
- Active credits
- Paid installments
- Pending payments
- Overdue payments
- Interest income

### Inventory Reports:
- Stock movements (in/out)
- Stock by product
- Low stock alerts
- Stock by supplier
- Inventory value

### Financial Reports:
- Income statement (monthly)
- Expense breakdown
- Cash flow
- Profit/loss
- By category

### Customer Reports:
- Customer credit history
- Payment behavior
- Outstanding balance
- Total purchases

---

## 🔐 6. User Roles & Permissions

### SuperAdmin:
- Full system access
- Manage users (create admin)
- Manage installment plans
- View all reports
- System settings
- Financial management

### Admin:
- Product management
- Order management
- Credit verification
- Installment tracking
- Inventory management
- Supplier management
- Financial transactions
- WhatsApp inventory (if phone registered)
- Reports (own data)

### Customer (User):
- Browse products
- Shopping cart
- Checkout (cash/credit)
- View own orders
- Track shipping
- Upload payment proofs
- View installment schedule

---

## 🗄️ Database Schema Summary

### Main Tables:
1. `users` - Customers & admins
2. `products` - Product catalog
3. `categories` - Product categories
4. `orders` - Customer orders
5. `order_items` - Order details

### Credit System:
6. `installment_plans` - Plan configurations (3mo, 6mo, 12mo, 24mo)
7. `product_credit_settings` - Per-product credit settings
8. `installment_payments` - Payment schedule for orders
9. `manual_credits` - Offline credit entries
10. `manual_credit_payments` - Manual credit payments

### Inventory:
11. `inventory_movements` - Stock in/out tracking
12. `suppliers` - Supplier master data

### Financial:
13. `transaction_categories` - Income/expense categories
14. `financial_transactions` - All financial records

### Shipping:
15. `shipping_addresses` - Customer addresses
16. `shipping_rates` - Cached shipping costs

---

## 🚀 API Endpoints

### Public API:
```
GET  /api/webhook/whatsapp    - WhatsApp webhook verification
POST /api/webhook/whatsapp    - WhatsApp message handler
```

### Authenticated API:
```
GET /api/user                 - Get authenticated user
```

---

## ⚙️ Configuration Files

### Environment Variables (.env):

```env
# App
APP_NAME="Amanah Shop"
DB_DATABASE=amanah_shop

# Midtrans
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=

# Biteship (Shipping)
BITESHIP_API_KEY=

# WhatsApp
WHATSAPP_PROVIDER=official
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_PHONE_NUMBER_ID=
```

### Config Files:
- `config/services.php` - Third-party API credentials
- `config/app.php` - App settings
- `config/database.php` - Database settings

---

## 📅 Scheduled Tasks (Cron)

### Daily Tasks:

**1. Check Overdue Payments**
```bash
php artisan credit:check-overdue
```
- Runs daily at 00:00
- Marks pending payments as overdue
- Updates order status
- Logs: `storage/logs/overdue-checks.log`

**Setup in Production:**
```bash
crontab -e
```

Add:
```
0 0 * * * cd /path/to/amanah-shop && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🧪 Testing

### Run Migrations:
```bash
php artisan migrate:fresh --seed
```

### Test Data Created:
- SuperAdmin: `superadmin@amanahshop.com` / `123`
- Admin: `admin@amanahshop.com` / `123`
- Users: `budi@example.com` / `123`, `siti@example.com` / `123`
- 4 Installment Plans
- 13 Transaction Categories
- Sample Products

### Test Credit Checkout:
1. Login as customer
2. Add product to cart
3. Checkout → Select "Credit"
4. Choose 12-month plan
5. Enter DP: Rp 1,000,000
6. Verify calculation
7. Pay DP via Midtrans
8. Check installment schedule

### Test WhatsApp:
```bash
curl -X POST http://localhost:8000/api/webhook/whatsapp \
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

---

## 📚 Documentation Files

1. **README.md** - Project overview & setup
2. **CLAUDE.md** - Development commands
3. **SETUP_AMANAH_SHOP.md** - Installation guide
4. **CHANGELOG_KREDIT.md** - Credit system changelog
5. **FEATURES.md** - This file (complete feature list)
6. **WHATSAPP_INTEGRATION.md** - WhatsApp setup guide

---

## 🎯 Use Cases

### Use Case 1: Customer Beli Barang Kredit
1. Customer browse products
2. Add to cart → Checkout
3. Select "Bayar Kredit"
4. Choose plan: 12 bulan
5. Enter DP: 30% (Rp 1,500,000)
6. Sistem calculate:
   - Principal: Rp 3,500,000
   - Interest 12%: Rp 420,000
   - Total: Rp 3,920,000
   - Monthly: Rp 326,667
7. Pay DP via Midtrans
8. Order processed, stock updated
9. Customer can view 12-month schedule
10. Upload payment proof tiap bulan
11. Admin verify payments

### Use Case 2: Customer Pinjam Uang Tunai (Bukan Beli Barang)
1. Customer datang ke toko
2. Admin login → Manual Credits
3. Create new credit:
   - Name: Budi Santoso
   - Phone: 081234567890
   - Amount: Rp 5,000,000
   - Description: "Pinjaman modal usaha warung"
   - Plan: 6 bulan (8%)
   - DP: Rp 0
4. Sistem calculate & generate schedule
5. Customer bayar tiap bulan ke toko
6. Admin record payments manually
7. System auto-update balance

### Use Case 3: Admin Stock In via WhatsApp
1. Admin terima barang dari supplier
2. Buka WhatsApp
3. Kirim pesan ke nomor bisnis:
   ```
   MASUK
   Produk: BR5KG
   Qty: 200
   Harga: 48000
   Supplier: SUP001
   Notes: PO2026-015
   ```
4. System auto:
   - Update stock BR5KG +200
   - Create inventory movement
   - Create financial transaction (expense)
   - Link to supplier SUP001
5. Admin dapat konfirmasi instant:
   ```
   ✅ STOCK MASUK BERHASIL
   📦 Beras Premium 5kg
   Stock: 450 → 650
   💵 Total: Rp 9,600,000
   ```

### Use Case 4: Admin Catat Barang Rusak via WhatsApp
1. Saat bongkar muat, 10 karung robek
2. Admin kirim WhatsApp:
   ```
   KELUAR BR5KG 10 Kemasan robek saat bongkar
   ```
3. System auto:
   - Update stock BR5KG -10
   - Create inventory movement (type: out)
   - Record notes
4. Admin dapat konfirmasi
5. Laporan inventory mencatat

### Use Case 5: Owner Lihat Laporan Keuangan Bulanan
1. Login as SuperAdmin
2. Reports → Financial Transactions
3. Filter: January 2026
4. View summary:
   - **Income**: Rp 125,000,000
     - Penjualan Online: Rp 80,000,000
     - Pembayaran Cicilan: Rp 35,000,000
     - Bunga: Rp 10,000,000
   - **Expense**: Rp 60,000,000
     - Pembelian Barang: Rp 45,000,000
     - Gaji: Rp 10,000,000
     - Operasional: Rp 5,000,000
   - **Net Profit**: Rp 65,000,000
5. Export to Excel

---

## 🔮 Future Enhancements

**Planned Features:**
- [ ] WhatsApp payment reminders (auto-send 3 days before due)
- [ ] SMS notifications
- [ ] Credit limit per customer
- [ ] Early payment discount
- [ ] Late payment penalty
- [ ] Multi-warehouse support
- [ ] Barcode scanning
- [ ] Mobile app (Flutter)
- [ ] Customer loyalty program
- [ ] Promo/discount system
- [ ] Advanced analytics dashboard

---

## 📞 Support

**Issues & Bugs:**
- GitHub Issues: [github.com/your-repo/issues]

**Documentation:**
- Read all .md files in root directory
- Check `storage/logs/laravel.log` for errors

**Community:**
- Discord: [your-discord-link]
- Email: support@amanahshop.com

---

**Amanah Shop v1.0.0 - Sistem Koperasi Simpan Pinjam Lengkap**

© 2026 Amanah Shop. All rights reserved.
