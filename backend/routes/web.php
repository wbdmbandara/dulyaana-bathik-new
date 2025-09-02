<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

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

Route::get('/dashboard', [DashboardController::class, 'index']);
