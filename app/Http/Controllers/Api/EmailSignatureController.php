<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailSignatureController extends Controller
{
    public function getSignature($profileId)
    {
        $profile = Profile::find($profileId);
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        return response()->json([
            'email_signature' => $profile->email_signature,
            'email_signature_enabled' => $profile->email_signature_enabled,
        ]);
    }

    public function updateSignature(Request $request, $profileId)
    {
        $profile = Profile::find($profileId);
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $request->validate([
            'email_signature' => 'nullable|string',
            'email_signature_enabled' => 'boolean',
        ]);

        $profile->update([
            'email_signature' => $request->email_signature,
            'email_signature_enabled' => $request->email_signature_enabled,
        ]);

        return response()->json([
            'message' => 'Email signature updated successfully',
            'email_signature' => $profile->email_signature,
            'email_signature_enabled' => $profile->email_signature_enabled,
        ]);
    }
} 