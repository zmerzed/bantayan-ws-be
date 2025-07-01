<?php

use Illuminate\Http\Request;
use Kolette\Auth\Enums\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\BrgyController;
use App\Http\Controllers\V1\SequenceController;
use App\Http\Controllers\V1\Admin\AdminController;
use App\Http\Controllers\V1\Admin\ReadingController;
use App\Http\Controllers\V1\Admin\SettingsController;
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

Route::group(['middleware' => 
    [
        //'user.role:' . Role::ADMIN, 
        //'user.role:' . Role::SUPER_ADMIN,
        'auth:admin'
    ]
], function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('admins', AdminController::class);
 
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/generate-account-no', [CustomerController::class, 'generateAccountNo']);
    });

    Route::get('barangays', [BrgyController::class, 'index']);
    Route::apiResource('sequences', SequenceController::class);

    Route::get('settings', [SettingsController::class, 'index']);

    Route::apiResource('readings', ReadingController::class);
    Route::put('readings/{reading}/sync', [ReadingController::class, 'sync']);

    Route::post('readings/generate', [ReadingController::class, 'generate']);
});