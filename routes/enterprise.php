<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Dashboard;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;


Route::middleware('enterprise')->group(function () {

    Route::view('enterprise/dashboard', 'enterprise.dashboard')->name('enterprise.dashboard');
    Route::view('enterprise/teams', 'enterprise.team.teams')->name('enterprise.teams');

    Route::get('enterprise/confirm-password', [ConfirmablePasswordController::class, 'showEnterprise'])
        ->name('enterprise.password.confirm');

    Route::post('enterprise/confirm-password', [ConfirmablePasswordController::class, 'storeEnterprise'])
        ->name('enterprise.password.confirm.store');

    Route::put('enterprise/password', [PasswordController::class, 'updateEnterprise'])
        ->name('enterprise.password.update');

    Route::post('enterprise/logout', [AuthenticatedSessionController::class, 'destroyEnterprise'])
        ->name('enterprise.logout');
});
