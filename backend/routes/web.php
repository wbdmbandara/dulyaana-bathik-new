<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PromoCardsController;
use App\Http\Controllers\SliderController;

Route::get('/', [UserController::class, 'showLogin']);
Route::get('/login', [UserController::class, 'showLogin']);

Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

// Route::resource('/users', UserController::class);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/new', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users/update/{id}', [UserController::class, 'update']);
Route::post('/users/delete/{id}', [UserController::class, 'delete']);

Route::get('/sarees', [ItemsController::class, 'index']);
Route::get('/new-saree', [ItemsController::class, 'create']);
Route::post('/sarees/new', [ItemsController::class, 'store']);
Route::get('/edit-saree/{id}', [ItemsController::class, 'edit']);
Route::post('/sarees/update/{id}', [ItemsController::class, 'update']);
Route::post('/sarees/delete/{id}', [ItemsController::class, 'delete']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories/new', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/categories/update/{id}', [CategoryController::class, 'update']);
Route::post('/categories/delete/{id}', [CategoryController::class, 'delete']);

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/home-slider', [SliderController::class, 'index']);
Route::post('/slides/new', [SliderController::class, 'store']);
Route::get('/slides/{id}', [SliderController::class, 'show']);
Route::post('/slides/update/{id}', [SliderController::class, 'update']);
Route::post('/slides/delete/{id}', [SliderController::class, 'delete']);

Route::get('/promo-cards', [PromoCardsController::class, 'index']);
Route::post('/promo-cards/new', [PromoCardsController::class, 'store']);
Route::get('/promo-cards/{id}', [PromoCardsController::class, 'edit']);
Route::post('/promo-cards/update/{id}', [PromoCardsController::class, 'update']);
Route::post('/promo-cards/delete/{id}', [PromoCardsController::class, 'destroy']);

Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::post('/customers/delete/{id}', [CustomerController::class, 'delete']);

Route::get('/email-settings', [EmailSettingController::class, 'index']);
Route::post('/update-email-settings', [EmailSettingController::class, 'update']);
// Route::get('/send-test-email', [EmailSettingController::class, 'sendTestEmail']); // For testing email settings

Route::get('/footer', [FooterController::class, 'index']);
Route::post('/footer/save', [FooterController::class, 'store']);