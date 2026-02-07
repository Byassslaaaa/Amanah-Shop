# 🚀 Deployment Checklist - Amanah Shop

**Pre-Deployment Date:** 2026-02-07
**Version:** Post-Audit (16 Security Fixes Applied)

---

## ⚠️ CRITICAL - Must Do Before Production

### 1. Database Migration
```bash
php artisan migrate
```
**Purpose:** Apply performance indexes for orders, carts, order_items, etc.
**Verification:** Check `php artisan migrate:status` - all migrations should show "Y"

---

### 2. Environment Configuration

#### `.env` File - Production Settings
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Security Keys (CHANGE THESE!)
APP_KEY=base64:... # Run: php artisan key:generate

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amanah_shop
DB_USERNAME=your_db_user
DB_PASSWORD=strong_password_here

# Midtrans (Payment Gateway)
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Biteship (Shipping)
BITESHIP_API_KEY=your_biteship_key_here
BITESHIP_ENVIRONMENT=production

# Email (for password reset)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

### 3. Cache & Optimize
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

### 4. File Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

### 5. Security Headers (Nginx/Apache)

#### Nginx Configuration
Add to your site config:
```nginx
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
```

#### Apache Configuration
Add to `.htaccess`:
```apache
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
```

---

### 6. SSL/TLS Certificate
- [ ] Install SSL certificate (Let's Encrypt recommended)
- [ ] Force HTTPS in `.env`: `APP_URL=https://...`
- [ ] Test SSL: https://www.ssllabs.com/ssltest/

---

### 7. Webhook Configuration

#### Midtrans Notification URL
Set in Midtrans Dashboard:
```
https://your-domain.com/payment/notification
```

**Test:** Send test notification from Midtrans dashboard

---

### 8. Database Backup
```bash
# Setup automated daily backups
mysqldump -u username -p amanah_shop > backup_$(date +%Y%m%d).sql
```

**Recommendation:** Use cron job for daily backups

---

## 🔍 Testing Checklist

### Functional Testing
- [ ] **User Registration** - Test with valid/invalid data
- [ ] **Login/Logout** - Test all user roles (user, admin, superadmin)
- [ ] **Product Browsing** - Test filtering, search, pagination
- [ ] **Cart Operations** - Add, update, remove items
- [ ] **Checkout Flow** - Test full checkout with shipping calculation
- [ ] **Payment** - Test Midtrans payment (use sandbox first)
- [ ] **Order Tracking** - Test order status updates
- [ ] **Credit/Installment** - Test down payment, installment payments
- [ ] **Admin Dashboard** - Test all admin operations
- [ ] **SuperAdmin** - Test user/admin management (403 for regular admin)

### Security Testing
- [ ] **Privilege Escalation** - Attempt to modify `role` field via POST
- [ ] **CSRF** - Test all forms without CSRF token (should fail)
- [ ] **SQL Injection** - Test inputs with `' OR '1'='1`
- [ ] **XSS** - Test inputs with `<script>alert(1)</script>`
- [ ] **Authorization** - Access other users' orders via URL manipulation
- [ ] **Webhook Signature** - Send webhook without signature (should reject)
- [ ] **Rate Limiting** - Exceed 30 req/min on Biteship endpoint

### Performance Testing
- [ ] **Database Indexes** - Verify queries use indexes (check `EXPLAIN`)
- [ ] **Page Load Time** - All pages < 2 seconds
- [ ] **API Response Time** - Biteship API < 3 seconds
- [ ] **Concurrent Orders** - 10+ simultaneous checkouts (no overselling)

---

## 📊 Monitoring Setup

### Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

**Monitor for:**
- `Log::alert` - Role changes
- `Log::warning` - Invalid webhook signatures
- `Log::error` - Failed operations

### Database Monitoring
```sql
-- Check for suspicious role changes
SELECT * FROM users WHERE updated_at > NOW() - INTERVAL 1 DAY AND role IN ('admin', 'superadmin');

-- Monitor order cancellations
SELECT COUNT(*) FROM orders WHERE status = 'cancelled' AND DATE(created_at) = CURDATE();

-- Check inventory movements
SELECT type, COUNT(*), SUM(quantity) FROM inventory_movements
WHERE DATE(created_at) = CURDATE()
GROUP BY type;
```

---

## 🔄 Post-Deployment Verification

### Immediate Checks (within 1 hour)
1. [ ] SSL certificate valid and HTTPS enforced
2. [ ] All pages load without errors
3. [ ] User registration works
4. [ ] Admin login works
5. [ ] SuperAdmin routes protected (403 for regular admin)

### Within 24 Hours
1. [ ] Test real payment with small amount (Midtrans)
2. [ ] Test shipping rate calculation (Biteship)
3. [ ] Verify webhook notifications working
4. [ ] Check error logs for unexpected issues
5. [ ] Test order cancellation (verify stock restored)

### Within 1 Week
1. [ ] Monitor payment success rate
2. [ ] Check for failed webhook deliveries
3. [ ] Review audit logs for suspicious activity
4. [ ] Verify database indexes improving query performance
5. [ ] Test load under expected traffic

---

## 🆘 Rollback Plan

If critical issues occur:

```bash
# 1. Revert to previous version
git reset --hard <previous-commit-hash>

# 2. Revert database migrations
php artisan migrate:rollback --step=1

# 3. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Restore database backup
mysql -u username -p amanah_shop < backup_YYYYMMDD.sql
```

---

## 📞 Emergency Contacts

- **Developer:** [Your Name]
- **System Admin:** [Admin Contact]
- **Database Admin:** [DBA Contact]
- **Midtrans Support:** https://support.midtrans.com
- **Biteship Support:** https://biteship.com/support

---

## ✅ Final Sign-Off

**Checklist Completed By:** _______________
**Date:** _______________
**Deployment Time:** _______________
**Deployed By:** _______________

---

**Generated:** 2026-02-07
**Audit Fixes Applied:** 16 (5 CRITICAL + 5 HIGH + 6 MEDIUM)
