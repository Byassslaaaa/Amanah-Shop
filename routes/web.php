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
use App\Http\Controllers\Admin\Village\VillageController;
use App\Http\Controllers\Admin\Village\ShippingSettingsController;
use App\Http\Controllers\User\Village\VillageController as UserVillageController;
use App\Http\Controllers\User\Contact\ContactController;
use App\Http\Controllers\User\Order\OrderController as UserOrderController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Api\RajaOngkirController;
use App\Http\Controllers\Api\BiteshipController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ShippingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Test Collaborator Komerce API
Route::get('/test-rajaongkir', function() {
    $service = new \App\Services\RajaOngkirService();

    // Test 1: Get Provinces
    $provinces = $service->getProvinces();

    // Test 2: Search Destination
    $searchResult = $service->searchDestination('jakarta');

    return response()->json([
        'status' => 'success',
        'test_1_provinces' => [
            'count' => count($provinces),
            'sample' => array_slice($provinces, 0, 3)
        ],
        'test_2_search' => [
            'keyword' => 'jakarta',
            'count' => count($searchResult),
            'sample' => array_slice($searchResult, 0, 3)
        ]
    ]);
});

// API Routes for RajaOngkir (AJAX)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'getProvinces'])->name('rajaongkir.provinces');
    Route::get('/rajaongkir/cities', [RajaOngkirController::class, 'getCities'])->name('rajaongkir.cities');
    Route::post('/rajaongkir/calculate-cost', [RajaOngkirController::class, 'calculateCost'])->name('rajaongkir.calculate-cost');

    // Biteship API Routes
    Route::post('/biteship/rates', [BiteshipController::class, 'getRates'])->name('biteship.rates');
    Route::get('/biteship/postal-code/search', [BiteshipController::class, 'searchPostalCode'])->name('biteship.postal-code.search');
    Route::get('/biteship/tracking/{trackingId}', [BiteshipController::class, 'getTracking'])->name('biteship.tracking');
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Villages Routes
Route::get('/villages', [UserVillageController::class, 'index'])->name('villages.index');
Route::get('/villages/{slug}', [UserVillageController::class, 'show'])->name('villages.show');

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
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('user.payment.notification');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Product Management
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    
    // Category Management
    Route::resource('categories', AdminCategoryController::class);

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

    // Village Management (SuperAdmin Only)
    Route::resource('villages', VillageController::class);
    Route::post('villages/{village}/toggle-status', [VillageController::class, 'toggleStatus'])->name('villages.toggle-status');

    // Shipping Settings (Village Admin Only)
    Route::get('shipping-settings', [ShippingSettingsController::class, 'index'])->name('shipping-settings.index');
    Route::put('shipping-settings', [ShippingSettingsController::class, 'update'])->name('shipping-settings.update');
    Route::post('shipping-settings/clear-cache', [ShippingSettingsController::class, 'clearCache'])->name('shipping-settings.clear-cache');
});
