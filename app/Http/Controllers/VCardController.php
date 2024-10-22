<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use DB;
use JeroenDesloovere\VCard\VCard;
use Response;

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

    public function downloadVCard($id)
    {
        $lead = DB::table('leads')->find($id);
        // Create a new vCard
        $vcard = new VCard();
        $vcard->addName($lead->name);
        $vcard->addEmail($lead->email);
        $vcard->addPhoneNumber($lead->phone);

        // Generate filename
        $filename = $lead->name . '_contact.vcf';

        return Response::make($vcard->getOutput(), 200, [
            'Content-Type' => 'text/vcard',
            'Content-Disposition' => "attachment; filename={$filename}.vcf",
        ]);

        // Save the vCard to a temporary location
        // $filePath = 'public/uploads/vcards/' . $filename;
        // Storage::disk('local')->put($filePath, $vcard->getOutput());

        // // Get the URL of the stored vCard
        // $this->downloadUrl = Storage::url($filePath);

        // // Emit event to trigger download in JavaScript
        // $this->dispatchBrowserEvent('triggerVCardDownload');
    }
}
