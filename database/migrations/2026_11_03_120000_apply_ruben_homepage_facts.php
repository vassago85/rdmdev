<?php

use App\Models\PageSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = PageSetting::query()->find(1);

        if (! $page) {
            return;
        }

        $page->home_owner_bullets = collect($page->home_owner_bullets ?? [])
            ->reject(function ($item) {
                $text = is_string($item) ? $item : (string) ($item['text'] ?? '');

                return str_contains($text, 'NHBRC');
            })
            ->values()
            ->all();

        $page->testimonials = collect($page->testimonials ?? [])
            ->map(function ($item) {
                if (! is_array($item)) {
                    return $item;
                }

                $quote = (string) ($item['quote'] ?? '');
                $suburb = (string) ($item['suburb'] ?? '');

                if (str_starts_with($quote, 'Dankie vir die ongelooflike') && ($suburb === '' || $suburb === 'TODO')) {
                    $item['suburb'] = 'Garsfontein';
                }

                return $item;
            })
            ->all();

        $page->save();
    }

    public function down(): void
    {
        // The previous NHBRC wording is not restored. The certificate had expired.
    }
};
