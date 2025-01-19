<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\WeatherDataController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomepageController::class, 'index'])->name('homepage');

Route::prefix('locations')->group(function () {
    Route::prefix('ajax')->group(function () {
        Route::post('/store', [LocationController::class, 'store']);
    });
});

Route::prefix('weather-data')->group(function () {
    Route::prefix('ajax')->group(function () {
        Route::post('/details/{id}', [WeatherDataController::class, 'weatherDetails']);
    });
});