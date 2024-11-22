<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Platform;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class UserProfileController extends Controller
{

    public function index()
    {
        $identifier = request()->segment(1) == 'card_id' ? 'uuid' : request()->username;

        if ($identifier == 'uuid') {
            $profile = Card::join('profile_cards', 'cards.id', 'profile_cards.card_id')
                ->join('profiles', 'profiles.id', 'profile_cards.profile_id')
                ->where('cards.uuid', request()->segment(2))
                ->select('profiles.*', 'profile_cards.status as card_status')
                ->first();

            if (!$profile || !$profile->card_status) {
                return abort(404);
            }
        } else {
            $profile = Profile::where('username', $identifier)->first();

            if (!$profile) {
                return abort(404);
            }
        }

        $directPath = null;
        $direct = null;
        $userPlatforms = [];

        // Fetch the user's platforms
        $platforms = DB::table('profile_platforms')
            ->select(
                'platforms.id as platform_id',
                'platforms.title',
                'platforms.icon',
                'platforms.input',
                'platforms.baseUrl as base_url',
                'profile_platforms.user_id as user_id',
                'profile_platforms.created_at',
                'profile_platforms.path',
                'profile_platforms.label',
                'profile_platforms.platform_order',
                'profile_platforms.direct',
                'profiles.private as check_profile_privacy'
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->join('profiles', 'profile_platforms.profile_id', 'profiles.id')
            ->where('profile_id', $profile->id)
            ->orderBy('profile_platforms.platform_order')
            ->get();

        // Handle direct path if user has a direct platform
        if ($profile->user_direct) {
            $direct = $platforms->first();
        }

        if ($direct) {
            if (!$direct->base_url) {
                if (!str_contains($direct->path, 'https') && !str_contains($direct->path, 'http')) {
                    $directPath = 'https://' . $direct->path;
                }
            } else {
                $directPath = $direct->base_url . '/' . $direct->path;
            }
        }

        // Increment user tiks
        User::find($profile->user_id)->increment('tiks');
        Profile::find($profile->id)->increment('tiks');

        // Check profile privacy
        $is_private = Profile::where('id', $profile->id)->first()->private;

        // Process user platforms
        foreach ($platforms as $platform) {
            if (!$platform->base_url) {
                if (!str_contains($platform->path, 'https') && !str_contains($platform->path, 'http')) {
                    $platform->base_url = 'https://';
                }
            }
            array_push($userPlatforms, $platform);
        }

        // Chunk user platforms into groups of 4
        $userPlatforms = array_chunk($userPlatforms, 4);


        return view('profile', compact('profile', 'userPlatforms', 'is_private', 'directPath'));
    }


    public function getProfileByUuid()
    {
        $uuid = request()->uuid;
        $user = Card::join('profile_cards', 'cards.id', 'profile_cards.card_id')
            ->join('users', 'users.id', 'profile_cards.user_id')
            ->where('cards.uuid', $uuid)
            ->select('users.*', 'profile_cards.status as card_status')
            ->first();

        if (!$user) {
            return abort(404);
        }

        if (!$user->card_status) {
            return abort(404);
        }

        $directPath = null;
        $direct = null;

        $userPlatforms = [];
        $platforms = DB::table('profile_platforms')
            ->select(
                'platforms.id as platform_id',
                'platforms.title',
                'platforms.icon',
                'platforms.input',
                'platforms.baseUrl as base_url',
                'profile_platforms.user_id as user_id',
                'profile_platforms.created_at',
                'profile_platforms.path',
                'profile_platforms.label',
                'profile_platforms.platform_order',
                'profile_platforms.direct',
                'users.private as check_user_privacy'
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->join('users', 'profile_platforms.user_id', 'users.id')
            ->where('user_id', $user->id)
            ->orderBy(('profile_platforms.platform_order'))
            ->get();

        if ($user->user_direct) {
            $direct = $platforms->first();
        }

        if ($direct) {
            if (!$direct->base_url) {
                if (!str_contains($direct->path, 'https') || !str_contains($direct->path, 'http')) {
                    $directPath = 'https://' . $direct->path;
                }
            } else {
                $directPath = $direct->base_url . '/' . $direct->path;
            }
        }

        User::find($user->id)->increment('tiks');
        $is_private = User::where('id', $user->id)->first()->private;

        for ($i = 0; $i < $platforms->count(); $i++) {
            if (!$platforms[$i]->base_url) {
                if (!str_contains($platforms[$i]->path, 'https') || !str_contains($platforms[$i]->path, 'http')) {
                    $platforms[$i]->base_url = 'https://';
                }
            }
            array_push($userPlatforms, $platforms[$i]);
        }

        $userPlatforms = array_chunk($userPlatforms, 4);

        return view('profile', compact('user', 'userPlatforms', 'is_private', 'directPath'));
    }


    public function getProfileByUsername()
    {
        $user = User::where('username', request()->username)
            ->first();
        if (!$user) {
            return abort(404);
        }

        $directPath = null;
        $direct = null;

        $userPlatforms = [];
        $platforms = DB::table('profile_platforms')
            ->select(
                'platforms.id as platform_id',
                'platforms.title',
                'platforms.icon',
                'platforms.input',
                'platforms.baseUrl as base_url',
                'profile_platforms.user_id as user_id',
                'profile_platforms.created_at',
                'profile_platforms.path',
                'profile_platforms.label',
                'profile_platforms.platform_order',
                'profile_platforms.direct',
                'users.private as check_user_privacy'
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->join('users', 'profile_platforms.user_id', 'users.id')
            ->where('user_id', $user->id)
            ->orderBy(('profile_platforms.platform_order'))
            ->get();

        if ($user->user_direct) {
            $direct = $platforms->first();
        }

        if ($direct) {
            if (!$direct->base_url) {
                if (!str_contains($direct->path, 'https') || !str_contains($direct->path, 'http')) {
                    $directPath = 'https://' . $direct->path;
                }
            } else {
                $directPath = $direct->base_url . '/' . $direct->path;
            }
        }

        User::find($user->id)->increment('tiks');
        $is_private = User::where('id', $user->id)->first()->private;

        for ($i = 0; $i < $platforms->count(); $i++) {
            if (!$platforms[$i]->base_url) {
                if (!str_contains($platforms[$i]->path, 'https') || !str_contains($platforms[$i]->path, 'http')) {
                    $platforms[$i]->base_url = 'https://';
                }
            }
            array_push($userPlatforms, $platforms[$i]);
        }

        $userPlatforms = array_chunk($userPlatforms, 4);

        return view('profile', compact('user', 'userPlatforms', 'is_private', 'directPath'));
    }

    //view by username
    public function viewProfileByUsername()
    {
        $identifier = request()->username;
        $profile = Profile::select(
            'id',
            'username',
            'type',
            'job_title',
            'work_position',
            'bio',
            'company',
            'photo',
            'cover_photo',
            'user_direct',
            'user_id',
            'enterprise_id',
            'is_leads_enabled',
            'private'
        )
            ->where('username', $identifier)
            ->first();
        $platforms = $this->getProfileData($profile);
        $profileCheck = $this->userdetail($profile);
        return view('profile-view', ['profile' => $profile, 'platforms' => $platforms['platforms'], 'profilecheck' => $profileCheck, 'redicretTo' => $platforms['redirect']]);
    }

    //view by card Id

    public function viewProfileByCardId()
    {
        $identifier = request()->uuid;
        $profile = Card::join('profile_cards', 'cards.id', '=', 'profile_cards.card_id')
            ->join('profiles', 'profiles.id', '=', 'profile_cards.profile_id')
            ->where('cards.uuid', $identifier)
            ->select('profiles.*', 'profile_cards.status as card_status')
            ->first();
        $platforms = $this->getProfileData($profile);
        $profileCheck = $this->userdetail($profile);
        return view('profile-view', ['profile' => $profile, 'platforms' => $platforms['platforms'], 'profilecheck' => $profileCheck, 'redicretTo' => $platforms['redirect']]);
    }

    //get profile data
    protected function getProfileData($profile)
    {
        $platforms = [];
        if (!$profile) {
            return abort(404);
        }
        if ($profile->user_id != null) {
            User::where('id', $profile->user_id)->increment('tiks');
        }
        Profile::where('id', $profile->id)->increment('tiks');


        if ($profile->user_direct) {
            $redirect = $this->isProfileDirect($profile);
        } else {
            $redirect = null;
        }

        $platforms = DB::table('profile_platforms')
            ->select(
                'platforms.id as platform_id',
                'platforms.title',
                'platforms.icon',
                'platforms.input',
                'platforms.baseUrl as base_url',
                'profile_platforms.user_id as user_id',
                'profile_platforms.created_at',
                'profile_platforms.path',
                'profile_platforms.label',
                'profile_platforms.platform_order',
                'profile_platforms.direct',
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->join('profiles', 'profile_platforms.profile_id', 'profiles.id')
            ->where('profile_id', $profile->id)
            ->orderBy('profile_platforms.platform_order')
            ->get();

        return ['platforms' => $platforms->chunk(4), 'redirect' => $redirect];
    }

    protected function isProfileDirect($profile)
    {
        $userPlatform = DB::table('profile_platforms')
            ->where('profile_id', $profile->id)
            ->orderBy('platform_order')
            ->first();

        if ($userPlatform) {
            $platform = Platform::where('id', $userPlatform->platform_id)->first();
            if ($platform) {
                DB::table('profile_platforms')
                    ->where('profile_id', $profile->id)
                    ->where('platform_id', $userPlatform->platform_id)
                    ->increment('clicks');
                if (!str_contains($userPlatform->path, 'https') && !str_contains($userPlatform->path, 'http')) {
                    $redicretTo = 'https://' . $userPlatform->path;
                } else {
                    $redicretTo = $userPlatform->path;
                }

                if ($platform->baseURL) {
                    $redicretTo = $platform->baseURL . '/' . $userPlatform->path;
                }
            }
        }
        return $redicretTo;
    }

    protected function userdetail($profile)
    {
        $user = auth()->user();
        if ($user) {
            $profileCheck = Profile::where('user_id', $user->id)->orwhere('enterprise_id', $user->id)->where('id', $profile->id)->exists();
        } else {
            $profileCheck = false;
        }
        return $profileCheck;
    }

    public function incrementPlatformClick(Request $request)
    {
        $platformId = $request->input('platform_id');
        $url = $request->input('url');
        $profile = Profile::find($request->input('id'));

        if (!$profile->private) {
            DB::table('profile_platforms')
                ->where('profile_id', $profile->id)
                ->where('platform_id', $platformId)
                ->increment('clicks');

            return response()->json(['redirect' => $url]);
        }

        return response()->json(['error' => 'Profile is private'], 403);
    }

    public function viewerDetail(Request $request)
    {
        $profile = Profile::find($request->input('id'));
        $ip = request()->ip();
        $locationData = $this->getUserLocation($ip);
        if ($locationData && $request->input('latitude')) {
            $country = $locationData['geoplugin_countryName'];
            $ip_address = $locationData['geoplugin_request'];
            $state = $locationData['geoplugin_region'];
            $city = $locationData['geoplugin_city'];
            $lat = $request->input('latitude');
            $long = $request->input('longitude');
        } else {
            $country = null;
            $ip_address = null;
            $state = null;
            $city = null;
            $lat = null;
            $long = null;
        }
        $data = ['name' => $request->input('name'), 'email' => $request->input('email'), 'phone' => $request->input('phone')];
        DB::table('leads')->insert([
            'enterprise_id' => $profile->enterprise_id,
            'employee_id' => $profile->user_id,
            'viewing_id' => $profile->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'ip_address' => $ip_address,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'latitude' => $lat,
            'longitude' => $long,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Details saved successfully!']);

    }

    private function getUserLocation($ip = null)
    {
        $client = new Client();

        try {
            // Make the API call to GeoPlugin
            $response = $client->get("http://www.geoplugin.net/json.gp?ip={$ip}");

            // Decode the JSON response
            $locationData = json_decode($response->getBody(), true);

            if (isset($locationData['geoplugin_countryName'])) {
                return $locationData; // Return the array data directly
            } else {
                return 0;
            }

        } catch (RequestException $e) {
            return 0;
        }
    }

}
