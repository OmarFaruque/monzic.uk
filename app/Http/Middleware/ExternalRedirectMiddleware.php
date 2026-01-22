<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ExternalRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $redirectStatus = Setting::where('param', 'external_redirect_status')->first();
        $redirectUrl = Setting::where('param', 'external_redirect_url')->first();

        if ($redirectStatus && $redirectStatus->value == '1' && $redirectUrl && !$request->is('admin/*') && $request->path() !== 'admin') {
            return redirect($redirectUrl->value);
        }

        return $next($request);
    }
}
