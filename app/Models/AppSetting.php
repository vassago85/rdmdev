<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton settings row (id=1) holding the runtime config for the things the
 * site owner needs to be able to change without redeploying:
 *
 *   - Mailgun credentials (domain + API key) for outgoing email — enquiry
 *     notifications, password resets, etc.
 *   - ntfy.sh push-notification settings (instant phone alerts when a new
 *     enquiry comes in)
 *
 * Read at the start of every request by AppServiceProvider (which overrides
 * config('services.mailgun.*') / config('mail.*') / config('rdm.enquiry_to')
 * from this row), and on demand by App\Services\NtfyService.
 *
 * Edit via the Filament admin page at /admin/notifications — no env file
 * changes or container restarts required.
 */
class AppSetting extends Model
{
    protected $fillable = [
        'mailer',
        'mailgun_domain',
        'mailgun_secret',
        'mailgun_endpoint',
        'mail_from_address',
        'mail_from_name',
        'enquiry_to',
        'ntfy_enabled',
        'ntfy_server',
        'ntfy_topic',
        'ntfy_token',
        'ntfy_priority',
    ];

    protected $casts = [
        'mailgun_secret' => 'encrypted',
        'ntfy_token'     => 'encrypted',
        'ntfy_enabled'   => 'boolean',
        'ntfy_priority'  => 'integer',
    ];

    /**
     * Return the single settings row, creating it on first call. Cached for
     * the lifetime of the request to avoid repeat queries.
     */
    public static function current(): self
    {
        static $cached = null;

        if ($cached instanceof self) {
            return $cached;
        }

        return $cached = static::firstOrCreate(
            ['id' => 1],
            [
                'mailer'           => 'mailgun',
                'mailgun_endpoint' => 'api.mailgun.net',
                'ntfy_server'      => 'https://ntfy.sh',
            ]
        );
    }
}
