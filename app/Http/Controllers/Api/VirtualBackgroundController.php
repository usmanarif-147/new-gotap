<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VirtualBackgroundController extends Controller
{
    public function getBackground($profileId)
    {
        $profile = Profile::find($profileId);
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        return response()->json([
            'virtual_background' => $profile->virtual_background ? Storage::url($profile->virtual_background) : null,
            'virtual_background_enabled' => $profile->virtual_background_enabled,
        ]);
    }

    public function updateBackground(Request $request, $profileId)
    {
        $profile = Profile::find($profileId);
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $request->validate([
            'virtual_background' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'virtual_background_enabled' => 'boolean',
        ]);

        $data = [
            'virtual_background_enabled' => $request->virtual_background_enabled,
        ];

        if ($request->hasFile('virtual_background')) {
            // Delete old background if exists
            if ($profile->virtual_background) {
                Storage::disk('public')->delete($profile->virtual_background);
            }
            
            // Store new background
            $data['virtual_background'] = Storage::disk('public')->put('uploads/virtual-backgrounds', $request->file('virtual_background'));
        }

        $profile->update($data);

        return response()->json([
            'message' => 'Virtual background updated successfully',
            'virtual_background' => $profile->virtual_background ? Storage::url($profile->virtual_background) : null,
            'virtual_background_enabled' => $profile->virtual_background_enabled,
        ]);
    }

    public function deleteBackground($profileId)
    {
        $profile = Profile::find($profileId);
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        // Delete file from storage
        if ($profile->virtual_background) {
            Storage::disk('public')->delete($profile->virtual_background);
        }
        
        $profile->update([
            'virtual_background' => null,
            'virtual_background_enabled' => false
        ]);

        return response()->json([
            'message' => 'Virtual background deleted successfully'
        ]);
    }
} 