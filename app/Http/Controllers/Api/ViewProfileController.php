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

            $checkCard = DB::table('user_cards')
                ->select(
                    'user_cards.user_id',
                    'user_cards.profile_id'
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
            User::where('id', $res['profile']->user_id)->increment('tiks');
        }

        $profile = Profile::where('id', $res['profile']->id)->first();


        return response()->json([
            'message' => 'User profile',
            'profile' => new UserProfileResource($profile),
            'is_connected' => $is_connected
        ]);
    }
}
