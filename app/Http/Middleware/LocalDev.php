<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class LocalDev
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // bypass if not local
        if (!app()->isLocal()) {
            return $next($request);
        }

        // replace the dev token with real auth0 token
        // e.g.:
        // Authorization: Bearer dev-test => Authorization: Bearer <real token>
        if ($request->bearerToken() === 'dev-test') {
            $m2m_token = Http::post('https://' . config('auth0.domain') . '/oauth/token', [
                'client_id' => config('auth0.clientId'),
                'client_secret' => config('auth0.clientSecret'),
                'audience' => config('auth0.audience')[0],
                'grant_type' => 'client_credentials',
            ])->json()['access_token'];
            $request->headers->set('Authorization', 'Bearer ' . $m2m_token);
        }

        return $next($request);
    }
}
