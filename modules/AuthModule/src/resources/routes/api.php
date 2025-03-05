<?php

use Kolette\Auth\Enums\Role;
use Illuminate\Support\Facades\Route;
use Kolette\Auth\Http\Controllers\V1\AuthController;
use Kolette\Auth\Http\Controllers\V1\UserController;
use Kolette\Auth\Http\Controllers\V1\CheckController;
use Kolette\Auth\Http\Controllers\V1\GuestController;
use Kolette\Auth\Http\Controllers\V1\ProfileController;
use Kolette\Auth\Http\Controllers\V1\RegisterController;
use Kolette\Auth\Http\Controllers\V1\OnBoardingController;
use Kolette\Auth\Http\Controllers\V1\UserAvatarController;
use Kolette\Auth\Http\Controllers\V1\VerificationController;
use Kolette\Auth\Http\Controllers\V1\ProfileAvatarController;
use Kolette\Auth\Http\Controllers\V1\ResetPasswordController;
use Kolette\Auth\Http\Controllers\V1\ForgotPasswordController;
use Kolette\Auth\Http\Controllers\V1\OneTimePasswordController;
use App\Http\Controllers\V1\Merchant\Profile\RewardSystemController;
use Kolette\Auth\Http\Controllers\V1\Customer\Payment\SetupIntent;
use Kolette\Auth\Http\Controllers\V1\Customer\Payment\CardController;
use Kolette\Auth\Http\Controllers\V1\Admin\UserAccountAccessController;
use App\Http\Controllers\V1\Merchant\Profile\BusinessInformationController;
use Kolette\Auth\Http\Controllers\V1\AccountSettings\ChangeEmailController;
use Kolette\Auth\Http\Controllers\V1\AccountSettings\DeleteAccountController;
use Kolette\Auth\Http\Controllers\V1\AccountSettings\ChangePasswordController;
use Kolette\Auth\Http\Controllers\V1\AccountSettings\ChangePhoneNumberController;
use Kolette\Auth\Http\Controllers\V1\AccountSettings\VerificationTokenController;


Route::apiResource('users', UserController::class)->except('store');

Route::get('users/{id}/avatar', [UserAvatarController::class, 'show'])->name('user.avatar.show');
Route::get('users/{id}/avatar/thumb', [UserAvatarController::class, 'showThumb'])->name('user.avatar.showThumb');

Route::post('users/{user}/avatar', [UserAvatarController::class, 'store'])->name('user.avatar.store');
Route::delete('users/{user}/avatar', [UserAvatarController::class, 'destroy'])->name('user.avatar.destroy');
Route::get('users/{id}/avatar', [UserAvatarController::class, 'show'])->name('user.avatar.show');
Route::get('users/{id}/avatar/thumb', [UserAvatarController::class, 'showThumb'])->name('user.avatar.showThumb');

Route::get('users/{id}/avatar/thumb', [UserAvatarController::class, 'showThumb'])->name('user.avatar.showThumb');

Route::prefix('auth')
    ->group(
        function () {
            // Route::post('check-email', [CheckController::class, 'checkEmail'])->name('checkEmail');
            // Route::post('check-username', [CheckController::class, 'checkUsername'])->name('checkUsername');

            // Route::post('login', [AuthController::class, 'login'])->name('login');
            // Route::post('guest/login', [GuestController::class, 'login'])->name('guest.login');

            // Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            // Route::get('me', [AuthController::class, 'me'])->name('me');

            // Route::get('/profile', [ProfileController::class, 'index']);
            // Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update']);
            // Route::post('/profile/avatar', [ProfileAvatarController::class, 'store']);
            // //Route::match(['put', 'patch'], '/profile/business_information', [BusinessInformationController::class, 'index']);
            // //Route::match(['put', 'patch'], '/profile/reward_system', [RewardSystemController::class, 'index']);
            // //Route::match(['put', 'patch'], '/profile/redeem_amount', [RewardSystemController::class, 'redeemAmount']);

            // Route::post('register', RegisterController::class)->name('register');
            // Route::post('forgot-password', ForgotPasswordController::class)->name('forgotPassword');
            // Route::post('reset-password', ResetPasswordController::class)->name('resetPassword');
            // Route::post('reset-password/check', [ResetPasswordController::class, 'checkToken'])->name(
            //     'resetPassword.check'
            // );
            // Route::post('reset-password/get-verified-account', [ResetPasswordController::class, 'getVerifiedAccount'])
            //     ->name('resetPassword.get-verified-account');
            // Route::post('verification/verify', [VerificationController::class, 'verify'])->name('verification.verify');
            // Route::post('verification/resend', [VerificationController::class, 'resend'])->name('verification.resend');

            // Route::post('otp/generate', [OneTimePasswordController::class, 'generate']);

            // Route::post('onboarding/email', [OnBoardingController::class, 'email']);
            // Route::post('onboarding/reward-system', [OnBoardingController::class, 'rewardSystem']);
            // Route::post('onboarding/redeem-amount', [OnBoardingController::class, 'redeemAmount']);
            // Route::post('onboarding/business-logo', [OnBoardingController::class, 'businessLogo']);
            // Route::post('onboarding/business-hours', [OnBoardingController::class, 'businessHours']);
            // Route::post('onboarding/business-name', [OnBoardingController::class, 'businessName']);
            // Route::post('onboarding/avatar', [OnBoardingController::class, 'avatar']);
            // Route::post('onboarding/complete', [OnBoardingController::class, 'complete']);


            // Route::post('change/email', [ChangeEmailController::class, 'change']);
            // Route::post('change/email/verify', [ChangeEmailController::class, 'verify']);

            // Route::post('change/phone-number', [ChangePhoneNumberController::class, 'change']);
            // Route::post('change/phone-number/verify', [ChangePhoneNumberController::class, 'verify']);

            // Route::post('change/password', ChangePasswordController::class);

            // Route::post('account/verification-token', VerificationTokenController::class);

            // Route::delete('account', DeleteAccountController::class);
        }
    );

Route::group(['prefix' => 'admin', 'middleware' => ['user.role:' . Role::ADMIN, 'auth']], function () {
    // Route::post('users/{user}/disable', [UserAccountAccessController::class, 'blockUserAccess']);
    // Route::post('users/{user}/enable', [UserAccountAccessController::class, 'grantUserAccess']);
});