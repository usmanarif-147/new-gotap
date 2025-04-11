<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Admin;
use App\Models\Card;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // /**
    //  * Display the user's profile form.
    //  */
    // public function edit(Request $request): View
    // {
    //     return view('profile.edit', [
    //         'user' => $request->user(),
    //     ]);
    // }

    // /**
    //  * Update the user's profile information.
    //  */
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

    //     $request->user()->save();

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // }

    // /**
    //  * Delete the user's account.
    //  */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     $request->validateWithBag('userDeletion', [
    //         'password' => ['required', 'current-password'],
    //     ]);

    //     $user = $request->user();

    //     Auth::logout();

    //     $user->delete();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return Redirect::to('/');
    // }

    public function changePassword(Request $request)
    {
        try {
            User::where('id', auth()->id())->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json(['success' => 'Password Changed Successfully']);
        } catch (Exception $ex) {
            return response()->json(['success' => $ex->getMessage()]);
        }
    }

    public function exportCsv(Request $request)
    {
        $filteredData = Card::select(
            'cards.id',
            'cards.uuid',
            'cards.description',
            'cards.status',
            'cards.type',
            'profiles.username',
        )
            ->leftJoin('profile_cards', 'profile_cards.card_id', 'cards.id')
            ->leftJoin('profiles', 'profiles.id', 'profile_cards.profile_id')
            ->when($request->searchQuery, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('cards.uuid', 'like', "%{$request->searchQuery}%")
                        ->orWhere('profiles.username', 'like', "%{$request->searchQuery}%");
                });
            })
            ->when($request->filterByStatus, function ($query) use ($request) {
                if ($request->filterByStatus == 1) {
                    $query->where('cards.status', 1);
                }
                if ($request->filterByStatus == 2) {
                    $query->where('cards.status', 0);
                }
            })
            ->orderBy('cards.created_at', 'desc')
            ->get();

        $csvContent = "ID,UUID,Description,Status,Type,Username\n";

        foreach ($filteredData as $card) {
            $csvContent .= implode(',', [
                $card->id,
                $card->uuid,
                $card->description,
                $card->status == 1 ? 'Active' : 'Inactive',
                $card->type,
                $card->username,
            ]) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="cards.csv"');
    }
}
