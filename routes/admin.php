<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotifychangepasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Admin\Card\Cards;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;


Route::middleware('admin')->group(function () {

    Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');

    // users
    Route::view('admin/users', 'admin.user.users')->name('admin.users');
    Route::view('admin/user/{id}/view', 'admin.user.view')->name('admin.user.view');

    // applications
    Route::view('admin/applications', 'admin.application.applications')->name('admin.applications');

    //Add Enterpriser 
    Route::view('admin/enterpriser-create', 'admin.enterpriser.create')->name('admin.enterpriser.create');

    // categories
    Route::view('admin/categories', 'admin.category.categories')->name('admin.categories');
    Route::view('admin/category/create', 'admin.category.create')->name('admin.category.create');
    Route::view('admin/category/{id}/edit', 'admin.category.edit')->name('admin.category.edit');

    // platforms
    Route::view('admin/platforms', 'admin.platform.platforms')->name('admin.platforms');
    Route::view('admin/platform/create', 'admin.platform.create')->name('admin.platform.create');
    Route::view('admin/platform/{id}/edit', 'admin.platform.edit')->name('admin.platform.edit');

    // cards
    Route::view('admin/cards', 'admin.card.cards')->name('admin.cards');
    Route::view('admin/card/create', 'admin.card.create')->name('admin.card.create');
    // Route::view('admin/card/{id}/edit', 'admin.card.edit')->name('admin.card.edit');


    Route::view('/downloadCardsCSV', [Cards::class, 'downloadCsv'])->name('export');

    // profile
    // Route::post('/changePassword', [ProfileController::class, 'changePassword'])->name('profile.change.password');

    //change password
    Route::get('/change-passwords', [NotifychangepasswordController::class, 'resetAllPasswords']);


    Route::get('admin/confirm-password', [ConfirmablePasswordController::class, 'showAdmin'])
        ->name('admin.password.confirm');
    Route::post('admin/confirm-password', [ConfirmablePasswordController::class, 'storeAdmin'])
        ->name('admin.password.confirm.store');
    Route::put('admin/password', [PasswordController::class, 'updateAdmin'])
        ->name('admin.password.update');
    Route::post('admin/logout', [AuthenticatedSessionController::class, 'destroyAdmin'])
        ->name('admin.logout');
});
