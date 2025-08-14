<?php

use App\Http\Controllers\EmailTrackingController;
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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
Route::domain(env('ENTERPRISE_DOMAIN'))->group(function () {
    Route::view('/', 'welcome');
});

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

Route::get('/clear-livewire', function () {
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');

    return '✅ Livewire and Laravel cache cleared!';
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

Route::get('/email/read/{compaignId}/{recipientEmail}', [EmailTrackingController::class, 'track'])->name('email.track');

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
Route::get('/{username}', [UserProfileController::class, 'viewProfileByUsername'])->name('viewProfileByUsername');

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

// Serve temporary banner files for email signature preview
Route::get('/temp-banner/{filename}', function ($filename) {
    $tempPath = storage_path('app/livewire-tmp/' . $filename);
    if (file_exists($tempPath)) {
        return response()->file($tempPath);
    }
    return response('File not found', 404);
})->name('temp.banner');

// QR Code generation for email signatures (without Imagick requirement)
Route::get('/qr-code/{profileId}', function ($profileId) {
    try {
        // Try to generate QR code with available backends
        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->generate(config('app.url') . '/profile/' . $profileId);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=31536000');
    } catch (\Exception $e) {
        // If QR code generation fails, return a simple SVG QR code
        $url = config('app.url') . '/profile/' . $profileId;
        $svg = '<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
            <rect width="200" height="200" fill="#ffffff" stroke="#000000" stroke-width="1"/>
            <text x="100" y="90" text-anchor="middle" font-family="Arial" font-size="12" fill="#000000">QR Code</text>
            <text x="100" y="110" text-anchor="middle" font-family="Arial" font-size="10" fill="#666666">Scan to connect</text>
            <text x="100" y="130" text-anchor="middle" font-family="Arial" font-size="8" fill="#999999">' . substr($url, 0, 30) . '...</text>
        </svg>';

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=31536000');
    }
})->name('qr-code.generate');

// Test route for virtual background generation (remove in production)
Route::get('/test-virtual-background/{profileId}', function ($profileId) {
    $controller = new \App\Http\Controllers\VirtualBackgroundGeneratorController();
    return $controller->generateBackground($profileId);
});

require __DIR__ . '/auth.php';
