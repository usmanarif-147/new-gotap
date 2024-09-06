<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PhoneContact\AddPhoneContactRequest;
use App\Http\Requests\Api\PhoneContact\ContactDetailsRequest;
use App\Http\Requests\Api\PhoneContact\UpdatePhoneContactRequest;
use App\Http\Resources\Api\ContactResource;
use App\Models\Group;
use App\Models\PhoneContact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PhoneContactController extends Controller
{

    /**
     * All Contact List
     */
    public function index()
    {

        $contacts = DB::table('phone_contacts')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(
            [
                "message" => "Phone Contacts",
                'data' => ContactResource::collection($contacts)
            ],

        );
    }

    /**
     * Contact Details
     */
    public function phoneContact(ContactDetailsRequest $request)
    {
        $contact_id = $request->contact_id;
        // get phone contact
        $contact = DB::table('phone_contacts')
            ->where('user_id', auth()->id())
            ->where('id', $contact_id)
            ->first();

        if (!$contact) {
            return response()->json([
                'message' => trans('backend.phone_contact_not_found')
            ]);
        }

        return response()->json([

            'message' => 'Phone Contact',
            'data' => new ContactResource($contact)
        ]);
    }

    /**
     * Add Contact
     */
    public function addPhoneContact(AddPhoneContactRequest $request)
    {
        $photo = null;

        if ($request->hasFile('photo')) {
            $image = $request->photo;
            $photo = Storage::disk('public')->put('uploads/photos', $image);
        }

        DB::table('phone_contacts')->insert([
            'user_id' => auth()->id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $photo,
            'work_email' => $request->work_email,
            'work_phone' => $request->work_phone,
            'website' => $request->website,
            'company_name' => $request->company_name,
            'address' => $request->address
        ]);

        $data = DB::table('phone_contacts')
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->first();

        return response()->json(
            [
                'message' => trans('backend.phone_conact_added_success'),
                'data' => new ContactResource($data),
            ]
        );
    }

    /**
     * Update Contact
     */
    public function updatePhoneContact(UpdatePhoneContactRequest $request)
    {
        $contact = DB::table('phone_contacts')
            ->where('id', $request->contact_id)
            ->first();
        if (!$contact) {
            return response()->json(
                [
                    'message' => trans('backend.phone_contact_not_found')
                ]
            );
        }

        try {
            $photo = $contact->photo;
            if ($request->hasFile('photo')) {
                if ($photo) {
                    Storage::disk('public')->delete($photo);
                }
                $photo = Storage::disk('public')->put('uploads/photos', $request->photo);
            }

            DB::table('phone_contacts')->where('id', $request->contact_id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'photo' => $photo,
                'work_email' => $request->work_email,
                'work_phone' => $request->work_phone,
                'website' => $request->website,
                'company_name' => $request->company_name,
                'address' => $request->address
            ]);

            $contact = DB::table('phone_contacts')->where('id', $request->contact_id)->first();
            return response()->json(
                [
                    'message' => trans('backend.phone_contact_updated_success'),
                    'data' => new ContactResource($contact)
                ]
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' => $ex->getMessage()
                ]
            );
        }
    }

    /**
     * Delete Phone Contact
     */
    public function deletePhoneContact(ContactDetailsRequest $request)
    {
        $contact_id = $request->contact_id;

        // check phone contact belongs to logged in user
        $phoneContact = PhoneContact::where('id', $contact_id)
            ->where('user_id', auth()->id())
            ->first();
        if (!$phoneContact) {
            return response()->json([
                'message' => 'Phone Contact Not Found'
            ], 404);
        }

        try {

            DB::transaction(function () use ($phoneContact) {

                // delete phone contact all logged in user's all groups
                $this->removePhoneContactFromGroups($phoneContact->id);

                // delete phone contact images
                if ($phoneContact->photo && Storage::disk('public')->exists($phoneContact->photo)) {
                    Storage::disk('public')->delete($phoneContact->photo);
                }

                // delete phone contact
                $phoneContact->delete();
            });

            return response()->json([
                'message' => 'Phone Contact deleted successfully',
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    private function removePhoneContactFromGroups($contactId)
    {

        // Get all groups that contain the contact and decrement total_contacts in one query
        $groups = DB::table('group_contacts')
            ->where('contact_id', $contactId)
            ->pluck('group_id');

        if ($groups->isNotEmpty()) {
            // Decrement total_contacts for all groups
            Group::whereIn('id', $groups)->decrement('total_contacts');
        }

        // Delete the contact from group_contacts
        DB::table('group_contacts')->where('contact_id', $contactId)->delete();
    }
}
