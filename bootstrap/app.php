<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )->withMiddleware(function (Middleware $middleware) {
    })->withExceptions(function ($exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            // Exceptions should be rendered as JSON
            if ($request->is('api/*')) {
                return true; // Always render JSON for 'api/*' routes
            }

            return $request->expectsJson();
        });
    })->create();
