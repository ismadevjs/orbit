<?php

use App\Http\Controllers\MobileController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function () {
    // Public routes
    Route::post('register', [MobileController::class, 'register'])->name('mobile.register');
    Route::post('login', [MobileController::class, 'login'])->name('mobile.login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [MobileController::class, 'user'])->name('mobile.user');
        Route::post('/logout', [MobileController::class, 'logout'])->name('mobile.logout');
    });
});



