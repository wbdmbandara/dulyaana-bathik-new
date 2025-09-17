<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;

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

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories/new', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/categories/update/{id}', [CategoryController::class, 'update']);
Route::post('/categories/delete/{id}', [CategoryController::class, 'delete']);

Route::get('/dashboard', [DashboardController::class, 'index']);
