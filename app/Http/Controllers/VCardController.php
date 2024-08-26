<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use JeroenDesloovere\VCard\VCard;

class VCardController extends Controller
{
    public function saveContact($id)
    {
        $profile = Profile::where('id', $id)->first();
        $vcard = new VCard();

        $vcard->addName('', $profile->name);
        $vcard->addEmail($profile->email);
        $vcard->addPhoneNumber($profile->phone, 'WORK');
        $vcard->addCompany($profile->company);
        $vcard->addJobtitle($profile->job_title);


        return response()->make(
            $vcard->getOutput(),
            200,
            $vcard->getHeaders(true)
        );
    }
}
