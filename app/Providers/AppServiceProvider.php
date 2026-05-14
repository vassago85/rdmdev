<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Behind NPM (https terminated at the proxy) the app receives plain
        // http, so asset() / url() would render http:// URLs and break mixed
        // content. Force https in production as a safety net in addition to
        // the trustProxies() middleware.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
