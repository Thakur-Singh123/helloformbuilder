<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Allow request to continue if either web (User) or client (Client) guard is authenticated.
 * Sets the request user to currentUser() so $request->user() and authorization use the same user.
 */
class AcceptWebOrClientAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = currentUser();
        if (!$user) {
            return redirect()->route('login');
        }
        $request->setUserResolver(fn () => $user);
        return $next($request);
    }
}
