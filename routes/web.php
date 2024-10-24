<?php

use App\Http\Controllers\VCardController;
use App\Models\Group;
use App\Models\Profile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\ProfileController;

Route::view('/', 'welcome');

Route::get('/set/default/profile', function () {


    $users = User::select('id')
        ->orderBy('id')
        ->get();
    foreach ($users as $user) {
        Profile::where('user_id', $user->id)
            ->update([
                'is_default' => 0
            ]);

        $first_profile = Profile::where('user_id', $user->id)->first();
        if ($first_profile) {
            Profile::where('id', $first_profile->id)->update([
                'is_default' => 1
            ]);
        }
    }

    dd("done");
});

Route::get('/privacy-and-policy', function () {
    return view('landingpage.privacy-policy');
})->name('privacy');

Route::get('/terms-and-conditions', function () {
    return view('landingpage.terms-conditions');
})->name('terms');


Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    dd("done");
});

// Route::get('/key', function () {
//     Artisan::call('key:generate');
//     dd("key generated");
// });

// Route::get('/storage-link', function () {
//     Artisan::call('storage:link');
//     dd("storage linked");
// });

// Route::get('/user-new-group', function () {
//     $users = User::where('role', 'user')->pluck('id');
//     for ($i = 0; $i < $users->count(); $i++) {

//         Group::create([
//             'user_id' => $users[$i],
//             'title' => 'leads'
//         ]);
//     }
//     dd('Add new groups');
// });

// Route::get('/middleware', function () {
//     Artisan::call('make:middleware Localization');
//     dd("localization done");
// });

// Route::get('/set-private-val', function () {
//     User::where('private', 1)->update([
//         'private' => 0
//     ]);
// });


// Profile using card_id
Route::view('/card_id/{uuid}', 'view-profile-by-card');

// Profile using username
Route::view('/{username}', 'view-profile-by-username');

// delete url
Route::get('/account-deletion/policy', function () {
    return view('pages.account-deletion-policy');
});

Route::post('/changePassword', [ProfileController::class, 'changePassword'])->name('profile.change.password');

Route::get('save_contact/{id}', [VCardController::class, 'saveContact'])->name('save.contact');

require __DIR__ . '/auth.php';
