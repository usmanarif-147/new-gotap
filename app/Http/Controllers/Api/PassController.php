<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use PKPass\PKPass;

class PassController extends Controller
{
  public function generatePass($id)
  {

    $profile = Profile::findOrFail($id);

    $pass = new PKPass(config('wallet.pkpass.certificate_path'), config('wallet.pkpass.certificate_password'));

    // Pass content
    $pass->setData('{
            "passTypeIdentifier": "' . config('wallet.pkpass.passTypeIdentifier') . '",
            "formatVersion": 1,
            "organizationName": "Gotaps",
            "teamIdentifier": "' . config('wallet.pkpass.teamIdentifier') . '",
            "serialNumber": "' . $profile->id . '",
            "backgroundColor": "#fff",
            "logoText": "Gotaps",
            "description": "Gotaps pass",

            "generic" : {
                "headerFields" : [
                    {
                    "key" : "staffNumber",
                      "label" : "Username",
                      "value" : "' . $profile->username . '"
                    }
                ],
              "primaryFields" : [
                {
                  "key" : "staffName",
                  "label" : "Name",
                  "value" : "' . ($profile->name) . '"
                }
              ],
              "secondaryFields" : [
                {
                  "key" : "telephoneExt",
                  "label" : "Company",
                  "value" : "' . ($profile->company ?? 'N/A') . '"
                },
                {
                  "key" : "jobTitle",
                  "label" : "Job Title",
                  "value" : "' . ($profile->job_title ?? 'N/A') . '"
                }
              ],
              "auxiliaryFields" : [
                {
                  "key" : "expiryDate",
                  "dateStyle" : "PKDateStyleShort",
                  "label" : "Expiry Date",
                  "value" : "2027-12-31T00:00-23:59"
                }
              ]
            },

            "barcode": {
                "format": "PKBarcodeFormatQR",
                "message": "' . str_replace('api.', '', request()->getSchemeAndHttpHost()) . '/p/' . ($profile->username ?: $profile->id) . '",
                "messageEncoding": "iso-8859-1",
                "altText": "' . $profile->username . '"
            }
            }');




    // Add files to the pass package
    $pass->addFile('assets/icon.png');
    $pass->addFile('assets/logo.png');

    if ($profile->photo && file_exists(public_path('storage/' . $profile->photo))) {
      copy(public_path('storage/' . $profile->photo), public_path('assets/thumbnail.png'));
    } else {
      copy(public_path('avatar.png'), public_path('assets/thumbnail.png'));
    }

    $pass->addFile('assets/thumbnail.png');

    // Create and output the pass
    $passFilePath = $pass->create(true);
  }
}
