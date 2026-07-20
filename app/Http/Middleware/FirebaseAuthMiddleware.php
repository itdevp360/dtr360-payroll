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

        $firebaseUser = Session::get('firebase_user');

        if (is_array($firebaseUser)) {
            $firebaseUser['name'] = $firebaseUser['name'] ?? $this->resolveEmployeeName($firebaseUser['email'] ?? null);

            if (!empty($firebaseUser['name'])) {
                Session::put('firebase_user', $firebaseUser);
            }

            View::share('authUser', $firebaseUser['name'] ?? $firebaseUser['email'] ?? 'User');
            View::share('dept', $firebaseUser['department'] ?? $firebaseUser['dept'] ?? null);
            View::share('usertype', $firebaseUser['usertype'] ?? null);
            View::share('guid', $firebaseUser['guid'] ?? null);
        }

        return $next($request);
    }

    protected function resolveEmployeeName(?string $email): ?string
    {
        if (empty($email)) {
            return null;
        }

        try {
            $employeeData = app('firebase.database')
                ->getReference('Employee')
                ->orderByChild('email')
                ->equalTo($email)
                ->getValue();
        } catch (\Throwable $e) {
            return null;
        }

        if (!is_array($employeeData)) {
            return null;
        }

        $employee = reset($employeeData);

        return is_array($employee)
            ? ($employee['name'] ?? $employee['fullName'] ?? $employee['employeeName'] ?? null)
            : null;
    }
}
