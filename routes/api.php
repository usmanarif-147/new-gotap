<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\PhoneContactController;
use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ConnectController;
use App\Http\Controllers\Api\ViewProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Card;
use App\Models\Profile;
use App\Models\User;

Route::post('/platformClick', [PlatformController::class, 'incrementClick'])->name('inc.platform.click');
Route::get('/getCards', function () {
    return Card::select(
        'id',
        'uuid',
        'status'
    )
        ->where('status', 0)
        ->get()->toArray();
});

Route::get('/getAllProfiles', function () {
    return Profile::select(
        'id',
        'user_id',
        'username',
        'name'
    )
        ->get()->toArray();
});

Route::get('/getAllAccounts', function () {
    return User::select(
        'id',
        'username',
        'email'
    )
        ->where('role', 'user')
        ->get()->toArray();
});


Route::middleware('localization')->group(function () {

    Route::post('register', [AuthController::class, 'register'])->middleware(['throttle:6,1']);
    Route::post('login', [AuthController::class, 'login'])->middleware(['throttle:6,1']);
    Route::post('forgetPassword', [AuthController::class, 'forgotPassword'])->middleware(['throttle:6,1']);
    Route::post('resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('/recoverAccount', [AuthController::class, 'recoverAccount']);

    Route::post('/otpVerification', [AuthController::class, 'otpVerify']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::middleware('user.status')->group(function () {

            // User Profile
            Route::get('/getProfiles', [ProfileController::class, 'index']);
            Route::get('/profile', [ProfileController::class, 'profile']);
            Route::post('/addProfile', [ProfileController::class, 'addProfile']);
            Route::post('/addEnterpiseProfile', [ProfileController::class, 'addEnterpiseProfile']);
            Route::post('/deleteProfile', [ProfileController::class, 'deleteProfile']);
            Route::post('/switchProfile', [ProfileController::class, 'switchProfile']);
            Route::post('/updateProfile', [ProfileController::class, 'updateProfile']);
            Route::get('/userDirect', [ProfileController::class, 'userDirect']);
            Route::get('/search', [ProfileController::class, 'search']);
            Route::get('/profileAnalytics', [ProfileController::class, 'profileAnalytics']);

            // Platform
            Route::get('/categories', [CategoryController::class, 'index']);
            // Route::post('/searchPlatform', [PlatformController::class, 'search']);
            Route::post('/addPlatform', [PlatformController::class, 'addPlatform']);
            Route::post('/removePlatform', [PlatformController::class, 'deletePlatform']);
            Route::post('/swapOrder', [PlatformController::class, 'swapPlatform']);
            Route::get('/getAllPlatforms', [PlatformController::class, 'getAllPlatforms']);

            // Phone Contact
            Route::get('/phoneContacts', [PhoneContactController::class, 'index']);
            Route::post('/addPhoneContact', [PhoneContactController::class, 'addPhoneContact']);
            Route::post('/phoneContact', [PhoneContactController::class, 'phoneContact']);
            Route::post('/updatePhoneContact', [PhoneContactController::class, 'updatePhoneContact']);
            Route::post('/removeContact', [PhoneContactController::class, 'deletePhoneContact']);

            // Group
            Route::get('/groups', [GroupController::class, 'index']);
            Route::post('/group', [GroupController::class, 'group']);
            Route::post('/addGroup', [GroupController::class, 'addGroup']);
            Route::post('/updateGroup', [GroupController::class, 'updateGroup']);
            Route::post('/removeGroup', [GroupController::class, 'deleteGroup']);
            Route::post('/addProfileIntoGroup', [GroupController::class, 'addProfileIntoGroup']);
            Route::post('/addContactIntoGroup', [GroupController::class, 'addPhoneContactIntoGroup']);
            Route::post('/removeProfileFromGroup', [GroupController::class, 'removeProfileFromGroup']);
            Route::post('/removeContactFromGroup', [GroupController::class, 'removePhoneContactFromGroup']);

            // Cards
            Route::get('/cards', [CardController::class, 'index']);
            Route::post('/activateCard', [CardController::class, 'activateCard']);
            Route::post('/changeCardStatus', [CardController::class, 'changeCardStatus']);
            Route::post('/cardProfileDetail', [CardController::class, 'cardProfileDetail']);

            // View User Profile
            Route::post('/viewUserProfile', [ViewProfileController::class, 'viewUserProfile']);  // profile
            //profile Leads
            Route::post('/ProfileLeadsEnabled', [ViewProfileController::class, 'profileLeadsEnabled']);
            Route::get('/ProfileLeads', [ViewProfileController::class, 'profileLeads']);
            Route::post('/UpdateProfileLead', [ViewProfileController::class, 'updateProfileLead']);

            // Connects
            Route::post('/connect', [ConnectController::class, 'connect']);
            Route::post('/disconnect', [ConnectController::class, 'disconnect']);
            Route::post('/connectionProfile', [ConnectController::class, 'getConnectionProfile']);
            Route::get('/connections', [ConnectController::class, 'getConnections']);

            // Change Password
            Route::post('/changePassword', [AuthController::class, 'changePassword']);

            Route::get('/analytics', [UserController::class, 'analytics']);
            Route::post('/privateProfile', [UserController::class, 'privateProfile']);

            Route::get('/deactivateAccount', [UserController::class, 'deactivateAccount']);
            Route::get('/delete', [UserController::class, 'deleteAccount']);
        });
        Route::get('logout', [AuthController::class, 'logout']);
    });
});