<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use DB;
use JeroenDesloovere\VCard\VCard;
use Response;
use Illuminate\Support\Facades\Storage;

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
        $lead = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.note',
            'leads.country',
            'leads.state',
            'leads.city',
            'leads.viewer_id',
            'leads.created_at',
            DB::raw('COALESCE(profiles.work_position, "") as viewer_work_position'),
            DB::raw('COALESCE(profiles.job_title, "") as viewer_job_title'),
            DB::raw('COALESCE(profiles.company, "") as viewer_company'),
            DB::raw('COALESCE(profiles.address, "") as viewer_address'),
            DB::raw('COALESCE(profiles.photo, "") as viewer_photo'),
        )
            // Join profiles on viewer_id
            ->leftJoin('profiles', 'leads.viewer_id', '=', 'profiles.id')
            ->where('leads.id', $id)->first();
        // Create a new vCard
        $vcard = new VCard();
        $vcard->addName($lead->name);
        $vcard->addEmail($lead->email);
        $vcard->addPhoneNumber($lead->phone);
        if ($lead->viewer_company) {
            $vcard->addCompany($lead->viewer_company);
        }
        if ($lead->viewer_job_title) {
            $vcard->addJobtitle($lead->viewer_job_title);
        }
        if ($lead->viewer_work_position) {
            $vcard->addRole($lead->viewer_work_position);
        }
        if ($lead->viewer_address) {
            $vcard->addAddress(null, null, '', '', null, $lead->viewer_address, null);
            // $vcard->addAddress($lead->viewer_address);
        } elseif ($lead->country) {
            $vcard->addAddress(null, null, null, $lead->city, $lead->state, $lead->country, '');
        }
        $photoPath = $lead->viewer_photo && Storage::disk('public')->exists($lead->viewer_photo)
            ? public_path('storage/' . $lead->viewer_photo)
            : public_path('user.png');
        $vcard->addPhoto($photoPath);

        // Generate filename
        $filename = $lead->name . '_contact.vcf';

        return Response::make($vcard->getOutput(), 200, [
            'Content-Type' => 'text/vcard',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
