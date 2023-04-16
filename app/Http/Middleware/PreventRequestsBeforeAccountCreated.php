<?php

namespace App\Http\Middleware;

use App\Exceptions\AccountNotAvailableException;
use App\Models\Account;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventRequestsBeforeAccountCreated
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // bypass if this is m2m request
        if (auth0_user()->getAttribute('gty') === 'client-credentials') {
            return $next($request);
        }

        if (Account::where('id', auth0_user()->getAuthIdentifier())->doesntExist()) {
            throw new AccountNotAvailableException();
        }

        return $next($request);
    }
}
