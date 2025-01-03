<?php

namespace App\Http\Controllers\Api;

use App\Models\ProfileCard;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\AddProfileRequest;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Api\UserProfileResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Card;
use App\Models\Group;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::where('id', auth()->id())->first();

        return response()->json([
            'message' => 'User Profiles',
            'data' => new UserResource($user),
        ]);
    }

    public function profile()
    {
        $profile = getActiveProfile();
        $platforms = DB::table('profile_platforms')
            ->select('platforms.id', 'platforms.title', 'platforms.icon', 'platforms.input', 'platforms.baseUrl', 'profile_platforms.created_at', 'profile_platforms.path', 'profile_platforms.label', 'profile_platforms.platform_order', 'profile_platforms.direct', 'profile_platforms.profile_id')
            ->join('platforms', 'platforms.id', '=', 'profile_platforms.platform_id')
            ->where('user_id', auth()->id())
            ->where('profile_id', $profile->id)
            ->orderBy('profile_platforms.platform_order')
            ->get();

        return response()->json([
            'profile' => new UserProfileResource($profile),
        ]);
    }

    public function switchProfile(Request $request)
    {
        $request->validate(
            [
                'profile_id' => ['required'],
            ],
            [
                'profile_id.required' => 'Please enter valid Profile Id',
            ],
        );

        $exist = Profile::where('user_id', auth()->id())
            ->where('id', $request->profile_id)
            ->exists();
        if (!$exist) {
            return response()->json([
                'message' => 'Profile Does not exist',
            ]);
        }

        $active = Profile::where('user_id', auth()->id())
            ->where('id', $request->profile_id)
            ->first()->active;
        if ($active) {
            return response()->json([
                'message' => 'Profile is Already Active',
            ]);
        }

        Profile::where('user_id', auth()->id())->update([
            'active' => 0,
        ]);

        Profile::where('user_id', auth()->id())
            ->where('id', $request->profile_id)
            ->update([
                'active' => 1,
            ]);

        return response()->json([
            'message' => 'Profile Switched Successfully',
        ]);
    }

    public function addProfile(AddProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = [];
            if ($request->hasFile('photo')) {
                $data['photo'] = Storage::disk('public')->put('/uploads/photos', $request->photo);
            }
            if ($request->hasFile('cover_photo')) {
                $data['cover_photo'] = Storage::disk('public')->put('/uploads/coverPhotos', $request->cover_photo);
            }

            $data['user_id'] = auth()->id();
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['username'] = $request->username;
            $data['work_position'] = $request->work_position;
            $data['phone'] = $request->phone;
            $data['job_title'] = $request->job_title;
            $data['company'] = $request->company;
            $data['address'] = $request->address;
            $data['bio'] = $request->bio;
            $data['active'] = 1;

            Profile::where('user_id', auth()->id())->update(['active' => 0]);

            $profile = Profile::create($data);

            DB::commit();

            return response()->json([
                'message' => trans('profile created successfully'),
                'profile' => new UserProfileResource($profile),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function addEnterpiseProfile(Request $request)
    {
        $data = $request->validate(
            [
                'user_id' => 'required',
                'card_id' => 'required',
            ],
            [
                'user_id.required' => 'User id is required',
                'card_id.required' => 'Card Id is required',
            ],
        );

        // check user exist
        $exist = User::where('id', $request->user_id)->exists();
        if (!$exist) {
            return response()->json(
                [
                    'message' => 'User Does not exist',
                ],
                400,
            );
        }
        // check card status and existence
        $card = Card::Where('uuid', $request->card_id)->first();

        if (!$card) {
            return response()->json(
                [
                    'message' => 'Card Does not exist',
                ],
                400,
            );
        }

        if ($card->status == 0) {
            return response()->json(
                [
                    'message' => 'Card is not active yet with any profile!',
                ],
                400,
            );
        }

        // link profile with user
        $profileCard = ProfileCard::where('card_id', $card->id)->first();
        $profileCardID = $profileCard->id;
        $profileID = $profileCard->profile_id;
        $profile = Profile::find($profileID);
        $profilePlatforms = DB::table('profile_platforms')->where('profile_id', $profileID)->get();
        foreach ($profilePlatforms as $key => $value) {
            DB::table('profile_platforms')
                ->where('id', $value->id)
                ->update([
                    'user_id' => $request->user_id,
                ]);
        }
        Profile::where('id', $profileID)->update(['user_id' => $request->user_id]);
        ProfileCard::where('id', $profileCardID)->update(['user_id' => $request->user_id]);

        return response()->json([
            'message' => trans('enterprise profile linked successfully'),
            'profile' => new UserProfileResource($profile),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $profile = getActiveProfile();
        try {
            $cover_photo = $profile->cover_photo;
            $photo = $profile->photo;

            if ($request->hasFile('cover_photo')) {
                if ($cover_photo) {
                    Storage::disk('public')->delete($cover_photo);
                }
                $cover_photo = Storage::disk('public')->put('uploads/coverPhotos', $request->cover_photo);
            }

            if ($request->hasFile('photo')) {
                if ($photo) {
                    Storage::disk('public')->delete($photo);
                }
                $photo = Storage::disk('public')->put('uploads/photos', $request->photo);
            }

            $profile = getActiveProfile();

            $isUpdated = Profile::where('id', $profile->id)
                ->where('user_id', auth()->id())
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'phone' => $request->phone,
                    'work_position' => $request->work_position,
                    'job_title' => $request->job_title,
                    'company' => $request->company,
                    'address' => $request->address,
                    'bio' => $request->bio,
                    'is_leads_enabled' => $request->is_leads_enabled,
                    'cover_photo' => $cover_photo,
                    'photo' => $photo,
                ]);

            if (!$isUpdated) {
                return response()->json([
                    'message' => trans('backend.profile_updated_failed'),
                ]);
            }

            $user = User::where('id', auth()->id())->get()->first();
            $profile = Profile::where('id', $profile->id)
                ->where('user_id', auth()->id())
                ->get()
                ->first();

            return response()->json([
                'message' => trans('backend.profile_updated_success'),
                // 'user' => new UserResource($user),
                'profile' => new UserProfileResource($profile),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function userDirect()
    {
        $profile = getActiveProfile();
        if ($profile->user_direct) {
            Profile::where('id', $profile->id)->update([
                'user_direct' => 0,
            ]);

            $profile = getActiveProfile();

            return response()->json([
                'message' => trans('backend.platform_set_public'),
                'profile' => new UserProfileResource($profile),
            ]);
        }

        Profile::where('id', $profile->id)->update([
            'user_direct' => 1,
        ]);
        $profile = getActiveProfile();
        return response()->json([
            'message' => trans('backend.first_platform_public'),
            'profile' => new UserProfileResource($profile),
        ]);
    }

    // public function privateProfile()
    // {

    //     $status = auth()->user()->private ? 'Public' : 'Private';

    //     User::where('id', auth()->id())
    //         ->update(
    //             [
    //                 'user_direct' => auth()->user()->user_direct ? 0 : 1
    //             ]
    //         );

    //     return response()->json(['message' => trans('backend.profile_set_to') . $status, 'data' => auth()->user()]);
    // }

    public function search(SearchRequest $request)
    {
        $searchQuery = $request->input('query', '');

        if (empty($searchQuery)) {
            $users = User::all();
            $message = 'All users fetched successfully.';
        } else {
            $users = User::where('name', 'like', '%' . $searchQuery . '%')->get();
            if ($users->isEmpty()) {
                $message = 'No users found.';
            } else {
                $message = 'Users have been found.';
            }
        }

        return response()->json([
            'message' => $message,
            'data' => $users,
        ]);
    }

    /**
     * Delete profile chat gpt code
     */
    public function deleteProfile(Request $request)
    {
        $request->validate(
            [
                'profile_id' => ['required'],
            ],
            [
                'profile_id.required' => 'Please enter valid Profile Id',
            ],
        );

        $profile = Profile::where('user_id', auth()->id())
            ->where('id', $request->profile_id)
            ->first();

        if (!$profile) {
            return response()->json(
                [
                    'message' => 'Profile Does not exist',
                ],
                400,
            );
        }

        if ($profile->is_default) {
            return response()->json(
                [
                    'message' => 'You cannot delete the Default Profile.',
                ],
                400,
            );
        }

        if ($profile->enterprise_id) {
            return response()->json(
                [
                    'message' => 'You cannot delete an enterprise Profile.',
                ],
                400,
            );
        }

        try {
            DB::transaction(function () use ($profile) {
                // Delete profile platforms from profile_platforms table
                $this->deleteProfilePlatforms($profile->id);

                // Delete all cards linked with the profile and set their status to inactive
                $this->deleteProfileCards($profile->id);

                // Remove the profile from all groups where it exists and decrement total_profiles
                $this->removeProfileFromGroups($profile->id);

                // Remove from connects
                $this->removeFromConnects($profile->id);

                //Remove from leads
                $this->removeFromLeads($profile->id);

                // Delete profile cover photo
                if ($profile->cover_photo) {
                    Storage::disk('public')->delete($profile->cover_photo);
                }

                // Delete profile photo
                if ($profile->photo) {
                    Storage::disk('public')->delete($profile->photo);
                }

                // Delete profile
                $profile->delete();
            });

            $profile = Profile::where('user_id', auth()->id())->where('active', 1)->first();

            if (!$profile) {
                Profile::where('user_id', auth()->id())
                    ->where('is_default', 1)
                    ->update([
                        'active' => 1,
                    ]);
            }

            return response()->json([
                'message' => 'Profile deleted successfully',
            ]);
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' => $ex->getMessage(),
                ],
                500,
            );
        }
    }

    private function deleteProfilePlatforms($profileId)
    {
        DB::table('profile_platforms')->where('profile_id', $profileId)->delete();
    }

    private function deleteProfileCards($profileId)
    {
        $profile_cards = DB::table('profile_cards')->where('profile_id', $profileId)->get();
        foreach ($profile_cards as $card) {
            Card::where('id', $card->card_id)->update(['status' => 0]);
        }
        DB::table('profile_cards')->where('profile_id', $profileId)->delete();
    }

    private function removeFromConnects($profileId)
    {
        DB::table('connects')->where('connected_id', $profileId)->delete();
    }
    private function removeFromLeads($profileId)
    {
        DB::table('leads')->where('viewing_id', $profileId)->delete();
    }
    private function removeProfileFromGroups($profileId)
    {
        $user_groups = DB::table('user_groups')->where('profile_id', $profileId)->get();
        foreach ($user_groups as $group) {
            Group::where('id', $group->group_id)->decrement('total_profiles');
        }
        DB::table('user_groups')->where('profile_id', $profileId)->delete();
    }

    public function profileAnalytics()
    {
        $profile = getActiveProfile();
        $profileViews = $profile->tiks;
        $totalTaps = $profile->taps;
        $platforms = DB::table('profile_platforms')
            ->select(
                'platforms.id',
                'platforms.title',
                'platforms.icon',
                'profile_platforms.path',
                'profile_platforms.label',
                'profile_platforms.clicks',
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->where('profile_id', $profile->id)
            ->orderBy('profile_platforms.clicks')
            ->get();

        $tapThroughRate = $profileViews > 0 ? round(($totalTaps / $profileViews) * 100) : 0;


        return response()->json(
            [
                'ProfileAnalytics' => [
                    [
                        'label' => 'Taps Streak',
                        'clicks' => $totalTaps,
                        'text' => '',
                    ],
                    [
                        'label' => 'Taps',
                        'clicks' => $profileViews,
                        'text' => 'The total number of times your GOtaps digital business card is viewed. This can be via a GOtaps device, your GOtaps QR code, or even a tap of your link in a bio',
                    ],
                    [
                        'label' => 'Link Taps',
                        'clicks' => $platforms->sum('clicks'),
                        'text' => 'The total number of times one of your links was tapped. Sharing with GOtaps Direct also counts as a tap for that single link shared directly.',
                    ],
                    [
                        'label' => 'New Connections',
                        'clicks' => DB::table('connects')->where('connecting_id', auth()->id())->count(),
                        'text' => 'The total number of new connections that you gained by scanning a business card, having someone tap "Save Contact" on your profile, or by meeting another GOtaps user',
                    ],
                    [
                        'label' => 'Tap Through Rate',
                        'clicks' => $tapThroughRate,
                        'text' => 'Your ratio of taps to views. Out of the total views you had on your digital business card, how many of those people actually tapped a link. This is important for maximizing your conversions and engagement.',
                    ]
                ],
                'platforms' => $platforms
            ]
        );
    }

    // public function profileAnalytics()
    // {
    //     $profile = getActiveProfile();
    //     $profileViews = $profile->tiks;

    //     // Fetch platform-related analytics
    //     $platforms = DB::table('profile_platforms')
    //         ->select(
    //             'platforms.id',
    //             'platforms.title',
    //             'platforms.icon',
    //             'profile_platforms.path',
    //             'profile_platforms.clicks',
    //         )
    //         ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
    //         ->where('profile_id', $profile->id)
    //         ->orderBy('profile_platforms.clicks')
    //         ->get();

    //     $totalLinkTaps = $platforms->sum('clicks');
    //     $newConnections = DB::table('connects')->where('connecting_id', auth()->id())->count();

    //     // Calculate Tap Through Rate (ratio of link taps to profile views)
    //     $tapThroughRate = $profileViews > 0 ? round(($totalLinkTaps / $profileViews) * 100) : 0;

    //     // Prepare data for the chart
    //     $chartData = [
    //         'labels' => ['Taps', 'Link Taps', 'New Connections', 'Tap Through Rate'],
    //         'datasets' => [
    //             [
    //                 'label' => 'Profile Analytics',
    //                 'data' => [
    //                     $profileViews,
    //                     $totalLinkTaps,
    //                     $newConnections,
    //                     $tapThroughRate
    //                 ],
    //                 'backgroundColor' => [
    //                     '#36a2eb', // Color for Taps
    //                     '#ff6384', // Color for Link Taps
    //                     '#ffcd56', // Color for New Connections
    //                     '#4bc0c0'  // Color for Tap Through Rate
    //                 ]
    //             ]
    //         ]
    //     ];

    //     // Prepare platform-specific data for the chart
    //     $platformsChartData = [
    //         'labels' => $platforms->pluck('title'), // Get platform labels for the chart
    //         'datasets' => [
    //             [
    //                 'label' => 'Platform Clicks',
    //                 'data' => $platforms->pluck('clicks'), // Get the number of clicks for each platform
    //                 'backgroundColor' => '#42A5F5',
    //             ]
    //         ]
    //     ];

    //     // Return response with chart data and platform information
    //     return response()->json([
    //         'ProfileAnalytics' => [
    //             [
    //                 'label' => 'Taps',
    //                 'clicks' => $profileViews,
    //                 'text' => 'The total number of times your GOtaps digital business card is viewed. This can be via a GOtaps device, your GOtaps QR code, or even a tap of your link in a bio',
    //             ],
    //             [
    //                 'label' => 'Link Taps',
    //                 'clicks' => $totalLinkTaps,
    //                 'text' => 'The total number of times one of your links was tapped. Sharing with GOtaps Direct also counts as a tap for that single link shared directly.',
    //             ],
    //             [
    //                 'label' => 'New Connections',
    //                 'clicks' => $newConnections,
    //                 'text' => 'The total number of new connections that you gained by scanning a business card, having someone tap "Save Contact" on your profile, or by meeting another GOtaps user',
    //             ],
    //             [
    //                 'label' => 'Tap Through Rate',
    //                 'clicks' => $tapThroughRate,
    //                 'text' => 'Your ratio of taps to views. Out of the total views you had on your digital business card, how many of those people actually tapped a link. This is important for maximizing your conversions and engagement.',
    //             ]
    //         ],
    //         'platforms' => $platforms,
    //         'chartData' => $chartData, // Include the general analytics chart data
    //         'platformsChartData' => $platformsChartData // Include the platform clicks chart data
    //     ]);
    // }

}
