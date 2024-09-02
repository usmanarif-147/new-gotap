<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminForgotPasswordMail;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Admin
     */
    public function createAdmin(): View
    {
        return view('auth.forgot-password');
    }
    public function storeAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $admin = User::where('email', $request->email)->first();

        if (!$admin) {
            return back()->with('message', 'Email is not valid');
        }

        $admin->remember_token = str()->random(50);
        $admin->save();

        Mail::to($admin->email)->send(new AdminForgotPasswordMail($admin));
    }


    /**
     * Enterprise
     */
    public function createEnterprise(): View
    {
        return view('auth.forgot-password');
    }
    public function storeEnterprise(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $admin = User::where('email', $request->email)->first();

        if (!$admin) {
            return back()->with('message', 'Email is not valid');
        }

        $admin->remember_token = str()->random(50);
        $admin->save();

        Mail::to($admin->email)->send(new AdminForgotPasswordMail($admin));

        return back()->with('message', 'Email Send To Admin');
    }
}
