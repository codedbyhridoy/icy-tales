<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('admin-login', [AuthenticatedSessionController::class, 'create'])
    ->name('admin-login');
Route::get('admin', [AuthenticatedSessionController::class, 'create']);
Route::middleware('guest:admin')->group(function () {
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-admin-password', [PasswordResetLinkController::class, 'createAdmin'])
        ->name('admin.password.request');
    Route::post('forgot-admin-password', [PasswordResetLinkController::class, 'storeAdmin'])
        ->name('admin.password.email');
    
    Route::get('reset-admin-password/{token}', [NewPasswordController::class, 'createAdmin'])
        ->name('admin.password.reset');
    Route::post('reset-admin-password', [NewPasswordController::class, 'storeAdmin'])
        ->name('admin.password.store');
});

Route::get('login', [AuthenticatedSessionController::class, 'customerCreate'])
        ->name('login');
Route::get('register', [RegisteredUserController::class, 'registerCreate'])->name('register');
Route::middleware('guest:web')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');
    Route::post('customer-login', [AuthenticatedSessionController::class, 'customerLogin'])->name('customer.login')->middleware('precognitive');

    Route::post('register', [RegisteredUserController::class, 'userRegistration'])->name('user.registration')->middleware('precognitive');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth:web')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroyAdmin'])->name('logout');
});
