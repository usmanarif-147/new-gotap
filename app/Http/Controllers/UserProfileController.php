<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{

    public function index()
    {
        $identifier = request()->segment(1) == 'card_id' ? 'uuid' : request()->username;

        if ($identifier == 'uuid') {
            $profile = Card::join('user_cards', 'cards.id', 'user_cards.card_id')
                ->join('profiles', 'profiles.id', 'user_cards.profile_id')
                ->where('cards.uuid', request()->segment(2))
                ->select('profiles.*', 'user_cards.status as card_status')
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
        $user = Card::join('user_cards', 'cards.id', 'user_cards.card_id')
            ->join('users', 'users.id', 'user_cards.user_id')
            ->where('cards.uuid', $uuid)
            ->select('users.*', 'user_cards.status as card_status')
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

        // dd($userPlatforms);

        $userPlatforms = array_chunk($userPlatforms, 4);

        return view('profile', compact('user', 'userPlatforms', 'is_private', 'directPath'));
    }
}
