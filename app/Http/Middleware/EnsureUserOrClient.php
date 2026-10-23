<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Allow user_type: user, client, admin, superadmin (same dashboard area).
 * Super Admin and Admin use the same dashboard as user, with extra sidebar menu.
 */
class EnsureUserOrClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = currentUser();
        if (!$user) {
            return redirect()->route('login');
        }
        if (!in_array($user->user_type, ['user', 'client', 'admin', 'superadmin'], true)) {
            abort(403, 'Access denied.');
        }
        return $next($request);
    }
}
