<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class HandleUnAdminAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if the user is not authenticated
        if (auth('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Already login as an admin. Please logout first'], 401);
            } else {
                return  redirect('/admin'); 
            }
        }


        return $next($request);
    }
}
