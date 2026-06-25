<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Sends push notifications via ntfy.sh (or any self-hosted ntfy instance).
 *
 * Reads its config from App\Models\AppSetting so the owner can change server,
 * topic, token, and priority from the admin panel without redeploying.
 *
 * Posts a JSON body to the server root — see https://docs.ntfy.sh/publish/
 * which is the most robust transport (no quirky header encoding for unicode
 * titles/messages, no URL-encoding edge cases for the topic name).
 */
class NtfyService
{
    /**
     * Publish a notification using the saved settings.
     *
     * Silently returns false if ntfy isn't enabled or no topic is configured,
     * so callers don't have to guard with their own enabled-checks.
     *
     * @param  array{priority?:int,tags?:array<int,string>,click?:string,server?:string,topic?:string,token?:string}  $opts
     */
    public function notify(string $title, string $message, array $opts = []): bool
    {
        $settings = AppSetting::current();

        $server = $opts['server'] ?? $settings->ntfy_server ?? 'https://ntfy.sh';
        $topic  = $opts['topic']  ?? $settings->ntfy_topic;
        $token  = $opts['token']  ?? $settings->ntfy_token;

        // Honour the saved enabled flag, but allow the test-send action on
        // the settings page to override it by passing the topic explicitly.
        $isExplicitOverride = isset($opts['topic']);
        if (! $isExplicitOverride && ! $settings->ntfy_enabled) {
            return false;
        }

        if (empty($topic)) {
            return false;
        }

        $this->publish($server, $topic, $token, $title, $message, [
            'priority' => $opts['priority'] ?? $settings->ntfy_priority,
            'tags'     => $opts['tags']     ?? [],
            'click'    => $opts['click']    ?? null,
        ])->throw();

        return true;
    }

    /**
     * Low-level publish — used by both notify() and the page's "Send test"
     * action (which needs to call it with values from the form before they're
     * saved to the database).
     *
     * @param  array{priority?:int|null,tags?:array<int,string>,click?:string|null}  $opts
     */
    public function publish(
        string $server,
        string $topic,
        ?string $token,
        string $title,
        string $message,
        array $opts = [],
    ): Response {
        $payload = array_filter(
            [
                'topic'    => $topic,
                'title'    => $title,
                'message'  => $message,
                'priority' => $opts['priority'] ?? null,
                'tags'     => ! empty($opts['tags']) ? array_values($opts['tags']) : null,
                'click'    => $opts['click'] ?? null,
            ],
            fn ($v) => $v !== null && $v !== '' && $v !== [],
        );

        $request = Http::timeout(5)->acceptJson();

        if (! empty($token)) {
            $request = $request->withToken($token);
        }

        return $request->post(rtrim($server, '/'), $payload);
    }
}
