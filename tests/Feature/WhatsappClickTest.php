<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\WhatsappClick;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsappClickTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_whatsapp_links_carry_placement(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-whatsapp-placement="hero"', false)
            ->assertSee('data-whatsapp-placement="footer"', false)
            ->assertSee('data-whatsapp-placement="floating"', false)
            ->assertSee('data-whatsapp-placement="sticky"', false);
    }

    public function test_service_page_whatsapp_links_are_tagged(): void
    {
        $service = Service::query()->where('is_published', true)->first()
            ?? Service::query()->create([
                'title'        => 'Bathroom Renovations Pretoria East',
                'slug'         => 'bathroom-renovations-pretoria-east',
                'is_published' => true,
                'sort_order'   => 1,
            ]);

        $this->get(route('services.show', $service->slug))
            ->assertOk()
            ->assertSee('data-whatsapp-placement="service"', false);
    }

    public function test_click_is_recorded(): void
    {
        $this->post(route('whatsapp-clicks.store'), [
            'placement' => 'hero',
            'path'      => '/?utm=test',
        ])->assertNoContent();

        $this->assertDatabaseHas('whatsapp_clicks', [
            'placement' => 'hero',
            'path'      => '/',
        ]);
    }

    public function test_unknown_placement_is_normalised(): void
    {
        $this->post(route('whatsapp-clicks.store'), [
            'placement' => 'javascript:alert(1)',
            'path'      => 'https://evil.example/steal',
        ])->assertNoContent();

        $click = WhatsappClick::query()->first();

        $this->assertSame('unknown', $click->placement);
        $this->assertNull($click->path);
    }
}
