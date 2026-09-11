<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Suppress PHP deprecation notices at the SAPI boundary. Laragon-local
// PHP 8.5 emits `PDO::MYSQL_ATTR_SSL_CA` deprecations from Laravel's own
// config files, and with display_errors on (Laragon default) those get
// prepended into every HTTP response — including livewire.js — which
// breaks the Filament admin panel. Production runs PHP 8.3 in Docker with
// display_errors off, so this is a no-op there. Laravel's exception
// handler still renders real errors via Ignition.
error_reporting(error_reporting() & ~E_DEPRECATED & ~E_USER_DEPRECATED);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust NPM / any reverse proxy on the Docker network so Laravel reads
        // the X-Forwarded-Proto header and generates https:// asset URLs when
        // the public request is https. Without this, mixed-content breakage.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
