<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin')
            return redirect('/admin/dashboard');
        if ($role === 'kasir')
            return redirect('/kasir/dashboard');
        return redirect()->route('dashboard'); // Customer
    }

    $featuredProducts = \App\Models\Product::take(4)->get();
    return view('welcome', compact('featuredProducts'));
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/debug-users', function () {
    $users = \App\Models\User::orderBy('role')->orderBy('id')->get();
    return view('debug-users', compact('users'));
});

Route::get('/products', function () {
    // Basic pagination for now, filtering logic to be added
    $products = \App\Models\Product::paginate(12);
    return view('products.index', compact('products'));
});

Route::get('/products/{id}', function ($id) {
    $product = \App\Models\Product::with(['variants' => function($query) {
        $query->where('stock', '>', 0)->where('is_active', true);
    }])->findOrFail($id);
    
    // Get available sizes and colors from variants
    $availableSizes = $product->variants->pluck('size')->unique()->sort()->values();
    $availableColors = $product->variants->pluck('color')->unique()->values();
    
    return view('products.show', compact('product', 'availableSizes', 'availableColors'));
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
    Route::get('/checkout/receipt/{transaction}/download', [WebCheckoutController::class, 'downloadReceipt'])->name('checkout.receipt.download');
});

// Kasir JSON API routes (using web sessions)
Route::middleware(['auth', 'kasir'])->prefix('kasir/api')->group(function () {
    Route::get('/orders/pending', [App\Http\Controllers\Kasir\KasirTransactionController::class, 'pendingOrders']);
    Route::post('/orders/{id}/verify', [App\Http\Controllers\Kasir\KasirTransactionController::class, 'verifyPayment']);
    Route::get('/orders/{id}/verify', [App\Http\Controllers\Kasir\KasirTransactionController::class, 'verifyPage'])->name('orders.verify');
    Route::get('/transactions', [App\Http\Controllers\Kasir\KasirTransactionController::class, 'index']);
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

// ===================================
// ADMIN ROUTES
// ===================================
Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Staff Management
    Route::get('/staff', [App\Http\Controllers\Admin\AdminEmployeeController::class, 'index'])->name('admin.staff.index');
    Route::get('/staff/create', [App\Http\Controllers\Admin\AdminEmployeeController::class, 'create'])->name('admin.staff.create');
    Route::post('/staff', [App\Http\Controllers\Admin\AdminEmployeeController::class, 'store'])->name('admin.staff.store');
    Route::get('/staff/{id}/edit', [App\Http\Controllers\Admin\AdminEmployeeController::class, 'edit'])->name('admin.staff.edit');
    Route::put('/staff/{id}', [App\Http\Controllers\Admin\AdminEmployeeController::class, 'update'])->name('admin.staff.update');
    Route::delete('/staff/{id}', [App\Http\Controllers\Admin\AdminEmployeeController::class, 'destroy'])->name('admin.staff.destroy');
    
    // Product Management
    Route::get('/products', [App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [App\Http\Controllers\Admin\AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [App\Http\Controllers\Admin\AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [App\Http\Controllers\Admin\AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('admin.reports');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('admin.settings.update');
});

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'admin')
            return redirect('/admin/dashboard');
        if ($user->role === 'kasir')
            return redirect('/kasir/dashboard');

        // Customer Dashboard - fetch data
        $activeOrder = \App\Models\Transaction::where('user_id', $user->id)
            ->whereIn('order_status', ['pending', 'processing', 'shipped']) // Active only
            ->latest()
            ->first();

        $recommendedProducts = \App\Models\Product::inRandomOrder()->take(4)->get();

        return view('customer.dashboard', compact('activeOrder', 'recommendedProducts'));
    })->name('dashboard');



    Route::get('/kasir/dashboard', function () {
        // Stats: Recent Sales (Verified transactions today)
        $today = now()->startOfDay();
        $endOfToday = now()->endOfDay();
        
        $todaysSales = \App\Models\Transaction::verified()
            ->whereBetween('verified_at', [$today, $endOfToday])
            ->sum('total');
            
        $todaysSalesCount = \App\Models\Transaction::verified()
            ->whereBetween('verified_at', [$today, $endOfToday])
            ->count();

        // Stats: Comparison with yesterday
        $yesterday = now()->subDay()->startOfDay();
        $endOfYesterday = now()->subDay()->endOfDay();
        $yesterdaysSales = \App\Models\Transaction::verified()
            ->whereBetween('verified_at', [$yesterday, $endOfYesterday])
            ->sum('total');

        // Stats: Pending Verifications (Online pending)
        $pendingVerifications = \App\Models\Transaction::online()->pending()->count();

        // Stats: Items Sold Today
        $itemsSold = \App\Models\TransactionDetail::whereHas('transaction', function($q) use ($today, $endOfToday) {
            $q->verified()->whereBetween('verified_at', [$today, $endOfToday]);
        })->sum('quantity');

        // Recent Transactions
        $recentTransactions = \App\Models\Transaction::with(['user', 'details.productVariant.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'todaysSales', 
            'todaysSalesCount',
            'yesterdaysSales', 
            'pendingVerifications', 
            'itemsSold', 
            'recentTransactions'
        ));
    })->middleware('kasir');

    Route::get('/kasir/orders', [\App\Http\Controllers\Kasir\KasirTransactionController::class, 'ordersPage'])
        ->middleware('kasir')
        ->name('kasir.orders.index');

    Route::get('/kasir/inventory', [\App\Http\Controllers\Kasir\KasirProductController::class, 'index'])->middleware('kasir')->name('kasir.inventory');
    Route::get('/kasir/reports', [\App\Http\Controllers\Kasir\KasirReportController::class, 'index'])->middleware('kasir');

    Route::get('/kasir/profile', function () {
        return view('kasir.profile');
    })->middleware('kasir');

    Route::put('/kasir/profile/update', [\App\Http\Controllers\Kasir\KasirProfileController::class, 'updateProfile'])->middleware('kasir');
    Route::put('/kasir/profile/password', [\App\Http\Controllers\Kasir\KasirProfileController::class, 'updatePassword'])->middleware('kasir');
});


// Chatbot Route (Public)
Route::post('/chatbot/ask', [\App\Http\Controllers\ChatbotController::class, 'handle'])->name('chatbot.ask');
