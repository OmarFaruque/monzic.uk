<?php

namespace App\Http\Middleware;

use App\Services\BlacklistService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklistedUser
{
    public function __construct(private BlacklistService $blacklistService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($this->blacklistService->isUserBlacklisted($user)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Account access restricted',
                    ], 403);
                }

                return redirect('/my-account')->withErrors([
                    'auth' => 'Account access restricted',
                ]);
            }
        }

        return $next($request);
    }
}