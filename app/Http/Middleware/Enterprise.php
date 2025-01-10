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

        $user = Auth::user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($request->routeIs('enterprise.logout') || $request->routeIs('enterprise.mysubscription')) {
            return $next($request);
        }
        if ($user->role === 'enterpriser') {
            $subscription = $user->userSubscription;
            if (!$subscription || now()->greaterThan($subscription->end_date)) {
                return redirect()->route('enterprise.mysubscription')->with('message', 'Your subscription has expired.');
            }
        }

        return $next($request);

        // if (Auth::check() && Auth::user()->role === 'enterprise') {
        //     return $next($request);
        // }

        // return redirect()->route('enterprise.login.form')
        //     ->with('error', 'You do not have access to this page.');
    }
}
