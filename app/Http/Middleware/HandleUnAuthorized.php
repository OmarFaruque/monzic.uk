<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Check if a user has already been loged in. If so, redirect to home page
 */
class HandleUnAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if the user is not authenticated
        if (auth('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Already logged in. Please logout first'], 401);
            } else {
                return  redirect('/'); 
            }
        }

        return $next($request);
    }
}

