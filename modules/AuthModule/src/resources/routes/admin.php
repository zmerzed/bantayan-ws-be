<?php

use Illuminate\Support\Facades\Route;
use Kolette\Auth\Http\Controllers\V1\Admin\Auth\AuthController;
use Kolette\Auth\Http\Controllers\V1\Admin\User\UserController;
use Kolette\Auth\Http\Controllers\V1\Admin\User\BusinessController;

// Route::group(['prefix' => 'v1/admin'], function () {
// });

Route::name('admin.')->group(function () {
    Route::group(['prefix' => 'admin'], function () {

        // Route::apiResource('orders', \App\Http\Controllers\V1\Admin\OrderController::class);

        // Route::post('auth/login', [AuthController::class, 'login'])->name('login');
        // Route::post('auth/bypass-login', [AuthController::class, 'bypassLogin'])->name('login.bypass');
        // Route::apiResource('users', UserController::class);
        // Route::post('users/{user}/update', [UserController::class, 'update']);
        // Route::apiResource('business', BusinessController::class);
        // Route::post('business/{user}/update', [BusinessController::class, 'update']);
    });
});
