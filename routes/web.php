<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/tentang', [PageController::class, 'about'])->name('about');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [PageController::class, 'contactSubmit'])->name('contact.submit');

Route::get('/syarat-ketentuan', [PageController::class, 'terms'])->name('terms');
Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('privacy');
Route::get('/kebijakan-retur', [PageController::class, 'returns'])->name('returns');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

// Cart Group (Accessible to Guest & Authenticated Users)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('apply_voucher');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/add', [WishlistController::class, 'add'])->name('add');
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::post('/move-to-cart', [WishlistController::class, 'moveToCart'])->name('moveToCart');
        Route::delete('/remove', [WishlistController::class, 'remove'])->name('remove');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::get('/cities', [CheckoutController::class, 'cities'])->name('cities');
        Route::post('/shipping-cost', [CheckoutController::class, 'shippingCost'])->name('shipping-cost');
        Route::get('/success/{order_code}', [CheckoutController::class, 'success'])->name('success');
    });

    // Payment upload
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/upload/{orderId}', [PaymentController::class, 'upload'])->name('upload');
        Route::post('/store/{orderId}', [PaymentController::class, 'store'])->name('store');
        Route::get('/success/{orderId}', [PaymentController::class, 'success'])->name('success');
    });

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Profile Addresses Book CRUD
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::put('/profile/addresses/{id}', [ProfileController::class, 'updateAddress'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');
    Route::post('/profile/addresses/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.default');

    // Product reviews submission
    Route::post('/products/{id}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store');

    // User Orders History & Tracking Actions
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order_code}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order_code}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel');
        Route::post('/{order_code}/confirm', [OrderController::class, 'confirmReceived'])->name('confirm_received');
    });
});

// Midtrans Webhook Callback
Route::post('/midtrans/callback', [App\Http\Controllers\CheckoutController::class, 'midtransCallback'])->name('midtrans.callback');

// Admin Panel (Role Admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.destroyImage');
    Route::post('products/{product}/clear-dummy', [AdminProductController::class, 'clearDummyImages'])->name('products.clearDummy');
    Route::resource('categories', AdminCategoryController::class);
    
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
    Route::put('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('orders', AdminOrderController::class);
    
    Route::resource('customers', AdminCustomerController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Testimonials approval management
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials/{id}/approve', [AdminTestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::delete('/testimonials/{id}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Admin Shipping Settings
    Route::get('/settings/shipping', [App\Http\Controllers\Admin\ShippingSettingController::class, 'index'])->name('settings.shipping');
    Route::post('/settings/shipping', [App\Http\Controllers\Admin\ShippingSettingController::class, 'update'])->name('settings.shipping.update');
});

require __DIR__.'/auth.php';
