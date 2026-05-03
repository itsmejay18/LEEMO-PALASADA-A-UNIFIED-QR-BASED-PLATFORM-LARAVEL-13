<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\TreasurerController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MapController::class, 'landing'])->name('landing');
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/vendors/{vendor}', [VendorController::class, 'publicProfile'])->name('vendors.show');

Route::prefix('qr')->name('qr.')->group(function (): void {
    Route::get('/products/{product}', [QRController::class, 'showProduct'])->name('products.show');
    Route::get('/products/{product}/image', [QRController::class, 'productImage'])->name('products.image');
    Route::get('/products/{product}/download', [QRController::class, 'downloadProduct'])->name('products.download');

    Route::get('/locations/{qrLocationCode}', [QRController::class, 'showLocation'])->name('locations.show');
    Route::get('/locations/{qrLocationCode}/image', [QRController::class, 'locationImage'])->name('locations.image');
    Route::get('/locations/{qrLocationCode}/download', [QRController::class, 'downloadLocation'])->name('locations.download');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->dashboardRoute());
    })->name('dashboard');

    Route::get('/qr/scanner', [QRController::class, 'scanner'])->name('qr.scanner');
    Route::get('/qr/generator', [QRController::class, 'generator'])->name('qr.generator');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('check.role:Customer')->prefix('customer')->name('customer.')->group(function (): void {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [CustomerController::class, 'transactions'])->name('transactions');
        Route::get('/bookmarks', [CustomerController::class, 'bookmarks'])->name('bookmarks');
        Route::post('/bookmarks', [CustomerController::class, 'toggleBookmark'])->name('bookmarks.toggle');
        Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
        Route::post('/cart/items/{product}', [CustomerController::class, 'addToCart'])->name('cart.add');
        Route::patch('/cart/items/{product}', [CustomerController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/items/{product}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [CustomerController::class, 'placeOrder'])->name('checkout.store');
    });

    Route::middleware('check.role:Vendor')->prefix('vendor')->name('vendor.')->group(function (): void {
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [VendorController::class, 'products'])->name('products.index');
        Route::get('/products/create', [VendorController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [VendorController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [VendorController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [VendorController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [VendorController::class, 'destroyProduct'])->name('products.destroy');
        Route::patch('/products/{product}/availability', [VendorController::class, 'toggleAvailability'])->name('products.availability');
    });

    Route::middleware('check.role:Collector')->prefix('collector')->name('collector.')->group(function (): void {
        Route::get('/dashboard', [CollectorController::class, 'dashboard'])->name('dashboard');
        Route::get('/collections', [CollectorController::class, 'index'])->name('collections.index');
        Route::get('/collections/create', [CollectorController::class, 'create'])->name('collections.create');
        Route::post('/collections', [CollectorController::class, 'store'])->name('collections.store');
        Route::get('/collections/{collection}/edit', [CollectorController::class, 'edit'])->name('collections.edit');
        Route::put('/collections/{collection}', [CollectorController::class, 'update'])->name('collections.update');
    });

    Route::middleware('check.role:Treasurer')->prefix('treasurer')->name('treasurer.')->group(function (): void {
        Route::get('/dashboard', [TreasurerController::class, 'dashboard'])->name('dashboard');
        Route::get('/collections', [TreasurerController::class, 'index'])->name('collections.index');
        Route::get('/collections/{collection}/verify', [TreasurerController::class, 'verify'])->name('collections.verify');
        Route::post('/records', [TreasurerController::class, 'store'])->name('records.store');
        Route::get('/receipts/{treasurerRecord}', [TreasurerController::class, 'receipt'])->name('receipts.show');
    });

    Route::middleware('check.role:Manager')->prefix('manager')->name('manager.')->group(function (): void {
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
        Route::get('/vendors', [ManagerController::class, 'vendors'])->name('vendors.index');
        Route::get('/vendors/create', [ManagerController::class, 'createVendor'])->name('vendors.create');
        Route::post('/vendors', [ManagerController::class, 'storeVendor'])->name('vendors.store');
        Route::get('/vendors/{vendor}/edit', [ManagerController::class, 'editVendor'])->name('vendors.edit');
        Route::put('/vendors/{vendor}', [ManagerController::class, 'updateVendor'])->name('vendors.update');
        Route::delete('/vendors/{vendor}', [ManagerController::class, 'destroyVendor'])->name('vendors.destroy');
        Route::get('/reports', [ManagerController::class, 'reports'])->name('reports');
    });

    Route::middleware('check.role:Admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::patch('/users/{user}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';
