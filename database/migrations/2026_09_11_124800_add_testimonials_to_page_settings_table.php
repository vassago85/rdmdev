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
            $table->string('testimonials_eyebrow')->nullable()->after('home_about_bullets');
            $table->string('testimonials_heading')->nullable()->after('testimonials_eyebrow');
            $table->text('testimonials_intro')->nullable()->after('testimonials_heading');
            $table->json('testimonials')->nullable()->after('testimonials_intro');
        });

        $existing = DB::table('page_settings')->where('id', 1)->first();

        if ($existing && empty($existing->testimonials)) {
            DB::table('page_settings')->where('id', 1)->update([
                'testimonials_eyebrow' => 'What clients say',
                'testimonials_heading' => 'Trusted across Pretoria East',
                'testimonials_intro'   => 'Straight feedback from homeowners we\'ve worked with.',
                'testimonials'         => json_encode($this->defaultTestimonials()),
                'updated_at'           => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'testimonials_eyebrow',
                'testimonials_heading',
                'testimonials_intro',
                'testimonials',
            ]);
        });
    }

    /** @return array<int, array{quote: string, name: string, suburb: string, rating: int}> */
    protected function defaultTestimonials(): array
    {
        return [
            [
                'quote'  => 'Ruben was on site, on time, and the finish was exactly what we agreed. No chasing, no surprises.',
                'name'   => 'Homeowner',
                'suburb' => 'Garsfontein',
                'rating' => 5,
            ],
            [
                'quote'  => 'Clear quote, tidy site, and the bathroom came out better than we expected. Would use RDM again.',
                'name'   => 'Homeowner',
                'suburb' => 'Faerie Glen',
                'rating' => 5,
            ],
        ];
    }
};
