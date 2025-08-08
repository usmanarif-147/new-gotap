<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{

    // public function handle(Request $request, Closure $next)
    // {

    //     if (!Auth::check()) {
    //         return redirect()->route('admin.login');
    //     }

    //     $userRole = Auth::user()->role;
    //     if ($userRole == 'enterpriser') {
    //         return redirect()->route('enterprise.dashboard');
    //     }

    //     if ($request->is('admin/*') && $request->getHost() !== 'app.gocoompany.com') {
    //         abort(403, 'Unauthorized access');
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        // Ensure admin routes are only accessible via app.gocoompany.com
        // if ($request->is('admin/*') && $request->getHost() !== 'app.gocoompany.com') {
        //     abort(403, 'Unauthorized access');
        // }

        // Allow unauthenticated users to reach admin login page
        if ($request->is('admin/login')) {
            return $next($request);
        }

        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $userRole = Auth::user()->role;

        // Redirect enterpriser users
        if ($userRole === 'enterpriser') {
            return redirect()->route('enterprise.dashboard');
        }

        return $next($request);
    }
}
