<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Kasir\KasirProductController;
use App\Http\Controllers\Kasir\KasirTransactionController;
use App\Http\Controllers\Kasir\KasirReportController;
use App\Http\Controllers\Customer\CustomerProductController;
use App\Http\Controllers\Customer\CustomerOrderController;

/*
|--------------------------------------------------------------------------
| API Routes - DistroZone E-Commerce System
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api
| Authentication: Sanctum (Bearer Token)
| Roles: admin, kasir, customer
|
*/

// ========================================
// PUBLIC ROUTES (No Authentication Required)
// ========================================

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Product Catalog (Guest can browse)
Route::prefix('products')->group(function () {
    Route::get('/', [CustomerProductController::class, 'index']);
    Route::get('/{id}', [CustomerProductController::class, 'show']);
    Route::get('/filters/options', [CustomerProductController::class, 'filterOptions']);
});

// Shipping Information (Guest can check shipping rates)
Route::prefix('shipping')->group(function () {
    Route::get('/destinations', [CustomerOrderController::class, 'shippingDestinations']);
    Route::post('/calculate', [CustomerOrderController::class, 'calculateShipping']);
});

// ========================================
// AUTHENTICATED ROUTES (All Roles)
// ========================================

Route::middleware('auth:sanctum')->group(function () {
    
    // Auth - Profile Management
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

});

// ========================================
// ADMIN ROUTES
// ========================================

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    
    // Employee Management
    Route::apiResource('employees', AdminEmployeeController::class);
    
    // Product Management
    Route::get('products/statistics', [AdminProductController::class, 'statistics']);
    Route::apiResource('products', AdminProductController::class);
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('financial', [AdminReportController::class, 'financial']);
        Route::get('daily-sales', [AdminReportController::class, 'dailySales']);
        Route::get('top-products', [AdminReportController::class, 'topProducts']);
        Route::get('cashier-performance', [AdminReportController::class, 'cashierPerformance']);
    });
    
    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminSettingsController::class, 'index']);
        Route::get('/operational-status', [AdminSettingsController::class, 'operationalStatus']);
        Route::put('/operational-hours', [AdminSettingsController::class, 'updateOperationalHours']);
        Route::get('/{key}', [AdminSettingsController::class, 'show']);
        Route::put('/{key}', [AdminSettingsController::class, 'update']);
    });
});

// ========================================
// KASIR ROUTES
// ========================================

Route::middleware(['auth:sanctum', 'kasir'])->prefix('kasir')->group(function () {
    
    // Product Viewing (Read Only)
    Route::get('products', [KasirProductController::class, 'index']);
    Route::get('products/{id}', [KasirProductController::class, 'show']);
    
    // Transaction Management
    Route::get('transactions', [KasirTransactionController::class, 'index']);
    Route::post('transactions', [KasirTransactionController::class, 'store']); // Create POS transaction
    Route::get('transactions/{id}', [KasirTransactionController::class, 'show']);
    
    // Online Order Verification
    Route::get('orders/pending', [KasirTransactionController::class, 'pendingOrders']);
    Route::post('orders/{id}/verify', [KasirTransactionController::class, 'verifyPayment']);
    
    // Personal Reports
    Route::prefix('reports')->group(function () {
        Route::get('financial', [KasirReportController::class, 'financial']);
        Route::get('daily-sales', [KasirReportController::class, 'dailySales']);
    });
});

// ========================================
// CUSTOMER ROUTES (with Operational Hours Check)
// ========================================

Route::middleware(['auth:sanctum', 'customer', 'operational.hours'])->prefix('customer')->group(function () {
    
    // Product Browsing (same as public but authenticated)
    Route::prefix('products')->group(function () {
        Route::get('/', [CustomerProductController::class, 'index']);
        Route::get('/{id}', [CustomerProductController::class, 'show']);
        Route::get('/filters/options', [CustomerProductController::class, 'filterOptions']);
    });
    
    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [CustomerOrderController::class, 'index']);
        Route::post('/', [CustomerOrderController::class, 'store']); // Place new order
        Route::get('/{id}', [CustomerOrderController::class, 'show']);
        
        // Shipping
        Route::get('/shipping/destinations', [CustomerOrderController::class, 'shippingDestinations']);
        Route::post('/shipping/calculate', [CustomerOrderController::class, 'calculateShipping']);
    });
});

// ========================================
// FALLBACK ROUTE
// ========================================

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route not found',
    ], 404);
});
