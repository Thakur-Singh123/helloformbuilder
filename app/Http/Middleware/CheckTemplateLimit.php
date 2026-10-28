<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTemplateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user) {
            $user->syncPlan();
            $user->refresh();

            if (!$user->canUseTemplate()) {
                $msg = $user->isExpired()
                    ? 'Your Pro plan has expired. Please renew your plan to continue using templates.'
                    : 'Your free template has been used. Please upgrade your plan to continue.';

                return redirect()->route('plans.upgrade')->with('error', $msg);
            }
        }

        return $next($request);
    }
}
