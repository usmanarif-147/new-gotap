<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PhoneContact\AddPhoneContactRequest;
use App\Http\Requests\Api\PhoneContact\ContactDetailsRequest;
use App\Http\Requests\Api\PhoneContact\UpdatePhoneContactRequest;
use App\Http\Resources\Api\ContactResource;
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
    public function add(AddPhoneContactRequest $request)
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
    public function update(UpdatePhoneContactRequest $request)
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
     * Remove Contact
     */
    public function remove(ContactDetailsRequest $request)
    {
        $contact_id = $request->contact_id;
        $platform = DB::table('phone_contacts')
            ->where('user_id', auth()->id())
            ->where('id', $contact_id)
            ->delete();
        if (!$platform) {
            return response()->json(
                [
                    'message' => trans('backend.phone_contact_not_found')
                ],
            );
        }

        return response()->json(
            [
                'message' => trans('backend.phone_contact_deleted_success')
            ],

        );
    }
}
