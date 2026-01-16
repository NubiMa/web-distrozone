<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect('/admin/dashboard');
        if ($role === 'kasir') return redirect('/kasir/dashboard');
        return redirect()->route('dashboard'); // Customer
    }
    
    $featuredProducts = \App\Models\Product::take(4)->get();
    return view('welcome', compact('featuredProducts'));
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/products', function () {
    // Basic pagination for now, filtering logic to be added
    $products = \App\Models\Product::paginate(12);
    return view('products.index', compact('products'));
});

Route::get('/products/{id}', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    return view('products.show', compact('product'));
});

use App\Http\Controllers\WebAuthController;

// Authentication Routes
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login'])->name('login.post');
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\WebCartController;
use App\Http\Controllers\WebWishlistController;
use App\Http\Controllers\WebAddressController;
use App\Http\Controllers\WebCheckoutController;


// Cart Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [WebCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [WebCartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [WebCartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [WebCartController::class, 'remove'])->name('cart.remove');
});

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [WebCheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [WebCheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{transaction}', [WebCheckoutController::class, 'success'])->name('checkout.success');
});

// Customer Order Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'show'])->name('orders.show');
});

// Profile & Wishlist Routes (Placeholders)
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WebWishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WebWishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{productId}', [WebWishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/move-all', [WebWishlistController::class, 'moveAllToCart'])->name('wishlist.moveAll');

    Route::get('/profile', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $orders = \App\Models\Transaction::where('user_id', $user->id)->latest()->take(3)->get();
        $primaryAddress = \App\Models\Address::where('user_id', $user->id)->where('is_primary', true)->first();
        return view('customer.profile', compact('user', 'orders', 'primaryAddress'));
    })->name('profile.show');

    Route::put('/profile', [WebAuthController::class, 'updateProfile'])->name('profile.update');

    // Customer Settings Routes
    Route::get('/settings', [\App\Http\Controllers\Customer\CustomerSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/password', [\App\Http\Controllers\Customer\CustomerSettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::post('/settings/preferences', [\App\Http\Controllers\Customer\CustomerSettingsController::class, 'updatePreferences'])->name('settings.updatePreferences');

    // Address Routes
    Route::get('/address', [WebAddressController::class, 'index'])->name('address.index');
    Route::post('/address', [WebAddressController::class, 'store'])->name('address.store');
    Route::put('/address/{id}', [WebAddressController::class, 'update'])->name('address.update');
    Route::delete('/address/{id}', [WebAddressController::class, 'destroy'])->name('address.destroy');
    Route::post('/address/{id}/primary', [WebAddressController::class, 'setPrimary'])->name('address.setPrimary');
});

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'admin') return redirect('/admin/dashboard');
        if ($user->role === 'kasir') return redirect('/kasir/dashboard');
        
        // Customer Dashboard - fetch data
        $activeOrder = \App\Models\Transaction::where('user_id', $user->id)
            ->whereIn('order_status', ['pending', 'processing', 'shipped']) // Active only
            ->latest()
            ->first();
            
        $recommendedProducts = \App\Models\Product::inRandomOrder()->take(4)->get();
        
        return view('customer.dashboard', compact('activeOrder', 'recommendedProducts'));
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('admin'); // We should add web-based middleware later if needed, mostly trusting role check in dashboard route for now simple case

    Route::get('/kasir/dashboard', function () {
        return view('kasir.dashboard');
    })->middleware('kasir');
});

