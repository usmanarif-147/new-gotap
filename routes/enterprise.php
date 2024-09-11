<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;


Route::middleware('enterprise')->group(function () {

    Route::view('enterprise/dashboard', 'enterprise.dashboard')->name('enterprise.dashboard');

    // teams
    Route::view('enterprise/profiles', 'enterprise.profile.profiles')->name('enterprise.profiles');
    Route::view('enterprise/profile/create', 'enterprise.profile.create')->name('enterprise.profile.create');
    Route::view('enterprise/profile/{id}/edit', 'enterprise.profile.edit')->name('enterprise.profile.edit');

    //manage profile
    Route::view('enterprise/profile/{id}/manage', 'enterprise.profile.manageprofile')->name('enterprise.profile.manage');

    Route::get('enterprise/confirm-password', [ConfirmablePasswordController::class, 'showEnterprise'])
        ->name('enterprise.password.confirm');

    Route::post('enterprise/confirm-password', [ConfirmablePasswordController::class, 'storeEnterprise'])
        ->name('enterprise.password.confirm.store');

    Route::put('enterprise/password', [PasswordController::class, 'updateEnterprise'])
        ->name('enterprise.password.update');

    Route::post('enterprise/logout', [AuthenticatedSessionController::class, 'destroyEnterprise'])
        ->name('enterprise.logout');
});