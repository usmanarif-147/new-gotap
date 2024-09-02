<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Enterprise
{
    public function handle(Request $request, Closure $next)
    {

        if (!Auth::check()) {
            return redirect()->route('enterprise.login');
        }

        $userRole = Auth::user()->role;
        if ($userRole == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);

        // if (Auth::check() && Auth::user()->role === 'enterprise') {
        //     return $next($request);
        // }

        // return redirect()->route('enterprise.login.form')
        //     ->with('error', 'You do not have access to this page.');
    }
}
