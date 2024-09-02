<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    public function setPassword()
    {
        $entRequest = User::where('token', request()->token)->first();
        if (!$entRequest) {
            abort(404);
        }
        return view('enterprise.auth.set-password', [
            'token' => request()->token
        ]);
    }

    public function saveNewPassword(Request $request)
    {
        $data = $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            User::where('token', $request->token)->update([
                'password' => Hash::make($request->password),
                'token' => null,
            ]);

            return redirect()->route('enterprise.login.form');
        } catch (Exception $ex) {
            return redirect()->back()->with('message', $ex->getMessage());
        }
    }
}
