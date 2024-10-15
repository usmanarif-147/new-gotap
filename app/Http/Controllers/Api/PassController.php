<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use PKPass\PKPass;

class PassController extends Controller
{
    public function generatePass($id)
    {

        $user = User::findOrFail($id);

        $pass = new PKPass(config('wallet.pkpass.certificate_path'), config('wallet.pkpass.certificate_password'));

        // Pass content
        $pass->setData('{
            "passTypeIdentifier": "' . config('wallet.pkpass.passTypeIdentifier') . '",
            "formatVersion": 1,
            "organizationName": "daap",
            "teamIdentifier": "' . config('wallet.pkpass.teamIdentifier') . '",
            "serialNumber": "' . $user->id . '",
            "backgroundColor": "rgb(22,105,122)",
            "logoText": "daap",
            "description": "daap pass",

            "generic" : {
                "headerFields" : [
                    {
                    "key" : "staffNumber",
                      "label" : "Username",
                      "value" : "' . $user->username . '"
                    }
                ],
              "primaryFields" : [
                {
                  "key" : "staffName",
                  "label" : "Name",
                  "value" : "' . ($user->first_name . ' ' . $user->last_name) . '"
                }
              ],
              "secondaryFields" : [
                {
                  "key" : "telephoneExt",
                  "label" : "Company",
                  "value" : "' . ($user->company ?? 'N/A') . '"
                },
                {
                  "key" : "jobTitle",
                  "label" : "Job Title",
                  "value" : "' . ($user->job_title ?? 'N/A') . '"
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
                "message": "' . str_replace('api.', '', request()->getSchemeAndHttpHost()) . '/p/' . ($user->username ?: $user->id) . '",
                "messageEncoding": "iso-8859-1",
                "altText": "' . $user->username . '"
            }
            }');




        // Add files to the pass package
        $pass->addFile('assets/icon.png');
        $pass->addFile('assets/logo.png');

        if ($user->photo && file_exists(public_path('storage/' . $user->photo))) {
            copy(public_path('storage/' . $user->photo), public_path('assets/thumbnail.png'));
        } else {
            copy(public_path('avatar.png'), public_path('assets/thumbnail.png'));
        }

        $pass->addFile('assets/thumbnail.png');

        // Create and output the pass
        $passFilePath = $pass->create(true);
    }
}
