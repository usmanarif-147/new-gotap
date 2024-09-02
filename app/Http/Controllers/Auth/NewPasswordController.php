<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Admin
     */
    public function createAdmin(Request $request): View
    {
        return view(
            'auth.reset-password',
            [
                'request' => $request
            ]
        );
    }
    public function storeAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $admin = User::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->first();


        if (!$admin) {
            return back()->with('message', 'Email or Token is not valid');
        }

        $admin->password = Hash::make($request->password);
        $admin->remember_token = null;
        $admin->save();

        return redirect()->route('admin.login');
    }



    /**
     * Enterprise
     */
    public function createEnterprise(Request $request): View
    {
        return view(
            'auth.reset-password',
            [
                'request' => $request
            ]
        );
    }
    public function storeEnterprise(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $admin = User::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->first();


        if (!$admin) {
            return back()->with('message', 'Email or Token is not valid');
        }

        $admin->password = Hash::make($request->password);
        $admin->remember_token = null;
        $admin->save();

        return redirect()->route('admin.login');
    }
}
