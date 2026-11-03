<?php

use App\Models\PageSetting;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('short_description', 160)->nullable()->after('tagline');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('featured_image');
        });

        $lines = [
            'building-pretoria-east' => 'Masonry, additions and alterations, supervised by Ruben from quote to hand-over.',
            'bathroom-renovations-pretoria-east' => 'Plumbing, tiling, showers and finishes, quoted and supervised by Ruben.',
            'tiling-pretoria-east' => 'Wall and floor tiling for bathrooms, living areas and outdoor spaces.',
            'waterproofing-pretoria-east' => 'Roof, wall and wet-area waterproofing, with the prep done first.',
            'painting-pretoria-east' => 'Interior and exterior painting, properly prepared, on a tidy site.',
        ];

        foreach ($lines as $slug => $line) {
            Service::query()->where('slug', $slug)->update(['short_description' => $line]);
        }

        Project::query()->with('images')->each(function (Project $project) {
            if (filled($project->cover_image)) {
                return;
            }

            $after = $project->images->firstWhere('type', 'after');
            if ($after) {
                $project->cover_image = $after->path;
                $project->save();
            }
        });

        Project::query()->where(function ($query) {
            foreach (['title', 'location', 'description', 'seo_title', 'meta_description'] as $column) {
                $query->orWhere($column, 'like', '%Lynwood%');
            }
        })->each(function (Project $project) {
            foreach (['title', 'location', 'description', 'seo_title', 'meta_description'] as $column) {
                $value = $project->{$column};
                if (is_string($value) && str_contains($value, 'Lynwood')) {
                    $project->{$column} = str_replace('Lynwood', 'Lynnwood', $value);
                }
            }
            $project->save();
        });

        $page = PageSetting::query()->find(1);
        if (! $page) {
            return;
        }

        $defaults = PageSetting::defaultAttributes();
        foreach ([
            'home_intro',
            'home_owner_quote',
            'why_heading',
            'why_items',
            'services_intro',
            'services_disclaimer',
            'areas_intro',
            'home_about_intro',
            'home_about_bullets',
        ] as $field) {
            $page->{$field} = $defaults[$field];
        }

        $page->testimonials = collect($page->testimonials ?? [])
            ->map(function ($item) {
                if (! is_array($item)) {
                    return $item;
                }
                if (($item['name'] ?? '') === 'Homeowner') {
                    $item['name'] = 'TODO';
                }
                if (! filled($item['suburb'] ?? null)) {
                    $item['suburb'] = 'TODO';
                }

                return $item;
            })
            ->all();

        $page->save();
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('short_description');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });
    }
};
