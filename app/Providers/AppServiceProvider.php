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
        // Livewire 3 registers POST /livewire/upload-file with ONLY a throttle
        // middleware by default — no session, no auth. Behind a reverse proxy
        // (NPM) and inside Filament's panel context, that route then aborts
        // 401 ("Unauthenticated") because no auth context was loaded for the
        // request, even though the user has a valid session cookie. Forcing
        // the upload route through the standard `web` group (loads session +
        // cookies + CSRF) and `auth:web` (requires logged-in user) makes the
        // upload share the same auth context as /livewire/update, which fixes
        // image uploads in the Filament admin (FileUpload, ImageColumn, etc).
        config([
            'livewire.temporary_file_upload.middleware' => ['web', 'auth:web'],
        ]);
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
