<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{

    public function handle(Request $request, Closure $next)
    {

        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $userRole = Auth::user()->role;
        if ($userRole == 'enterpriser') {
            return redirect()->route('enterprise.dashboard');
        }

        return $next($request);

        // if (Auth::check() && Auth::user()->role === 'admin') {
        //     return $next($request);
        // }

        // return redirect()->route('admin.login.form')
        //     ->with('error', 'You do not have access to this page.');
    }
}
