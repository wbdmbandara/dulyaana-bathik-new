<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('login');
});

// Route::resource('/users', UserController::class);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/new', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users/update/{id}', [UserController::class, 'update']);
Route::post('/users/delete/{id}', [UserController::class, 'delete']);

Route::get('/dashboard', function () {
    return view('dashboard');
});
