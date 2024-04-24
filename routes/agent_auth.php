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

Route::middleware('guest:agent')->prefix('agent')->group(function () {
    Route::get('register', [RegisteredAgentController::class, 'create'])
                ->name('agent.register');

    Route::post('register', [RegisteredAgentController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('agent.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('agent.password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('agent.password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('agent.password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('agent.password.store');
});

Route::middleware('auth:agent')->prefix('agent')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('agent.verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('agent.verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('agent.verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('agent.password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('agent.password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('agent.logout');
});
