<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\Product\ProductController;
use App\Http\Controllers\User\Cart\CartController;
use App\Http\Controllers\User\Order\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Product\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Product\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\SuperAdmin\User\UserController as AdminUserController;
use App\Http\Controllers\User\Contact\ContactController;
use App\Http\Controllers\User\Order\OrderController as UserOrderController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Api\BiteshipController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ShippingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// API Routes for Biteship (Shipping)
// ⚠️ Rate limited to prevent API abuse and reduce costs
Route::prefix('api')->name('api.')->middleware('throttle:60,1')->group(function () {
    // Shipping rates - more restrictive (30 requests/minute) as it calls external API
    Route::post('/biteship/rates', [BiteshipController::class, 'getRates'])
        ->middleware('throttle:30,1')
        ->name('biteship.rates');

    Route::get('/biteship/postal-code/search', [BiteshipController::class, 'searchPostalCode'])
        ->middleware('throttle:30,1')
        ->name('biteship.postal-code.search');

    Route::get('/biteship/tracking/{trackingId}', [BiteshipController::class, 'getTracking'])
        ->middleware('throttle:30,1')
        ->name('biteship.tracking');
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Products Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/type/{type}', [ProductController::class, 'byType'])->name('products.type');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::post('/products/{product}/whatsapp-inquiry', [ProductController::class, 'whatsappInquiry'])->name('products.whatsapp-inquiry');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// User Routes (Authentication Required)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('user.cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('user.cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('user.cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('user.cart.remove');
    Route::post('/cart/{cart}/toggle-selection', [CartController::class, 'toggleSelection'])->name('user.cart.toggle-selection');
    Route::post('/cart/select-all', [CartController::class, 'selectAll'])->name('user.cart.select-all');

    // Order Routes
    Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('user.orders.show');
    Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('user.orders.cancel');
    Route::get('/orders/{order}/tracking', [ShippingController::class, 'show'])->name('user.orders.tracking');
    Route::get('/checkout', [UserOrderController::class, 'checkout'])->name('user.orders.checkout');
    Route::post('/checkout', [UserOrderController::class, 'store'])->name('user.orders.store');

    // Favorite Routes
    Route::get('/favorites', [\App\Http\Controllers\User\FavoriteController::class, 'index'])->name('user.favorites.index');
    Route::post('/favorites/{product}/toggle', [\App\Http\Controllers\User\FavoriteController::class, 'toggle'])->name('user.favorites.toggle');
    Route::delete('/favorites/{favorite}', [\App\Http\Controllers\User\FavoriteController::class, 'destroy'])->name('user.favorites.destroy');

    // Payment Routes (Midtrans)
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('user.payment.show');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('user.payment.finish');
    Route::get('/payment/{order}/status', [PaymentController::class, 'checkStatus'])->name('user.payment.status');

    // Credit/Installment Routes
    Route::prefix('credit')->name('user.credit.')->group(function () {
        Route::get('/', [\App\Http\Controllers\User\Credit\CustomerInstallmentController::class, 'index'])->name('index');
        Route::get('/orders/{order}', [\App\Http\Controllers\User\Credit\CustomerInstallmentController::class, 'show'])->name('show');
        Route::get('/payment-history', [\App\Http\Controllers\User\Credit\CustomerInstallmentController::class, 'paymentHistory'])->name('payment-history');
        Route::get('/upcoming', [\App\Http\Controllers\User\Credit\CustomerInstallmentController::class, 'upcomingPayments'])->name('upcoming');
        Route::get('/overdue', [\App\Http\Controllers\User\Credit\CustomerInstallmentController::class, 'overduePayments'])->name('overdue');
        Route::get('/installments/{installment}/payment-proof', [\App\Http\Controllers\User\Credit\CustomerInstallmentController::class, 'paymentProofForm'])->name('payment-proof-form');
        Route::post('/installments/{installment}/payment-proof', [\App\Http\Controllers\User\Credit\CustomerInstallmentController::class, 'uploadPaymentProof'])->name('upload-payment-proof');
    });
});

// Midtrans Webhook (No Auth Required)
// ⚠️ Higher rate limit for webhook (100/min) to accommodate Midtrans retries
Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->middleware('throttle:100,1')
    ->name('user.payment.notification');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Product Management
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    
    // Category Management
    Route::resource('categories', AdminCategoryController::class);

    // Banner Management
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);

    // Order Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::put('orders/{order}/shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.update-shipping');

    // Shipping Management (Input Resi)
    Route::get('orders/{order}/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
    Route::post('orders/{order}/shipping', [ShippingController::class, 'store'])->name('shipping.store');
    Route::post('orders/{order}/shipping/create-shipment', [ShippingController::class, 'createShipment'])->name('shipping.create-shipment');
    Route::post('orders/{order}/shipping/update-tracking', [ShippingController::class, 'updateTracking'])->name('shipping.update-tracking');

    // Credit/Installment Management
    Route::prefix('credit')->name('credit.')->group(function () {
        // Installment Plans
        Route::resource('plans', \App\Http\Controllers\Admin\Credit\InstallmentPlanController::class);
        Route::post('plans/{plan}/toggle-active', [\App\Http\Controllers\Admin\Credit\InstallmentPlanController::class, 'toggleActive'])->name('plans.toggle-active');

        // Installment Payments Management
        Route::get('installments', [\App\Http\Controllers\Admin\Credit\InstallmentController::class, 'index'])->name('installments.index');
        Route::get('installments/overdue', [\App\Http\Controllers\Admin\Credit\InstallmentController::class, 'overdue'])->name('installments.overdue');
        Route::get('installments/statistics', [\App\Http\Controllers\Admin\Credit\InstallmentController::class, 'statistics'])->name('installments.statistics');
        Route::get('installments/orders/{order}', [\App\Http\Controllers\Admin\Credit\InstallmentController::class, 'show'])->name('installments.show');
        Route::get('installments/{installment}/verify', [\App\Http\Controllers\Admin\Credit\InstallmentController::class, 'verifyForm'])->name('installments.verify-form');
        Route::post('installments/{installment}/verify', [\App\Http\Controllers\Admin\Credit\InstallmentController::class, 'verify'])->name('installments.verify');
    });

    // Finance Management (Keuangan)
    Route::prefix('finance')->name('finance.')->group(function () {
        // Financial Transactions
        Route::get('transactions', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/create', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'show'])->name('transactions.show');
        Route::get('transactions/{transaction}/edit', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('transactions/{transaction}', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'update'])->name('transactions.update');
        Route::delete('transactions/{transaction}', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::get('report', [\App\Http\Controllers\Admin\Finance\FinancialTransactionController::class, 'report'])->name('report');

        // Transaction Categories
        Route::resource('categories', \App\Http\Controllers\Admin\Finance\TransactionCategoryController::class);
    });

    // Inventory Management (Expanded)
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Inventory Movements
        Route::get('movements', [\App\Http\Controllers\Admin\Inventory\InventoryMovementController::class, 'index'])->name('movements.index');
        Route::get('movements/stock-in', [\App\Http\Controllers\Admin\Inventory\InventoryMovementController::class, 'stockInForm'])->name('movements.stock-in-form');
        Route::post('movements/stock-in', [\App\Http\Controllers\Admin\Inventory\InventoryMovementController::class, 'storeStockIn'])->name('movements.stock-in');
        Route::get('movements/stock-out', [\App\Http\Controllers\Admin\Inventory\InventoryMovementController::class, 'stockOutForm'])->name('movements.stock-out-form');
        Route::post('movements/stock-out', [\App\Http\Controllers\Admin\Inventory\InventoryMovementController::class, 'storeStockOut'])->name('movements.stock-out');
        Route::get('movements/report', [\App\Http\Controllers\Admin\Inventory\InventoryMovementController::class, 'report'])->name('movements.report');
        Route::get('movements/{movement}', [\App\Http\Controllers\Admin\Inventory\InventoryMovementController::class, 'show'])->name('movements.show');

        // Suppliers
        Route::resource('suppliers', \App\Http\Controllers\Admin\Inventory\SupplierController::class);
        Route::post('suppliers/{supplier}/toggle-status', [\App\Http\Controllers\Admin\Inventory\SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    });

    // Manual Credits Management (Kredit & Hutang)
    Route::prefix('credits')->name('credits.')->group(function () {
        // Manual Credits
        Route::get('manual', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'index'])->name('manual.index');
        Route::get('manual/create', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'create'])->name('manual.create');
        Route::post('manual', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'store'])->name('manual.store');
        Route::get('manual/{credit}', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'show'])->name('manual.show');
        Route::get('manual/{credit}/edit', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'edit'])->name('manual.edit');
        Route::put('manual/{credit}', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'update'])->name('manual.update');
        Route::delete('manual/{credit}', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'destroy'])->name('manual.destroy');
        Route::get('manual/{credit}/payment', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'recordPaymentForm'])->name('manual.payment-form');
        Route::post('manual/{credit}/payment', [\App\Http\Controllers\Admin\Credit\ManualCreditController::class, 'storePayment'])->name('manual.store-payment');

        // Manual Credit Payments
        Route::get('payments', [\App\Http\Controllers\Admin\Credit\ManualCreditPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/overdue', [\App\Http\Controllers\Admin\Credit\ManualCreditPaymentController::class, 'overdueList'])->name('payments.overdue');
        Route::get('payments/report', [\App\Http\Controllers\Admin\Credit\ManualCreditPaymentController::class, 'report'])->name('payments.report');
        Route::get('payments/{payment}', [\App\Http\Controllers\Admin\Credit\ManualCreditPaymentController::class, 'show'])->name('payments.show');
        Route::post('payments/{payment}/verify', [\App\Http\Controllers\Admin\Credit\ManualCreditPaymentController::class, 'verifyPayment'])->name('payments.verify');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('inventory', [\App\Http\Controllers\Admin\Reports\ReportController::class, 'inventory'])->name('inventory');
        Route::get('payments', [\App\Http\Controllers\Admin\Reports\ReportController::class, 'payments'])->name('payments');
        Route::get('credit-orders', [\App\Http\Controllers\Admin\Reports\ReportController::class, 'creditOrders'])->name('credit-orders');
        Route::get('inventory/export', [\App\Http\Controllers\Admin\Reports\ReportController::class, 'exportInventory'])->name('inventory.export');
        Route::get('payments/export', [\App\Http\Controllers\Admin\Reports\ReportController::class, 'exportPayments'])->name('payments.export');
    });

    // Admin Management (SuperAdmin Only)
    Route::resource('admins', AdminManagementController::class);

    // User Management (Super Admin Only) - Hanya untuk user biasa, bukan admin
    Route::resource('users', AdminUserController::class);

    // About Page Management
    Route::prefix('about')->name('about.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AboutContentController::class, 'index'])->name('index');
        Route::get('/edit', [\App\Http\Controllers\Admin\AboutContentController::class, 'edit'])->name('edit');
        Route::put('/', [\App\Http\Controllers\Admin\AboutContentController::class, 'update'])->name('update');
        Route::get('/delete-image', [\App\Http\Controllers\Admin\AboutContentController::class, 'deleteImage'])->name('delete-image');
    });

    // Settings Management
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});
