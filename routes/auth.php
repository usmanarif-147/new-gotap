<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\SetPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('admin/login', [AuthenticatedSessionController::class, 'createAdmin'])
        ->name('admin.login.form');
    Route::post('admin/login', [AuthenticatedSessionController::class, 'storeAdmin'])
        ->name('admin.login');
    Route::get('admin/forgot-password', [PasswordResetLinkController::class, 'createAdmin'])
        ->name('admin.password.request');
    Route::post('admin/forgot-password', [PasswordResetLinkController::class, 'storeAdmin'])
        ->name('admin.password.email');
    Route::get('admin/reset-password/{token}', [NewPasswordController::class, 'createAdmin'])
        ->name('admin.password.reset');
    Route::post('admin/reset-password', [NewPasswordController::class, 'storeAdmin'])
        ->name('admin.password.store');
});

Route::middleware('guest')->group(function () {

    Route::get('enterprise/set-password/{token}', [SetPasswordController::class, 'setPassword'])
        ->name('enterprise.set.password');
    Route::post('enterprise/save-password', [SetPasswordController::class, 'saveNewPassword'])
        ->name('enterprise.save.password');

    Route::get('enterprise/login', [AuthenticatedSessionController::class, 'createEnterprise'])
        ->name('enterprise.login.form');
    Route::post('enterprise/login', [AuthenticatedSessionController::class, 'storeEnterprise'])
        ->name('enterprise.login');
    Route::get('enterprise/forgot-password', [PasswordResetLinkController::class, 'createEnterprise'])
        ->name('enterprise.password.request');
    Route::post('enterprise/forgot-password', [PasswordResetLinkController::class, 'storeEnterprise'])
        ->name('enterprise.password.email');
    Route::get('enterprise/reset-password/{token}', [NewPasswordController::class, 'createEnterprise'])
        ->name('enterprise.password.reset');
    Route::post('enterprise/reset-password', [NewPasswordController::class, 'storeEnterprise'])
        ->name('enterprise.password.store');
});