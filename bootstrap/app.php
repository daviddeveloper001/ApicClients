<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckApiClientToken;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //$middleware->append('check.api.client', CheckApiClientToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
