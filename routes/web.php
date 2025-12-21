<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;






Route::get('/',[FrontendController::class,'index'])->name('home');
Route::get('/shop',[FrontendController::class,'shop'])->name('shop');
Route::get('/search',[FrontendController::class,'search'])->name('search');
Route::get('/shop/category/{slug}',[FrontendController::class,'shopByCategory'])->name('shop.category');
Route::get('/prd/{id}',[FrontendController::class,'productDetails'])->name('prd');
Route::get('/cart',[FrontendController::class,'cart'])->name('cart');
Route::get('/checkout',[FrontendController::class,'checkout'])->name('checkout');
Route::get('/about-us',[FrontendController::class,'about'])->name('about');
Route::get('/contact-us',[FrontendController::class,'contact'])->name('contact');
Route::post('/contact-us',[FrontendController::class,'contactSubmit'])->name('contact.submit');

 Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware(['auth', 'verified']);

// Cart routes
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/items', [CartController::class, 'getCartItems'])->name('cart.items');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::get('/cart/product-quantity', [CartController::class, 'getProductCartQuantity'])->name('cart.product.quantity');

// Order routes
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order/success/{order}', [OrderController::class, 'orderSuccess'])->name('order.success');

// Payment routes
Route::get('/payment/process/{order}', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
Route::get('/payment/failed/{order}', [PaymentController::class, 'paymentFailed'])->name('payment.failed');

// User dashboard routes 
Route::group(['prefix' => 'user', 'middleware' => ['auth']], function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    
    // User Orders
    Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('user.orders.show');
    Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('user.orders.cancel');
    Route::post('/orders/{order}/reorder', [UserOrderController::class, 'reorder'])->name('user.orders.reorder');
    
    // User Profile
    Route::get('/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/change-password', [UserProfileController::class, 'showChangePasswordForm'])->name('user.change-password');
    Route::post('/change-password', [UserProfileController::class, 'changePassword'])->name('user.change-password.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('user.profile.destroy');
});
// Admin dashboard routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Admin Product Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/make_admin/{user}', [AdminController::class, 'make_admin'])->name('admin.make_admin');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products/export', [AdminController::class, 'exportProducts'])->name('admin.products.export');
    Route::post('/products/import', [AdminController::class, 'importProducts'])->name('admin.products.import');
    Route::get('/products/template', [AdminController::class, 'downloadProductTemplate'])->name('admin.products.template');
    Route::get('/add-product', [AdminController::class, 'add_product'])->name('admin.add-product');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('admin.store_product');
    Route::post('/products/bulk-delete', [AdminController::class, 'bulkDeleteProducts'])->name('admin.products.bulk_delete');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.destroy');
    Route::post('/products/{id}/duplicate', [AdminController::class, 'duplicateProduct'])->name('admin.products.duplicate');
    
    // Admin Category Management
    Route::resource('categories', AdminCategoryController::class, [
        'names' => [
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'show' => 'admin.categories.show',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'destroy' => 'admin.categories.destroy'
        ]
    ]);
    
    // Admin Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{order}', [AdminController::class, 'orderDetails'])->name('admin.orders.show');
    Route::post('/orders/export', [AdminController::class, 'exportOrders'])->name('admin.orders.export');
    
    // Admin Users Export
    Route::post('/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
    
    // Admin Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('admin.change-password.update');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
