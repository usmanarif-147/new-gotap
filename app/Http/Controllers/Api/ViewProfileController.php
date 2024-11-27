<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\AddLeadRequest;
use App\Http\Requests\Api\Profile\DeleteLeadRequest;
use App\Http\Requests\Api\Profile\EnterpriseProfileRequest;
use App\Http\Requests\Api\Profile\UpdateProfileLeadRequest;
use App\Http\Requests\Api\Profile\ViewProfileRequest;
use App\Http\Resources\Api\UserProfileResource;
use App\Models\Card;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserRequestProfile;
use Illuminate\Support\Facades\DB;
use Request;

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
            $checkLeadsEnabled = $res['profile']->is_leads_enabled;
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
            } else {
                if ($checkLeadsEnabled) {
                    if (!$is_connected) {
                        $checkEntry = DB::table('leads')->where('employee_id', $res['profile']->user_id)
                            ->where('viewing_id', $res['profile']->id)->where('viewer_id', $profile->id)->first();
                        if ($checkEntry) {
                            DB::table('leads')->update([
                                'created_at' => now(),
                            ]);
                        } else {
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
                    }
                }
            }
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
            'is_lead_enabled' => getActiveProfile()->is_leads_enabled,
        ]);
    }

    public function profileLeads()
    {
        $leads = DB::table('leads')
            ->select(
                'leads.id',
                'profiles.id as profile_id',
                'leads.name',
                'leads.email',
                'leads.phone',
                'leads.note',
                'leads.country',
                'leads.state',
                'leads.city',
                'leads.latitude',
                'leads.longitude',
                'profiles.work_position',
                'profiles.job_title',
                'profiles.company',
                'profiles.address',
                'profiles.photo',
                'leads.created_at as created_date'
            )
            ->leftJoin('profiles', 'leads.viewer_id', '=', 'profiles.id')
            ->where('leads.employee_id', auth()->id())
            ->where('leads.viewing_id', getActiveProfile()->id)
            ->get();

        return response()->json([
            'message' => 'Profile Leads',
            'leads' => $leads,
        ]);
    }

    public function updateProfileLead(UpdateProfileLeadRequest $request)
    {
        $lead = DB::table('leads')->find($request->id);
        if (!$lead) {
            return response()->json([
                'message' => 'Lead not Found',
            ], 404);
        }
        DB::table('leads')
            ->where('id', $request->id)->update([
                    'name' => $request->name,
                    'note' => $request->note,
                ]);
        $updatedLead = DB::table('leads')
            ->select(
                'leads.id',
                'profiles.id as profile_id',
                'leads.name',
                'leads.email',
                'leads.phone',
                'leads.note',
                'leads.country',
                'leads.state',
                'leads.city',
                'leads.latitude',
                'leads.longitude',
                'profiles.work_position',
                'profiles.job_title',
                'profiles.company',
                'profiles.address',
                'profiles.photo',
                'leads.created_at as created_date'
            )
            ->leftJoin('profiles', 'leads.viewer_id', '=', 'profiles.id')
            ->where('leads.id', $request->id)
            ->first();
        return response()->json([
            'message' => 'Lead Update Successfully!',
            'lead' => $updatedLead,
        ]);
    }

    public function addLead(AddLeadRequest $request)
    {
        $Profile = getActiveProfile();
        if (!$Profile) {
            return response()->json([
                'message' => 'Your Profile is not Active',
            ], 404);
        }
        DB::table('leads')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'note' => $request->note,
            'viewing_id' => $Profile->id,
            'employee_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json([
            'message' => 'Lead created Successfully!',
        ]);
    }

    public function deleteLead(DeleteLeadRequest $request)
    {
        $lead = DB::table('leads')->find($request->id);
        if (!$lead) {
            return response()->json([
                'message' => 'Lead not Found',
            ], 404);
        }
        DB::table('leads')->where('id', $request->id)->delete();
        return response()->json([
            'message' => 'Lead Deleted Successfully!',
        ]);
    }

    public function UserRequestProfile(EnterpriseProfileRequest $request)
    {
        $enterprise = User::where('email', $request->email)->first();
        if (!$enterprise) {
            return response()->json([
                'message' => 'Enterpriser not Found',
            ], 404);
        }

        $user = User::find(auth()->id());
        if (!$user) {
            return response()->json([
                'message' => 'User not Found',
            ], 404);
        }

        $profile = Profile::where('user_id', $user->id)->where('enterprise_id', $enterprise->id)->first();
        if ($profile) {
            return response()->json([
                'message' => 'You Already Have Profile From This Company',
            ], 200);
        }
        $existingRequest = UserRequestProfile::where('user_id', $user->id)
            ->where('enterprise_id', $enterprise->id)
            ->whereIn('status', [0, 1])
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'Request already exists',
            ], 200);
        }
        UserRequestProfile::create([
            'user_id' => $user->id,
            'enterprise_id' => $enterprise->id,
            'status' => 0,
        ]);

        return response()->json([
            'message' => 'Request successfully added',
        ], 201);
    }
}
