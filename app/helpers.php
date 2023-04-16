<?php

/**
 * Get the Auth0 user.
 *
 * @return \Auth0\Laravel\Model\Stateless\User
 */
function auth0_user(): \Auth0\Laravel\Model\Stateless\User
{
    $u = \Illuminate\Support\Facades\Auth::user();
    if ($u instanceof \Auth0\Laravel\Model\Stateless\User) {
        return $u;
    }
    throw new RuntimeException('Auth0 user not found');
}
