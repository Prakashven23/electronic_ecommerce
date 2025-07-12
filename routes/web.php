<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// User authentication
Route::get('register', [UserAuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [UserAuthController::class, 'register']);
Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])->name('logout');

// Shop pages
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('category/{id}', [ShopController::class, 'category'])->name('category');
Route::get('product/{id}', [ShopController::class, 'product'])->name('product');
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout/validate-offer', [\App\Http\Controllers\CheckoutController::class, 'validateOffer'])->name('checkout.validate-offer');
Route::post('checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

Route::get('thank-you', [ShopController::class, 'thankYou'])->name('thankyou');

Route::post('razorpay/order', [PaymentController::class, 'createOrder'])->name('razorpay.order');
Route::post('razorpay/verify', [PaymentController::class, 'verifyPayment'])->name('razorpay.verify');
Route::get('payment/process/{order}', [PaymentController::class, 'process'])->name('payment.process');

Route::middleware(['auth'])->group(function () {
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::resource('categories', CategoryController::class, ['as' => 'admin']);
    Route::resource('products', ProductController::class, ['as' => 'admin']);
    Route::resource('offers', OfferController::class, ['as' => 'admin']);
    Route::resource('orders', OrderController::class, ['as' => 'admin'])->only(['index', 'show', 'update']);
    Route::resource('customers', CustomerController::class, ['as' => 'admin']);
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

Route::get('test-whatsapp', function () {
    $apiKey=env('WHATSAPP_API_KEY');
    $sender=env('WHATSAPP_SENDER');
    $api_url=env('WHATSAPP_API_URL','https://wa.t7solution.com/send-message');
    $message="This is a test WhatsApp message from ecommerce project.";
    $payload = [
        'api_key' => $apiKey,
        'sender' => $sender,
        'number' => '919904966242',
        'message' => $message,
        'footer' => 'Sent By ecommerce',
    ];
    $response = \Http::post($api_url, $payload);
    return [
        'payload' => $payload,
        'status' => $response->status(),
        'body' => $response->json(),
    ];
});
