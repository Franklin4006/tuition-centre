<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth_user = Auth::user();
        if (!$auth_user) {
            return redirect('/admin/login')->with('error', "Login to Continue");
        } else if ($auth_user->role_id == 1 || $auth_user->role_id == 2 || $auth_user->role_id == 3) {
            return $next($request);
        } else {
            return redirect('/admin')->with('error', "You don't have that access");
        }
    }
}
