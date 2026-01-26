<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PromoCardsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ThemeController;

Route::middleware('api')->group(function () {
    Route::get('/theme-settings', [ThemeController::class, 'getThemeSettings']);

    Route::get('/getSlider', [SliderController::class, 'displaySlides']);
    Route::get('/getPromoCards', [PromoCardsController::class, 'getPromoCards']);
    Route::get('/getRecentProducts', [ItemsController::class, 'getRecentProducts']);
    Route::get('/getProductDetails/{url}', [ItemsController::class, 'getProductDetails']);
    Route::get('/getParentCategories', [CategoryController::class, 'getParentCategories']);
    Route::get('/getMinAndMaxPrices', [ItemsController::class, 'getMinAndMaxPrices']);
    Route::get('/getFabricList', [ItemsController::class, 'getFabricList']);
    Route::get('/getFooter', [FooterController::class, 'getFooterData']);
    Route::get('/getItems', [ItemsController::class, 'filterItems']);
    Route::get('/getItems/{}', [ItemsController::class, 'filterItems']);

    // Authentication routes (no token required)
    Route::post('/registerCustomer', [CustomerController::class, 'register']);
    Route::post('/login', [CustomerController::class, 'login']);
    Route::post('/customers/{id}', [CustomerController::class, 'update']);
    // Route::post('/admin/login', [UserController::class, 'login']); // Admin login route

    // cart operations
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
    Route::post('/cart/update', [CartController::class, 'updateCart']);
    Route::post('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/cart', [CartController::class, 'viewCart']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);

    // getting customer details
    // Route::get('/customers/{id}', [CustomerController::class, 'getCustomerDetails']);

    // Customer Profile Management
    Route::post('/customerNewAddress/{id}', [CustomerController::class, 'addAddress']);

});

// Protected routes that require authentication token
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [CustomerController::class, 'logout']);
    Route::post('/validate-token', [CustomerController::class, 'validateToken']);
    Route::get('/user/profile', [CustomerController::class, 'profile']);
});