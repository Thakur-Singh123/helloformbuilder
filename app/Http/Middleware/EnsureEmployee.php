<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = currentUser();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->user_type !== 'employee') {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}