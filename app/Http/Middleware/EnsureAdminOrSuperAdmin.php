<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Allow only user_type 'admin' or 'superadmin' (admin panel).
 */
class EnsureAdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = currentUser();
        if (!$user) {
            return redirect()->route('admin_login');
        }
        // Only User (admin/superadmin) can access admin panel; Client is from clients table
        if (!in_array($user->user_type, ['admin', 'superadmin'], true)) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
