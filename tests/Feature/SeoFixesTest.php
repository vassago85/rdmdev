<?php

namespace Tests\Feature;

use App\Models\PageSetting;
use App\Models\Project;
use App\Models\Service;
use App\Support\JsonLd;
use App\Support\MetaDescription;
use App\Support\ResponsiveImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoFixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_placeholder_testimonials_are_not_rendered(): void
    {
        $page = PageSetting::current();
        $page->update([
            'testimonials_heading' => 'Quotes that must stay hidden',
            'testimonials' => [
                [
                    'quote' => 'Placeholder quote that must not appear.',
                    'name' => 'TODO',
                    'suburb' => 'Garsfontein',
                    'rating' => 5,
                    'is_published' => true,
                ],
                [
                    'quote' => 'Unpublished real quote that must not appear.',
                    'name' => 'Anna',
                    'suburb' => 'Lynnwood',
                    'rating' => 5,
                    'is_published' => false,
                ],
            ],
        ]);
        PageSetting::flushCache();

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Placeholder quote that must not appear.')
            ->assertDontSee('TODO')
            ->assertDontSee('Unpublished real quote that must not appear.')
            ->assertDontSee('Quotes that must stay hidden');

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('Placeholder quote that must not appear.');
    }

    public function test_meta_description_keeps_an_explicit_value_and_cuts_fallbacks_on_a_word(): void
    {
        $explicit = str_repeat('Word ', 20);
        $this->assertSame(trim($explicit), MetaDescription::from($explicit, 'ignored fallback that is long enough to be cut'));
        $this->assertLessThanOrEqual(160, mb_strlen(MetaDescription::from($explicit, 'ignored')));

        $fallback = 'Alpha beta gamma delta epsilon zeta eta theta iota kappa lambda mu nu xi omicron pi rho sigma tau upsilon phi chi psi omega and then more words that push this past the snippet limit for search results today.';
        $cut = MetaDescription::from(null, $fallback);

        $this->assertLessThanOrEqual(155, mb_strlen($cut));
        $this->assertStringStartsWith($cut, $fallback);
        $this->assertStringNotContainsString('…', $cut);
        $this->assertStringNotContainsString('...', $cut);
    }

    public function test_project_heading_does_not_repeat_the_suburb(): void
    {
        $project = new Project([
            'title' => 'Wingate Park Home Renovation',
            'location' => 'Wingate Park',
        ]);

        $this->assertSame('Wingate Park Home Renovation, Pretoria East', $project->displayHeading());

        $other = new Project([
            'title' => 'Complete Bathroom Renovation',
            'location' => 'Garsfontein',
        ]);

        $this->assertSame('Complete Bathroom Renovation, Garsfontein, Pretoria East', $other->displayHeading());
    }

    public function test_contact_heading_is_sentence_case(): void
    {
        $this->assertSame(
            'Request a quote for building or renovation work in Pretoria East',
            PageSetting::defaultAttributes()['contact_heading']
        );
    }

    public function test_sitemap_uses_lastmod_without_changefreq_or_priority(): void
    {
        $service = Service::query()->create([
            'title' => 'Building Pretoria East',
            'slug' => 'building-pretoria-east',
            'is_published' => true,
        ]);
        $service->timestamps = false;
        $service->updated_at = '2026-05-02 10:00:00';
        $service->save();

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $body = $response->getContent();
        $this->assertStringContainsString('<lastmod>', $body);
        $this->assertStringContainsString('2026-05-02', $body);
        $this->assertStringNotContainsString('changefreq', $body);
        $this->assertStringNotContainsString('<priority>', $body);
        $this->assertStringNotContainsString('now()', $body);
    }

    public function test_business_schema_uses_suburbs_and_skips_empty_profiles(): void
    {
        $entity = JsonLd::business();

        $this->assertSame('ImageObject', $entity['logo']['@type']);
        $this->assertArrayNotHasKey('sameAs', $entity);
        $this->assertArrayNotHasKey('hasMap', $entity);
        $this->assertArrayNotHasKey('aggregateRating', $entity);
        $this->assertArrayNotHasKey('review', $entity);

        $names = collect($entity['areaServed'])->pluck('name');
        $this->assertTrue($names->contains('Garsfontein, Pretoria East, Gauteng'));
        $this->assertTrue($names->contains('Wingate Park, Pretoria East, Gauteng'));
    }

    public function test_faq_schema_is_omitted_until_a_service_has_faqs(): void
    {
        $service = Service::query()->create([
            'title' => 'Tiling Pretoria East',
            'slug' => 'tiling-pretoria-east',
            'is_published' => true,
            'faq' => [],
        ]);

        $this->get(route('services.show', $service))
            ->assertOk()
            ->assertDontSee('FAQPage', false);

        $service->update([
            'faq' => [
                ['question' => 'How long does tiling take?', 'answer' => 'It depends on the room.'],
            ],
        ]);

        $this->get(route('services.show', $service))
            ->assertOk()
            ->assertSee('FAQPage', false)
            ->assertSee('How long does tiling take?', false);
    }

    public function test_service_and_project_pages_link_to_each_other(): void
    {
        $service = Service::query()->create([
            'title' => 'Bathroom Renovations Pretoria East',
            'slug' => 'bathroom-renovations-pretoria-east',
            'is_published' => true,
        ]);
        $project = Project::query()->create([
            'title' => 'Complete Bathroom Renovation',
            'slug' => 'complete-bathroom-renovation',
            'location' => 'Faerie Glen',
            'is_published' => true,
        ]);
        $project->services()->attach($service);

        $this->get(route('services.show', $service))
            ->assertOk()
            ->assertSeeText('Related projects')
            ->assertSeeText('Complete Bathroom Renovation');

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee(route('services.show', $service), false)
            ->assertSeeText('Bathroom renovations');
    }

    public function test_www_host_redirects_to_the_apex_and_keeps_the_query(): void
    {
        $this->get('http://www.rdmdev.co.za/contact?foo=1')
            ->assertStatus(301)
            ->assertRedirect('https://rdmdev.co.za/contact?foo=1');

        $this->get('http://rdmdev.co.za/contact')->assertOk();
    }

    public function test_image_variants_do_not_upscale_past_the_original(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD WebP is not available.');
        }

        $directory = storage_path('app/public/projects');
        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $path = $directory.'/seo-test-source.jpg';
        $canvas = imagecreatetruecolor(500, 300);
        imagejpeg($canvas, $path, 90);
        imagedestroy($canvas);

        $picture = ResponsiveImage::fromStoragePath('projects/seo-test-source.jpg');

        $this->assertSame(500, $picture['width']);
        $this->assertSame(300, $picture['height']);
        $this->assertTrue($picture['below_1200']);
        $this->assertStringContainsString(' 400w', (string) $picture['srcset']);
        $this->assertStringNotContainsString(' 800w', (string) $picture['srcset']);
        $this->assertStringNotContainsString(' 1200w', (string) $picture['srcset']);

        @unlink($path);
    }
}
