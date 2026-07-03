<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\View;

class FirebaseAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('firebase_user')) {
            return redirect('/login');
        }

        $email = Session::get('firebase_user.email');

        $firebaseUser = Session::get('firebase_user');

        if ($firebaseUser) {
            View::share('authUser', $firebaseUser['name'] ?? $firebaseUser['email'] ?? 'User');
            View::share('dept', $firebaseUser['department'] ?? $firebaseUser['dept'] ?? null);
            View::share('usertype', $firebaseUser['usertype'] ?? null);
            View::share('guid', $firebaseUser['guid'] ?? null);
        }

        return $next($request);
    }
}
