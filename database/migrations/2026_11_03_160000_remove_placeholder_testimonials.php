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

        $kept = [];

        foreach ($page->testimonials ?? [] as $item) {
            if (! is_array($item)) {
                continue;
            }

            if (! PageSetting::isPublicTestimonialName($item['name'] ?? null)) {
                continue;
            }

            if (! array_key_exists('is_published', $item)) {
                $item['is_published'] = true;
            }

            $kept[] = $item;
        }

        $page->testimonials = $kept;

        $titleCase = 'Request a Quote for Building or Renovation Work in Pretoria East';

        if ($page->contact_heading === $titleCase) {
            $page->contact_heading = 'Request a quote for building or renovation work in Pretoria East';
        }

        $page->save();
    }

    public function down(): void
    {
        // Placeholder quotes are not restored. They had no real author names.
    }
};
