<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\ViewProfileRequest;
use App\Http\Resources\Api\UserProfileResource;
use App\Models\Card;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ViewProfileController extends Controller
{

    public function viewUserProfile(ViewProfileRequest $request)
    {
        $res['profile'] = null;

        $card = Card::where('uuid', $request->search_profile_by)->first();
        if (!$card) {
            $res['profile'] = Profile::where('username', $request->search_profile_by)->first();
            if (!$res['profile']) {
                $res['profile'] = Profile::where('id', $request->search_profile_by)->first();
            }

            if (!$res['profile']) {
                return response()->json(['message' => 'Profile not found']);
            }
        }
        if ($card) {
            if (!$card->status) {
                return response()->json(['message' => trans('backend.card_inactive')]);
            }

            $checkCard = DB::table('profile_cards')
                ->select(
                    'profile_cards.user_id',
                    'profile_cards.profile_id'
                )
                ->where('card_id', $card->id)
                ->where('status', 1)
                ->first();
            if (!$checkCard) {
                return response()->json(['message' => trans('backend.profile_not_accessible')]);
            }

            $res['profile'] = Profile::where('id', $checkCard->profile_id)->first();
        }

        $is_connected = 0;
        $connected = DB::table('connects')
            ->where('connecting_id', auth()->id())
            ->where('connected_id', $res['profile']->id)
            ->first();
        if ($connected) {
            $is_connected = 1;
        }
        $isLoggedInUserProfile = Profile::where('id', $res['profile']->id)
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isLoggedInUserProfile) {
            Profile::where('id', $res['profile']->id)->increment('tiks');
            // $checkLeadsEnabled = $res['profile']->is_leads_enabled;
            $profile = Profile::where('user_id', auth()->id())->where('is_default', 1)->first();
            User::where('id', $res['profile']->enterprise_id)->increment('tiks');
            User::where('id', $res['profile']->user_id)->increment('tiks');

            if ($res['profile']->type == 'enterprise') {
                DB::table('leads')->insert([
                    'enterprise_id' => $res['profile']->enterprise_id,
                    'employee_id' => $res['profile']->user_id,
                    'viewing_id' => $res['profile']->id,
                    'viewer_id' => $profile->id,
                    'name' => $profile->name,
                    'email' => $profile->email,
                    'phone' => $profile->phone,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // if ($checkLeadsEnabled) {
            //     $connectedProfile = DB::table('connects')
            //         ->where('connecting_id', $res['profile']->user_id)
            //         ->where('connected_id', getActiveProfile()->id)
            //         ->first();

            //     if (!$connectedProfile) {
            //         DB::table('leads')->insert([
            //             'viewing_user_id' => $res['profile']->user_id,
            //             'viewing_profile_id' => $res['profile']->id,
            //             'viewer_profile_id' => $profile->id,
            //             'name' => $profile->name,
            //             'email' => $profile->email,
            //             'phone' => $profile->phone,
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ]);
            //     }
            // }
        }

        $profile = Profile::where('id', $res['profile']->id)->first();


        return response()->json([
            'message' => 'User profile',
            'profile' => new UserProfileResource($profile),
            'is_connected' => $is_connected
        ]);
    }

    public function profileLeadsEnabled()
    {
        $status = getActiveProfile()->is_leads_enabled ? 'Disable' : 'Enable';
        Profile::where('id', getActiveProfile()->id)->update([
            'is_leads_enabled' => getActiveProfile()->is_leads_enabled ? 0 : 1,
        ]);
        return response()->json([
            'message' => 'Leads ' . $status . ' Succsessfully',
        ]);
    }

    public function profileLeads()
    {
        $leads = DB::table('leads')
            ->select(
                'profiles.id as profile_id',
                'leads.name',
                'leads.email',
                'leads.phone',
                'profiles.work_position',
                'profiles.job_title',
                'profiles.company',
                'profiles.address',
                'profiles.photo',
                'leads.created_at as created_date'
            )
            ->leftJoin('profiles', 'leads.viewer_id', '=', 'profiles.id')
            ->where('leads.employee_id', auth()->id())
            ->get();

        return response()->json([
            'message' => 'Profile Leads',
            'leads' => $leads,
        ]);
    }
}
