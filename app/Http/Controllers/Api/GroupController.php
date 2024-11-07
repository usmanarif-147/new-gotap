<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Group\AddGroupRequest;
use App\Http\Requests\Api\Group\ContactInGroupRequest;
use App\Http\Requests\Api\Group\LeadInGroupRequest;
use App\Http\Requests\Api\Group\UserInGroupRequest;
use App\Http\Requests\Api\Group\GroupDetailsRequest;
use App\Http\Requests\Api\Group\UpdateGroupRequest;
use App\Http\Resources\Api\ContactResource;
use App\Http\Resources\Api\GroupResource;
use App\Http\Resources\Api\LeadResource;
use App\Http\Resources\Api\UserProfileResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Group;
use App\Models\PhoneContact;
use App\Models\Profile;
use App\Models\User;
use App\Services\GroupService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::where('groups.user_id', auth()->id())
            ->get();

        return response()->json(
            [
                'message' => "All Groups",
                'data' => GroupResource::collection($groups)
            ]
        );
    }

    public function group(GroupDetailsRequest $request)
    {
        $group = Group::where('id', $request->group_id)
            ->where('user_id', auth()->id())
            ->first();
        if (!$group) {
            return response()->json(['message' => trans('backend.group_not_found')]);
        }

        $groupContacts = Group::select(
            'contacts.*'
        )
            ->join('group_contacts', 'group_contacts.group_id', 'groups.id')
            ->join('phone_contacts as contacts', 'contacts.id', 'group_contacts.contact_id')
            ->where('groups.id', $request->group_id)
            ->where('groups.user_id', auth()->id())
            ->get();

        $groupProfiles = Group::select(
            'profiles.id',
            'profiles.email',
            'profiles.name',
            'profiles.username',
            'profiles.photo',
            'profiles.phone'
        )
            ->join('user_groups', 'user_groups.group_id', 'groups.id')
            ->join('profiles', 'profiles.id', 'user_groups.profile_id')
            ->where('groups.id', $request->group_id)
            ->where('groups.user_id', auth()->id())
            ->get();

        $groupLeads = Group::select('Leads.*')
            ->join('group_leads', 'group_leads.group_id', 'groups.id')
            ->join('leads', 'leads.id', 'group_leads.lead_id')
            ->where('groups.id', $request->group_id)
            ->where('groups.user_id', auth()->id())
            ->get();

        return response()->json(
            [
                'data' =>
                    [
                        'group' => new GroupResource($group),
                        'group_profiles' => $groupProfiles,
                        'group_contacts' => ContactResource::collection($groupContacts),
                        'group_leads' => LeadResource::collection($groupLeads),
                    ]
            ]
        );
    }

    public function addGroup(AddGroupRequest $request)
    {
        $isExist = Group::where('user_id', auth()->id())
            ->where('title', $request->title)
            ->first();
        if ($isExist) {
            return response()->json([
                'message' => trans('backend.group_already_exist')
            ]);
        }

        Group::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'icon' => $request->icon,
            'active' => $request->active ?? 0,
        ]);
        return response()->json([

            'message' => trans('backend.group_created_success')
        ]);
    }

    public function updateGroup(UpdateGroupRequest $request)
    {
        $group = Group::where('id', $request->group_id)->where('user_id', auth()->id())->first();

        if (!$group) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        Group::where('id', $request->group_id)->where('user_id', auth()->id())->update([
            'title' => $request->title,
            'icon' => $request->icon,
            'active' => $request->active ?? 0,
        ]);

        $group = Group::where('id', $request->group_id)->where('user_id', auth()->id())->first();
        return response()->json([
            'message' => trans('backend.group_updated_success'),
            'data' => new GroupResource($group)
        ]);
    }

    public function deleteGroup(GroupDetailsRequest $request)
    {
        $group = Group::where('id', $request->group_id)->where('user_id', auth()->id())->first();
        if (!$group) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        // remove all data related to group
        try {
            DB::table('group_contacts')->where('group_id', $request->group_id)->delete();
            DB::table('group_leads')->where('group_id', $request->group_id)->delete();
            DB::table('user_groups')->where('group_id', $request->group_id)->delete();
            Group::where('user_id', auth()->id())->where('id', $request->group_id)->delete();
            return response()->json([

                'message' => trans('backend.group_removed')
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }


    /**
     * Add user in group
     */
    public function addProfileIntoGroup(UserInGroupRequest $request)
    {
        // check if it is logged in user's profile
        $isLoggedInUserProfile = Profile::where('user_id', auth()->id())
            ->where('id', $request->profile_id)
            ->exists();
        if ($isLoggedInUserProfile) {
            return response()->json([
                'message' => trans('backend.own_group_error')
            ]);
        }

        // check group exists
        $isGroupExist = Group::where('id', $request->group_id)
            ->where('user_id', auth()->id())
            ->exists();
        if (!$isGroupExist) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        // check profile exists
        $isProflieExist = Profile::where('id', $request->profile_id)->exists();
        if (!$isProflieExist) {
            return response()->json([
                'message' => trans('backend.connection_not_found')
            ]);
        }

        // check if profile connected to user
        $isConnectionExist = DB::table('connects')
            ->where('connected_id', $request->profile_id)
            ->where('connecting_id', auth()->id())
            ->exists();
        if (!$isConnectionExist) {
            return response()->json([
                'message' => trans('Profile does not belongs to the User')
            ]);
        }

        // check profile is already exist in group
        $isProfileExistInGroup = DB::table('user_groups')
            ->where('profile_id', $request->profile_id)
            ->where('group_id', $request->group_id)
            ->first();
        if ($isProfileExistInGroup) {
            return response()->json([
                'message' => trans('backend.user_already_exist')
            ]);
        }

        // add profile in group
        try {
            DB::table('user_groups')->insert([
                'profile_id' => $request->profile_id,
                'group_id' => $request->group_id,
                'created_at' => now()
            ]);

            Group::where('user_id', auth()->id())
                ->where('id', $request->group_id)
                ->increment('total_profiles');

            return response()->json([
                'message' => trans(
                    'backend.user_added_success'
                ),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Remove user from group
     */
    public function removeProfileFromGroup(UserInGroupRequest $request)
    {
        // check group belongs to the user
        $group = Group::where('user_id', auth()->id())
            ->where('id', $request->group_id)
            ->first();
        if (!$group) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        // check user belongs to this group
        $userInGroup = DB::table('user_groups')
            ->where('profile_id', $request->profile_id)
            ->where('group_id', $request->group_id)
            ->first();
        if (!$userInGroup) {
            return response()->json([
                'message' => trans('backend.cannot_delete_user')
            ]);
        }

        // remove profile from group
        try {
            DB::table('user_groups')
                ->where('profile_id', $request->profile_id)
                ->where('group_id', $request->group_id)
                ->delete();

            Group::where('user_id', auth()->id())
                ->where('id', $request->group_id)
                ->decrement('total_profiles');

            return response()->json([
                'message' => trans('backend.user_removed_from_group')
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Add contact in group
     */
    public function addPhoneContactIntoGroup(ContactInGroupRequest $request)
    {
        $contact = DB::table('phone_contacts')
            ->where('id', $request->contact_id)
            ->first();

        // is contact exist
        if (!$contact) {
            return response()->json([
                'message' => trans('backend.contact_not_found')
            ]);
        }

        // Is phone contact belongs to the logged in user
        $isBelongsToLoggedInUser = PhoneContact::where('id', $request->contact_id)
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isBelongsToLoggedInUser) {
            return response()->json([
                'message' => 'Please enter valid phone contact'
            ]);
        }

        // is group belongs to the logged in user
        $group = Group::where('user_id', auth()->id())
            ->where('id', $request->group_id)
            ->first();
        if (!$group) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        // check contact is already exist into the group
        $checkContactInGroup = DB::table('group_contacts')
            ->where('contact_id', $request->contact_id)
            ->where('group_id', $request->group_id)
            ->first();

        if ($checkContactInGroup) {
            return response()->json([
                'message' => trans('backend.contact_already_exist')
            ]);
        }

        // insert record into the group_contacts
        try {
            DB::table('group_contacts')->insert([
                'contact_id' => $request->contact_id,
                'group_id' => $request->group_id,
                'created_at' => now()
            ]);

            Group::where('user_id', auth()->id())->where('id', $request->group_id)->increment('total_contacts');
            return response()->json([

                'message' => trans('backend.contact_added_success'),
                'data' => new ContactResource($contact)
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Remove contact from group
     */
    public function removePhoneContactFromGroup(Request $request)
    {
        // check group belongs to the user
        $group = Group::where('user_id', auth()->id())
            ->where('id', $request->group_id)
            ->first();
        if (!$group) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        // check contact belongs to this group
        $contactInGroup = DB::table('group_contacts')
            ->where('contact_id', $request->contact_id)
            ->where('group_id', $request->group_id)
            ->first();
        if (!$contactInGroup) {
            return response()->json([
                'message' => trans('backend.cannot_delete_contact')
            ]);
        }

        // remove contact from group
        try {
            DB::table('group_contacts')
                ->where('contact_id', $request->contact_id)
                ->where('group_id', $request->group_id)
                ->delete();

            Group::where('user_id', auth()->id())
                ->where('id', $request->group_id)
                ->decrement('total_contacts');
            return response()->json([

                'message' => trans('backend.contact_removed_success')
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Add lead in group
     */
    public function addLeadIntoGroup(LeadInGroupRequest $request)
    {
        $lead = DB::table('leads')
            ->where('id', $request->lead_id)
            ->first();

        // is contact exist
        if (!$lead) {
            return response()->json([
                'message' => 'Lead Not Found',
            ]);
        }

        $profile = getActiveProfile();

        // Is phone contact belongs to the logged in user
        $isBelongsToLoggedInUser = DB::table('leads')->where('id', $request->lead_id)
            ->where('employee_id', auth()->id())
            ->where('viewing_id', $profile->id)
            ->exists();

        if (!$isBelongsToLoggedInUser) {
            return response()->json([
                'message' => 'Please enter valid Lead Id'
            ]);
        }

        // is group belongs to the logged in user
        $group = Group::where('user_id', auth()->id())
            ->where('id', $request->group_id)
            ->first();
        if (!$group) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        // check contact is already exist into the group
        $checkContactInGroup = DB::table('group_leads')
            ->where('lead_id', $request->lead_id)
            ->where('group_id', $request->group_id)
            ->first();

        if ($checkContactInGroup) {
            return response()->json([
                'message' => 'Lead Already Exist In Group',
            ]);
        }

        // insert record into the group_contacts
        try {
            DB::table('group_leads')->insert([
                'lead_id' => $request->lead_id,
                'group_id' => $request->group_id,
                'created_at' => now()
            ]);

            Group::where('user_id', auth()->id())->where('id', $request->group_id)->increment('total_leads');
            return response()->json([

                'message' => 'Lead Added Into Group Successfully',
                'data' => new LeadResource($lead)
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Remove contact from group
     */
    public function removeLeadFromGroup(LeadInGroupRequest $request)
    {
        // check group belongs to the user
        $group = Group::where('user_id', auth()->id())
            ->where('id', $request->group_id)
            ->first();
        if (!$group) {
            return response()->json([
                'message' => trans('backend.group_not_found')
            ]);
        }

        // check contact belongs to this group
        $leadInGroup = DB::table('group_leads')
            ->where('lead_id', $request->lead_id)
            ->where('group_id', $request->group_id)
            ->first();
        if (!$leadInGroup) {
            return response()->json([
                'message' => 'Can Not Delete Lead From Group'
            ]);
        }

        // remove contact from group
        try {
            DB::table('group_leads')
                ->where('lead_id', $request->lead_id)
                ->where('group_id', $request->group_id)
                ->delete();

            Group::where('user_id', auth()->id())
                ->where('id', $request->group_id)
                ->decrement('total_leads');
            return response()->json([

                'message' => 'Lead Remove Successfully From Group',
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function groupDetail($id)
    {
        // Check if the group exists
        $groupExist = Group::find($id);

        if ($groupExist) {
            $users = DB::table('user_groups')
                ->select('users.*')
                ->join('users', 'users.id', '=', 'user_groups.profile_id')
                ->where('user_groups.group_id', $id)
                ->where('users.status', 1)
                ->get();

            $contacts = DB::table('group_contacts')
                ->select('phone_contacts.*')
                ->join('phone_contacts', 'phone_contacts.id', '=', 'group_contacts.contact_id')
                ->where('group_contacts.group_id', $id)
                ->get();
            $leads = DB::table('group_leads')
                ->select(
                    'leads.id',
                    'leads.name',
                    'leads.email',
                    'leads.phone',
                    'leads.note',
                    'leads.country',
                    'leads.state',
                    'leads.city',
                )
                ->join('leads', 'leads.id', '=', 'group_leads.lead_id')
                ->where('group_leads.group_id', $id)
                ->get();

            return response()->json([

                'message' => 'Group Detail with user , Lead and contact details',
                'users' => $users,
                'contacts' => $contacts,
                'leads' => $leads
            ], 200);
        }

        return response()->json([
            'message' => 'Requested Group does not exist',
            'data' => []
        ], 404);
    }
}