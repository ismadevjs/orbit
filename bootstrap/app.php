<?php

use App\Http\Middleware\RunUserRulesScheduler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'maintenance' => \App\Http\Middleware\Maintenance::class,
            'user_active' => \App\Http\Middleware\UserActive::class,
            'user_verification' => \App\Http\Middleware\UserVerification::class,
            'user_send_data' => \App\Http\Middleware\UserSentData::class
        ]);

    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'mobile/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle your custom exceptions here
    })
    ->create();
