<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check karein ke 'admin' guard se user logged in hai aur uska role 'admin' hai
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin') {
            return $next($request);
        }

        return redirect('/admin/login');
    }

}
