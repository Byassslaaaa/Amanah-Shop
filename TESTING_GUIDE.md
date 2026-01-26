# Testing Guide - Amanah Shop

## Quick Test Checklist

Gunakan checklist ini untuk memverifikasi semua fitur bekerja dengan baik.

---

## ✅ 1. Database & Migration Test

### Test Migration:
```bash
# Backup database lama (jika ada)
php artisan db:seed --class=BackupSeeder

# Fresh migration dengan seeder
php artisan migrate:fresh --seed
```

**Expected Output:**
```
Migration table created successfully.
Migrating: 2026_01_26_000001_remove_villages_concept
Migrated:  2026_01_26_000001_remove_villages_concept
...
Seeding: Database\Seeders\Credit\InstallmentPlanSeeder
Seeded:  Database\Seeders\Credit\InstallmentPlanSeeder
Seeding: Database\Seeders\Finance\TransactionCategorySeeder
Seeded:  Database\Seeders\Finance\TransactionCategorySeeder

🎉 Amanah Shop - Database seeding completed!
📊 Summary:
   - Users: 4
   - Installment Plans: 4
   - Transaction Categories: 13
   - Products: XX

🔐 Login credentials:
   SuperAdmin: superadmin@amanahshop.com / 123
   Admin: admin@amanahshop.com / 123
```

### Verify Tables Created:
```sql
SHOW TABLES;
```

**Expected Tables (23+):**
```
- users
- products
- categories
- orders
- order_items
- installment_plans ✅ NEW
- product_credit_settings ✅ NEW
- installment_payments ✅ NEW
- manual_credits ✅ NEW
- manual_credit_payments ✅ NEW
- suppliers ✅ NEW
- inventory_movements ✅ NEW (enhanced)
- transaction_categories ✅ NEW
- financial_transactions ✅ NEW
```

### Verify Seeder Data:
```sql
-- Check installment plans
SELECT * FROM installment_plans;
-- Expected: 4 rows (3mo, 6mo, 12mo, 24mo)

-- Check transaction categories
SELECT * FROM transaction_categories;
-- Expected: 13 rows (5 income + 8 expense)

-- Check admins
SELECT id, name, email, role, phone FROM users WHERE role IN ('admin', 'superadmin');
-- Expected: 2 rows
```

---

## ✅ 2. Credit System Test (Frontend)

### 2.1 Test Cash Checkout:

**Steps:**
1. Open browser: `http://localhost:8000`
2. Login as customer: `budi@example.com` / `123`
3. Browse products → Add to cart
4. Checkout
5. Select **"Bayar Tunai"**
6. Choose shipping method
7. Choose Midtrans payment
8. Complete payment
9. ✅ Order status = processing
10. ✅ Stock updated
11. ✅ Inventory movement created

**Verify Database:**
```sql
SELECT id, order_number, payment_type, total_amount, payment_status
FROM orders
ORDER BY created_at DESC
LIMIT 1;
-- payment_type should be 'cash'

SELECT * FROM inventory_movements
ORDER BY created_at DESC
LIMIT 5;
-- Should see 'out' movement for order items
```

---

### 2.2 Test Credit Checkout:

**Steps:**
1. Login as customer: `budi@example.com` / `123`
2. Add product (min Rp 1,000,000) to cart
3. Checkout
4. Select **"Bayar Kredit"**
5. **Test Calculator:**
   - Enter DP: `500000`
   - Select Plan: "12 Bulan - Bunga 12% flat"
   - ✅ Verify calculation displays correctly:
     ```
     Total Harga: Rp 1,000,000
     Uang Muka: Rp 500,000
     Pokok Pinjaman: Rp 500,000
     Bunga (Flat): Rp 60,000
     Total yang Harus Dibayar: Rp 560,000
     Cicilan per Bulan: Rp 46,667
     ```
6. Confirm order
7. Pay DP via Midtrans (Rp 500,000)
8. ✅ Check installment schedule page

**Verify Database:**
```sql
-- Check order
SELECT
    id, order_number, payment_type,
    down_payment_amount, principal_amount,
    interest_amount, total_credit_amount,
    monthly_installment, installment_months,
    payment_status
FROM orders
WHERE payment_type = 'credit'
ORDER BY created_at DESC
LIMIT 1;

-- Check installment schedule (should be 12 rows)
SELECT
    installment_number,
    due_date,
    amount_due,
    status
FROM installment_payments
WHERE order_id = [LAST_ORDER_ID]
ORDER BY installment_number;
-- Expected: 12 rows, all status = 'pending'
```

---

### 2.3 Test Customer Installment View:

**Steps:**
1. Login as customer
2. Go to "My Orders" or "My Installments"
3. Click credit order
4. ✅ Verify displays:
   - Credit summary (DP, principal, interest, total)
   - Payment schedule table (12 rows)
   - Due dates
   - Payment status
5. Click "Upload Payment Proof" for installment #1
6. Upload image
7. ✅ Status changes to "pending verification"

**Verify Database:**
```sql
SELECT
    installment_number,
    amount_paid,
    paid_date,
    status,
    payment_proof
FROM installment_payments
WHERE order_id = [ORDER_ID]
AND installment_number = 1;
-- status should be 'pending' or updated
```

---

## ✅ 3. Admin Credit Management Test

### 3.1 Test Credit Verification:

**Steps:**
1. Login as admin: `admin@amanahshop.com` / `123`
2. Go to Admin → Installments
3. ✅ See list of credit orders
4. Click one order → View detail
5. ✅ See installment schedule
6. Click "Verify Payment" for installment with proof uploaded
7. Enter verification notes
8. Click "Approve"
9. ✅ Status changes to "paid"
10. ✅ Order balance updates

**Verify Database:**
```sql
SELECT
    id,
    installment_number,
    amount_paid,
    status,
    verified_at,
    verified_by
FROM installment_payments
WHERE status = 'paid'
ORDER BY verified_at DESC
LIMIT 5;
```

---

### 3.2 Test Overdue Detection:

**Manual Test (Create Overdue Payment):**
```sql
-- Set due date to past
UPDATE installment_payments
SET due_date = DATE_SUB(NOW(), INTERVAL 5 DAY)
WHERE id = 1;
```

**Run Command:**
```bash
php artisan credit:check-overdue
```

**Expected Output:**
```
Checking overdue installment payments...
Found 1 overdue payment(s)
Updated order statuses
Done!
```

**Verify Database:**
```sql
SELECT
    ip.id,
    ip.installment_number,
    ip.due_date,
    ip.status,
    o.payment_status
FROM installment_payments ip
JOIN orders o ON ip.order_id = o.id
WHERE ip.status = 'overdue';
-- Should see the payment with overdue status
```

---

## ✅ 4. Manual Credit System Test (Backend Only)

### 4.1 Create Manual Credit:

**Steps:**
1. Login as admin
2. Go to Admin → Manual Credits → Create
3. Fill form:
   - Customer Name: "Andi Wijaya"
   - Phone: "081298765432"
   - Description: "Pinjaman modal usaha warung"
   - Loan Amount: Rp 10,000,000
   - Down Payment: Rp 2,000,000
   - Plan: "6 Bulan - Bunga 8% flat"
4. Submit
5. ✅ Verify calculation:
   ```
   Principal: Rp 8,000,000
   Interest: Rp 640,000
   Total: Rp 8,640,000
   Monthly: Rp 1,440,000
   ```
6. ✅ See 6-month payment schedule

**Verify Database:**
```sql
SELECT * FROM manual_credits
ORDER BY created_at DESC
LIMIT 1;

SELECT * FROM manual_credit_payments
WHERE manual_credit_id = [LAST_CREDIT_ID]
ORDER BY installment_number;
-- Expected: 6 rows
```

---

### 4.2 Record Manual Payment:

**Steps:**
1. Click "Record Payment" for installment #1
2. Enter:
   - Amount: Rp 1,440,000
   - Payment Date: Today
   - Notes: "Cash payment"
3. Submit
4. ✅ Payment marked as paid
5. ✅ Credit balance updated

**Verify Database:**
```sql
SELECT
    total_amount,
    total_paid,
    remaining_balance,
    status
FROM manual_credits
WHERE id = [CREDIT_ID];
```

---

## ✅ 5. Financial Transaction System Test

### 5.1 Create Manual Transaction (Income):

**Steps:**
1. Admin → Financial Transactions → Create
2. Type: Income
3. Category: "Penjualan Offline"
4. Date: Today
5. Amount: Rp 5,000,000
6. Description: "Penjualan langsung di toko"
7. Upload receipt (optional)
8. Submit
9. ✅ Transaction created

**Verify Database:**
```sql
SELECT * FROM financial_transactions
WHERE type = 'income'
ORDER BY created_at DESC
LIMIT 5;
```

---

### 5.2 Create Manual Transaction (Expense):

**Steps:**
1. Admin → Financial Transactions → Create
2. Type: Expense
3. Category: "Gaji Karyawan"
4. Date: Today
5. Amount: Rp 3,000,000
6. Description: "Gaji bulan Januari"
7. Submit

**Verify Database:**
```sql
SELECT * FROM financial_transactions
WHERE type = 'expense'
ORDER BY created_at DESC
LIMIT 5;
```

---

### 5.3 View Financial Reports:

**Steps:**
1. Admin → Reports → Financial
2. Filter: This Month
3. ✅ Verify displays:
   - Total Income
   - Total Expense
   - Net Profit/Loss
   - Breakdown by category
4. Export to Excel
5. ✅ File downloaded

---

## ✅ 6. Supplier Management Test

### 6.1 Create Supplier:

**Steps:**
1. Admin → Suppliers → Create
2. Fill:
   - Code: SUP001
   - Name: "PT Sumber Pangan Jaya"
   - Contact Person: "Budi"
   - Phone: "021-12345678"
   - Email: "supplier@example.com"
   - Address: "Jakarta"
3. Submit
4. ✅ Supplier created

**Verify Database:**
```sql
SELECT * FROM suppliers
ORDER BY created_at DESC
LIMIT 5;
```

---

## ✅ 7. Inventory Management Test

### 7.1 Manual Stock In (Admin Panel):

**Steps:**
1. Admin → Inventory → Stock In
2. Select Product: "Beras Premium 5kg"
3. Quantity: 100
4. Supplier: "PT Sumber Pangan Jaya"
5. Unit Price: Rp 50,000
6. Document Number: "PO2026-001"
7. Notes: "Pembelian rutin"
8. Submit
9. ✅ Stock updated (+100)
10. ✅ Inventory movement created
11. ✅ Financial transaction created (expense)

**Verify Database:**
```sql
-- Check inventory movement
SELECT * FROM inventory_movements
WHERE type = 'in'
ORDER BY created_at DESC
LIMIT 1;

-- Check financial transaction auto-created
SELECT * FROM financial_transactions
WHERE reference_type = 'App\\Models\\Inventory\\InventoryMovement'
ORDER BY created_at DESC
LIMIT 1;
-- Should see expense for Rp 5,000,000 (100 × 50,000)

-- Check product stock
SELECT id, name, stock FROM products
WHERE sku = 'BR5KG';
-- Stock should be increased
```

---

### 7.2 Manual Stock Out (Admin Panel):

**Steps:**
1. Admin → Inventory → Stock Out
2. Select Product: "Beras Premium 5kg"
3. Quantity: 10
4. Reason: "Barang rusak"
5. Notes: "Kemasan robek"
6. Submit
7. ✅ Stock decreased (-10)

**Verify Database:**
```sql
SELECT * FROM inventory_movements
WHERE type = 'out'
AND notes LIKE '%rusak%'
ORDER BY created_at DESC
LIMIT 1;
```

---

## ✅ 8. WhatsApp Integration Test

### 8.1 Setup Test Environment:

**Option 1: Use ngrok (Recommended for local testing)**
```bash
# Terminal 1: Start Laravel
php artisan serve

# Terminal 2: Start ngrok
ngrok http 8000
```

Copy ngrok URL: `https://abc123.ngrok.io`

---

### 8.2 Test Webhook Verification (GET):

```bash
curl -X GET "http://localhost:8000/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=amanah_shop_webhook_token&hub.challenge=TEST123"
```

**Expected Response:**
```
TEST123
```

✅ If you get the challenge back, webhook verification works!

---

### 8.3 Test Stock In via cURL (POST):

**Official WhatsApp Format:**
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

**Expected Response:**
```json
{
  "status": "success",
  "processed": true,
  "message": "✅ *STOCK MASUK BERHASIL*\n\n📦 Produk: Beras Premium 5kg\n➕ Qty: 100\n..."
}
```

**Verify Database:**
```sql
SELECT * FROM inventory_movements
WHERE notes LIKE '%WhatsApp%'
ORDER BY created_at DESC
LIMIT 1;
-- Should see new 'in' movement with qty 100
```

---

### 8.4 Test Stock Out via cURL:

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
            "body": "KELUAR BR5KG 50 Rusak saat bongkar muat"
          }
        }]
      }
    }]
  }]
}'
```

**Expected Response:**
```json
{
  "status": "success",
  "processed": true,
  "message": "✅ *STOCK KELUAR BERHASIL*\n\n📦 Produk: Beras Premium 5kg\n➖ Qty: 50\n..."
}
```

---

### 8.5 Test Detailed Format:

```bash
curl -X POST http://localhost:8000/api/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "6282136547891",
          "text": {
            "body": "MASUK\nProduk: BR5KG\nQty: 200\nHarga: 48000\nSupplier: SUP001\nNotes: PO2026-015"
          }
        }]
      }
    }]
  }]
}'
```

**Verify:**
- Stock updated
- Supplier linked
- Document number saved
- Financial transaction created

---

### 8.6 Test Help Command:

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
            "body": "HELP"
          }
        }]
      }
    }]
  }]
}'
```

**Expected:** Long help message with all formats

---

### 8.7 Test Unauthorized Number:

```bash
curl -X POST http://localhost:8000/api/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "6281999999999",
          "text": {
            "body": "MASUK BR5KG 100 50000"
          }
        }]
      }
    }]
  }]
}'
```

**Expected Response:**
```json
{
  "status": "success",
  "processed": false,
  "message": "⛔ Nomor tidak terdaftar sebagai admin..."
}
```

---

### 8.8 Test Wrong Format:

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
            "body": "MASUK SALAH"
          }
        }]
      }
    }]
  }]
}'
```

**Expected:** Help message displayed

---

### 8.9 Test Twilio Format:

```bash
curl -X POST http://localhost:8000/api/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{
  "From": "whatsapp:+6281234567890",
  "Body": "MASUK BR5KG 100 50000 SUP001"
}'
```

Should work with Twilio format too!

---

## ✅ 9. Check Logs

### Laravel Logs:
```bash
tail -f storage/logs/laravel.log
```

**Look for:**
```
[2026-01-26 10:00:00] local.INFO: WhatsApp Webhook Received
[2026-01-26 10:00:00] local.INFO: Processing MASUK command
[2026-01-26 10:00:00] local.INFO: Stock updated: BR5KG +100
```

### Overdue Check Logs:
```bash
cat storage/logs/overdue-checks.log
```

---

## ✅ 10. Performance Test

### Test with Multiple Products:

**Create 100 products:**
```bash
php artisan tinker
```

```php
for ($i = 1; $i <= 100; $i++) {
    App\Models\Product\Product::create([
        'category_id' => 1,
        'sku' => 'PROD' . str_pad($i, 4, '0', STR_PAD_LEFT),
        'name' => 'Test Product ' . $i,
        'description' => 'Description',
        'price' => rand(10000, 100000),
        'stock' => rand(10, 100),
        'is_active' => true,
    ]);
}
```

### Test Checkout Performance:
1. Add 10 items to cart
2. Measure checkout time
3. ✅ Should complete < 3 seconds

### Test Report Performance:
1. Generate inventory report
2. Filter by date range (1 month)
3. ✅ Should load < 2 seconds

---

## ✅ 11. Security Test

### Test CSRF Protection:
```bash
curl -X POST http://localhost:8000/admin/products \
-d "name=Hacked"
```
**Expected:** 419 Page Expired (CSRF token missing)

### Test Authorization:

**Try access admin page as customer:**
1. Login as `budi@example.com`
2. Go to `http://localhost:8000/admin/dashboard`
3. ✅ Should redirect to home with error

### Test Installment Payment Authorization:

**Try view other customer's installments:**
1. Login as `budi@example.com`
2. Create credit order (order_id = 1)
3. Logout
4. Login as `siti@example.com`
5. Try access `/user/installments/1`
6. ✅ Should get 403 Forbidden

---

## ✅ 12. Branding Check

### Check "Amanah Shop" Appears:

**Frontend:**
- ✅ Homepage title
- ✅ Header logo area
- ✅ Footer copyright
- ✅ Email subjects

**Admin:**
- ✅ Admin dashboard title
- ✅ Sidebar branding
- ✅ Email notifications

### Check NO "BUMDes" or "Desa":

**Search entire codebase:**
```bash
grep -r "BUMDes" resources/views/
grep -r "Desa" resources/views/ | grep -v "Sendangsari"
```

**Expected:** No results (except "Sendangsari" allowed as shop name)

---

## 📊 Summary Checklist

- [ ] Database migrated successfully
- [ ] All seeders ran
- [ ] Cash checkout works
- [ ] Credit checkout works
- [ ] Credit calculator accurate
- [ ] Installment schedule generated
- [ ] Customer can upload payment proof
- [ ] Admin can verify payments
- [ ] Overdue detection works
- [ ] Manual credit creation works
- [ ] Financial transaction recording works
- [ ] Supplier management works
- [ ] Inventory movements tracked
- [ ] WhatsApp webhook responds
- [ ] WhatsApp stock in works
- [ ] WhatsApp stock out works
- [ ] WhatsApp help works
- [ ] WhatsApp auth blocks unauthorized
- [ ] Reports display correctly
- [ ] Branding updated (Amanah Shop)
- [ ] No "BUMDes" or "Desa" references
- [ ] Security checks passed

---

## 🚨 Common Issues & Solutions

### Issue: Migration fails

**Error:** `SQLSTATE[42S01]: Base table or view already exists`

**Solution:**
```bash
php artisan migrate:fresh --seed
```

---

### Issue: WhatsApp webhook returns 500

**Check:**
1. Log file: `storage/logs/laravel.log`
2. Admin phone number in database
3. Product SKU exists
4. Payload format correct

**Debug:**
```bash
tail -f storage/logs/laravel.log
```

---

### Issue: Credit calculation wrong

**Verify:**
```php
php artisan tinker

$service = new App\Services\CreditCalculationService();
$result = $service->calculateCredit(5000000, 1000000, 3);
dd($result);
```

Expected:
```php
[
  "principal" => 4000000,
  "interest_amount" => 200000,  // 4M × 5%
  "total_credit" => 4200000,
  "monthly_installment" => 1400000,  // 4.2M / 3
]
```

---

### Issue: Stock not updating

**Check inventory_movements table:**
```sql
SELECT * FROM inventory_movements
WHERE product_id = [PRODUCT_ID]
ORDER BY created_at DESC;
```

If empty, check:
1. OrderController creates movement after order
2. WhatsAppInventoryService calls InventoryMovement::record()

---

## ✅ Final Deployment Checklist

Before going to production:

- [ ] Update `.env`:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - Strong `APP_KEY`
  - Production database credentials
  - Midtrans production keys
  - WhatsApp production credentials
- [ ] Setup SSL certificate (HTTPS required)
- [ ] Configure cron job for `schedule:run`
- [ ] Setup backup cron
- [ ] Test all payment gateways with real money
- [ ] Configure email (SMTP)
- [ ] Setup monitoring (Sentry, Bugsnag)
- [ ] Load test with 100+ concurrent users
- [ ] Security audit
- [ ] WhatsApp webhook registered with production URL

---

**Testing Complete! 🎉**

Jika semua checklist di atas ✅, sistem siap production!
