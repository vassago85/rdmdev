<?php

namespace App\Providers;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Behind NPM (https terminated at the proxy) the app receives plain
        // http, so asset() / url() would render http:// URLs and break mixed
        // content. Force https in production as a safety net in addition to
        // the trustProxies() middleware.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->applyMailSettings();
    }

    /**
     * Read SMTP credentials from the `app_settings` table and override the
     * mail config in memory for this request. This lets the owner edit creds
     * through the Filament admin without redeploying or restarting containers.
     *
     * Wrapped defensively: if the table is missing (first migration), the DB
     * is unreachable, or no row has been saved yet, we silently fall back to
     * whatever is in .env / config/mail.php so the admin never gets locked out
     * by a misconfiguration here.
     */
    protected function applyMailSettings(): void
    {
        try {
            if (! Schema::hasTable('app_settings')) {
                return;
            }

            $settings = AppSetting::query()->find(1);

            if (! $settings) {
                return;
            }

            if ($settings->mailer) {
                Config::set('mail.default', $settings->mailer);
            }

            if ($settings->mail_host) {
                Config::set('mail.mailers.smtp.host', $settings->mail_host);
            }
            if ($settings->mail_port) {
                Config::set('mail.mailers.smtp.port', $settings->mail_port);
            }
            if ($settings->mail_username) {
                Config::set('mail.mailers.smtp.username', $settings->mail_username);
            }
            if ($settings->mail_password) {
                Config::set('mail.mailers.smtp.password', $settings->mail_password);
            }

            // 'tls' / 'ssl' / '' (the SMTP transport treats empty/null as no encryption).
            Config::set('mail.mailers.smtp.encryption', $settings->mail_encryption ?: null);

            if ($settings->mail_from_address) {
                Config::set('mail.from.address', $settings->mail_from_address);
            }
            if ($settings->mail_from_name) {
                Config::set('mail.from.name', $settings->mail_from_name);
            }

            if ($settings->enquiry_to) {
                Config::set('rdm.enquiry_to', $settings->enquiry_to);
            }
        } catch (\Throwable $e) {
            // Don't let a broken settings read take down the entire app.
            report($e);
        }
    }
}
