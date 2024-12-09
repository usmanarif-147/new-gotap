<?php

use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\VCardController;
use App\Models\Connect;
use App\Models\Group;
use App\Models\Profile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;

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

Route::get('/connectsLeads', function () {
    $connections = Connect::all();

    foreach ($connections as $connection) {
        $profile = Profile::find($connection->connected_id);
        $user = User::find($connection->connecting_id);
        $active = Profile::where('user_id', $user->id)->where('active', 1)->first();
        DB::table('leads')->insert([
            'enterprise_id' => $profile->enterprise_id,
            'employee_id' => $profile->user_id,
            'viewing_id' => $profile->id,
            'viewer_id' => $active ? $active->id : null,
            'name' => $user->name ? $user->name : $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'type' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }


    return 'Leads created successfully for all connections!';
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
// Route::view('/card_id/{uuid}', 'view');

Route::get('/card_id/{uuid}', [UserProfileController::class, 'viewProfileByCardId']);

// Profile using username
// Route::view('/{username}', 'view');
Route::get('/{username}', [UserProfileController::class, 'viewProfileByUsername']);

//increment platform
Route::post('/platform/increment', [UserProfileController::class, 'incrementPlatformClick'])->name('platform.increment');

//lead
Route::post('/viewer/store', [UserProfileController::class, 'viewerDetail'])->name('viewer.store');

// delete url
Route::get('/account-deletion/policy', function () {
    return view('pages.account-deletion-policy');
});

Route::post('/changePassword', [ProfileController::class, 'changePassword'])->name('profile.change.password');

Route::get('save_contact/{id}', [VCardController::class, 'saveContact'])->name('save.contact');

require __DIR__ . '/auth.php';
