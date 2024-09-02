<?php

use App\Http\Controllers\VCardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::view('/', 'welcome');

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

Route::get('/key', function () {
    Artisan::call('key:generate');
    dd("key generated");
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    dd("storage linked");
});

Route::get('/middleware', function () {
    Artisan::call('make:middleware Localization');
    dd("localization done");
});

Route::get('/set-private-val', function () {
    User::where('private', 1)->update([
        'private' => 0
    ]);
});


// Profile using card_id
Route::view('/card_id/{uuid}', 'profile');

// Profile using username
Route::view('/{username}', 'profile');


// Route::get('user/{id}/analytics', function ($id) {


//     $userId = $id;
//     $connections = DB::table('connects')->where('connecting_id', $userId)->get()->count();
//     $profileViews = User::where('id', $userId)->first()->tiks;


//     $platforms = DB::table('profile_platforms')
//         ->select(
//             'platforms.id',
//             'platforms.title',
//             'platforms.icon',
//             'profile_platforms.path',
//             'profile_platforms.label',
//             'profile_platforms.clicks',
//         )
//         ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
//         ->where('user_id', $userId)
//         ->orderBy(('profile_platforms.platform_order'))
//         ->get();


//     return response()->json(
//         [
//             'user' => [
//                 [
//                     'label' => trans('backend.connections'),
//                     'connections' => $connections,
//                     'icon' => 'uploads/photos/total_connections.png',
//                 ],
//                 [
//                     'label' => trans('backend.profile_views'),
//                     'profileViews' => $profileViews,
//                     'icon' => 'uploads/photos/profile_views.png',
//                 ],
//                 [
//                     'label' => trans('backend.platform_clicks'),
//                     'total_clicks' => $platforms->sum('clicks'),
//                     'icon' => 'uploads/photos/total_clicks.png',
//                 ],
//                 [
//                     'label' => trans('backend.platforms'),
//                     'total_platforms' => $platforms->count(),
//                     'icon' => 'uploads/photos/total_platforms.png',
//                 ],
//                 [
//                     'label' => trans('backend.groups'),
//                     'total_groups' => Group::where('user_id', $userId)->count(),
//                     'icon' => 'uploads/photos/total_groups.png',
//                 ],
//             ],
//             'platforms' => $platforms
//         ]
//     );
// });

Route::get('save_contact/{id}', [VCardController::class, 'saveContact'])->name('save.contact');

require __DIR__ . '/auth.php';
