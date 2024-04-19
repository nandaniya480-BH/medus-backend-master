<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class userRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role,$role2=null)
    {
        if (Auth::check() && (Auth::user()->role == 'superadmin')) {
            return $next($request);
        }
        if (Auth::check() && (Auth::user()->role == $role || Auth::user()->role == $role2 )) {
            return $next($request);
        }
        return response()->json("Premission deinied!",401);
    }
}
