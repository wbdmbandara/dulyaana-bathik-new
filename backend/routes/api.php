<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PromoCardsController;
use App\Http\Controllers\UserController; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SliderController;

Route::middleware('api')->group(function () {
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

    Route::post('/registerCustomer', [CustomerController::class, 'register']);
    Route::post('/login', [CustomerController::class, 'login']);
});