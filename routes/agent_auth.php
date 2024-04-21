<?php

use App\Http\Controllers\Auth\Agent\AuthenticatedSessionController;
use App\Http\Controllers\Auth\Agent\ConfirmablePasswordController;
use App\Http\Controllers\Auth\Agent\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\Agent\EmailVerificationPromptController;
use App\Http\Controllers\Auth\Agent\NewPasswordController;
use App\Http\Controllers\Auth\Agent\PasswordController;
use App\Http\Controllers\Auth\Agent\PasswordResetLinkController;
use App\Http\Controllers\Auth\Agent\RegisteredAgentController;
use App\Http\Controllers\Auth\Agent\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:agent')->prefix('agent')->name('agent.*')->group(function () {
    Route::get('register', [RegisteredAgentController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredAgentController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth:agent')->prefix('agent')->name('agent.*')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

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
