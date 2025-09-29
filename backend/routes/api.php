<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SliderController;

Route::middleware('api')->group(function () {
    Route::get('/getSlider', [SliderController::class, 'displaySlides']);
});