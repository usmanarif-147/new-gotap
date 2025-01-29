<?php

use App\Http\Controllers\VCardController;
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

    //SubTeams
    Route::view('enterprise/subTeams', 'enterprise.profile.subteams')->name('enterprise.profile.subteams');

    //leads
    Route::view('enterprise/leads', 'enterprise.profile.leads')->name('enterprise.leads');
    Route::view('enterprise/leads/{id}/view', 'enterprise.profile.view-lead')->name('enterprise.leads.view');
    Route::get('/lead/download/{id}', [VCardController::class, 'downloadVCard'])->name('lead.download');

    //imageUploade
    Route::post('/upload-image', [VCardController::class, 'uploadImage'])->name('ckeditor.upload');

    //Request For Profile
    Route::view('enterprise/UserRequests', 'enterprise.profile.user-request-profile')->name('enterprise.requests');

    //map
    Route::view('enterprise/leads/map', 'enterprise.profile.leads-map')->name('enterprise.leads-map');

    //manage profile
    Route::view('enterprise/profile/{id}/manage', 'enterprise.profile.manageprofile')->name('enterprise.profile.manage');

    //Insights

    Route::view('enterprise/insights', 'enterprise.insights')->name('enterprise.insights');

    //my subscription
    Route::view('enterprise/mysubscription', 'enterprise.mysubscription')->name('enterprise.mysubscription');

    //Accessories
    Route::view('enterprise/accessories', 'enterprise.accessories')->name('enterprise.accessories');

    //Email Compaign

    Route::view('enterprise/emailcompaign', 'enterprise.email-compaign')->name('enterprise.emailcompaign');

    //Support Page

    Route::view('enterprise/support', 'enterprise.supportpage')->name('enterprise.support');

    //edit Enterprise
    Route::view('enterprise/edit', 'enterprise.edit-enterprise')->name('enterprise.edit');

    Route::get('enterprise/confirm-password', [ConfirmablePasswordController::class, 'showEnterprise'])
        ->name('enterprise.password.confirm');

    Route::post('enterprise/confirm-password', [ConfirmablePasswordController::class, 'storeEnterprise'])
        ->name('enterprise.password.confirm.store');

    Route::put('enterprise/password', [PasswordController::class, 'updateEnterprise'])
        ->name('enterprise.password.update');

    Route::post('enterprise/logout', [AuthenticatedSessionController::class, 'destroyEnterprise'])
        ->name('enterprise.logout');
});