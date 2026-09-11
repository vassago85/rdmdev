<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            $table->string('home_hero_image')->nullable();
            $table->string('home_eyebrow')->nullable();
            $table->string('home_heading')->nullable();
            $table->text('home_intro')->nullable();
            $table->text('home_owner_quote')->nullable();
            $table->string('home_owner_role')->nullable();
            $table->json('home_owner_bullets')->nullable();
            $table->string('why_eyebrow')->nullable();
            $table->string('why_heading')->nullable();
            $table->text('why_intro')->nullable();
            $table->json('why_items')->nullable();
            $table->string('projects_eyebrow')->nullable();
            $table->string('projects_heading')->nullable();
            $table->text('projects_intro')->nullable();
            $table->string('services_eyebrow')->nullable();
            $table->string('services_heading')->nullable();
            $table->text('services_intro')->nullable();
            $table->text('services_disclaimer')->nullable();
            $table->string('areas_eyebrow')->nullable();
            $table->string('areas_heading')->nullable();
            $table->text('areas_intro')->nullable();
            $table->string('home_seo_title')->nullable();
            $table->string('home_seo_meta_description', 320)->nullable();
            $table->string('contact_eyebrow')->nullable();
            $table->string('contact_heading')->nullable();
            $table->text('contact_intro')->nullable();
            $table->string('contact_seo_title')->nullable();
            $table->string('contact_seo_meta_description', 320)->nullable();
        });

        $existing = DB::table('page_settings')->where('id', 1)->first();

        if ($existing && empty($existing->home_heading)) {
            $defaults = \App\Models\PageSetting::defaultAttributes();

            DB::table('page_settings')->where('id', 1)->update([
                'home_eyebrow'               => $defaults['home_eyebrow'],
                'home_heading'               => $defaults['home_heading'],
                'home_intro'                 => $defaults['home_intro'],
                'home_owner_quote'           => $defaults['home_owner_quote'],
                'home_owner_role'            => $defaults['home_owner_role'],
                'home_owner_bullets'         => json_encode($defaults['home_owner_bullets']),
                'why_eyebrow'                => $defaults['why_eyebrow'],
                'why_heading'                => $defaults['why_heading'],
                'why_intro'                  => $defaults['why_intro'],
                'why_items'                  => json_encode($defaults['why_items']),
                'projects_eyebrow'           => $defaults['projects_eyebrow'],
                'projects_heading'           => $defaults['projects_heading'],
                'projects_intro'             => $defaults['projects_intro'],
                'services_eyebrow'           => $defaults['services_eyebrow'],
                'services_heading'           => $defaults['services_heading'],
                'services_intro'             => $defaults['services_intro'],
                'services_disclaimer'        => $defaults['services_disclaimer'],
                'areas_eyebrow'              => $defaults['areas_eyebrow'],
                'areas_heading'              => $defaults['areas_heading'],
                'areas_intro'                => $defaults['areas_intro'],
                'home_seo_title'             => $defaults['home_seo_title'],
                'home_seo_meta_description'  => $defaults['home_seo_meta_description'],
                'contact_eyebrow'            => $defaults['contact_eyebrow'],
                'contact_heading'            => $defaults['contact_heading'],
                'contact_intro'              => $defaults['contact_intro'],
                'contact_seo_title'          => $defaults['contact_seo_title'],
                'contact_seo_meta_description' => $defaults['contact_seo_meta_description'],
                'updated_at'                 => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_hero_image',
                'home_eyebrow',
                'home_heading',
                'home_intro',
                'home_owner_quote',
                'home_owner_role',
                'home_owner_bullets',
                'why_eyebrow',
                'why_heading',
                'why_intro',
                'why_items',
                'projects_eyebrow',
                'projects_heading',
                'projects_intro',
                'services_eyebrow',
                'services_heading',
                'services_intro',
                'services_disclaimer',
                'areas_eyebrow',
                'areas_heading',
                'areas_intro',
                'home_seo_title',
                'home_seo_meta_description',
                'contact_eyebrow',
                'contact_heading',
                'contact_intro',
                'contact_seo_title',
                'contact_seo_meta_description',
            ]);
        });
    }
};
