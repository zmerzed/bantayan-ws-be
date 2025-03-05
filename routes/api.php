<?php

use Illuminate\Http\Request;
use Kolette\Auth\Enums\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\BrgyController;
use App\Http\Controllers\V1\Customer\ReadingController;
use App\Http\Controllers\V1\Customer\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['user.role:' . Role::ADMIN, 'auth:admin']], function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('readings', ReadingController::class);

    Route::group(['prefix' => 'customer'], function () {
        Route::get('/generate-account-no', [CustomerController::class, 'generateAccountNo']);
    });

    Route::get('barangays', [BrgyController::class, 'index']);
});