<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailRead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmailTrackingController extends Controller
{
    public function track($recipientEmail, $compaignId)
    {
        try {
            $recipientEmail = urldecode($recipientEmail);

            // Update or create the record
            $tracking = EmailRead::updateOrCreate(
                [
                    'compaign_id' => $compaignId,
                    'email' => $recipientEmail
                ],
                [
                    'read_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );

            // Return transparent 1x1 pixel
            return response(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='))
                ->header('Content-Type', 'image/gif')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            Log::error("Email tracking failed: " . $e->getMessage());
            // Still return the pixel even if tracking fails
            return response(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='))
                ->header('Content-Type', 'image/gif');
        }
    }
}
