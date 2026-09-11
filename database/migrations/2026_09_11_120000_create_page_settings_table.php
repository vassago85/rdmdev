<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_settings', function (Blueprint $table) {
            $table->id();

            $table->string('about_eyebrow')->nullable();
            $table->string('about_heading');
            $table->text('about_intro')->nullable();
            $table->string('about_hero_image')->nullable();
            $table->string('about_story_heading')->nullable();
            $table->longText('about_body')->nullable();
            $table->string('expect_heading')->nullable();
            $table->json('expect_items')->nullable();
            $table->string('where_heading')->nullable();
            $table->text('where_we_work')->nullable();
            $table->string('team_heading')->nullable();
            $table->text('team_intro')->nullable();

            $table->string('home_about_eyebrow')->nullable();
            $table->string('home_about_heading')->nullable();
            $table->text('home_about_intro')->nullable();
            $table->json('home_about_bullets')->nullable();

            $table->string('seo_title')->nullable();
            $table->string('seo_meta_description', 320)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_settings');
    }
};
