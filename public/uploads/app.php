<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'shortcode/*/submit',
            'api/shortcode/*/submit',
        ]);
        $middleware->redirectGuestsTo(fn (Request $request) =>
            $request->routeIs('templates.use') ? route('template.gate') : route('login')
        );
        $middleware->alias([
            'auth_user_or_client' => \App\Http\Middleware\AcceptWebOrClientAuth::class,
            'user' => \App\Http\Middleware\EnsureUserOrClient::class,
            'admin' => \App\Http\Middleware\EnsureAdminOrSuperAdmin::class,
            'employee' => \App\Http\Middleware\EnsureEmployee::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
