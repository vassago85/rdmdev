<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageAboutTeam;
use App\Models\PageSetting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use OverflowException;
use Tests\TestCase;

class AboutTeamPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_renders_seeded_story_and_default_member(): void
    {
        $about = PageSetting::current();

        $this->get(route('about'))
            ->assertOk()
            ->assertSeeText($about->about_heading)
            ->assertSeeText($about->about_intro)
            ->assertSeeText('Meet the team')
            ->assertSeeText(config('rdm.owner'))
            ->assertSeeText('Owner & Project Supervisor')
            ->assertSeeText('About & Team');
    }

    public function test_unpublished_members_are_hidden_on_the_public_page(): void
    {
        $page = PageSetting::current();

        TeamMember::factory()->unpublished()->create([
            'page_setting_id' => $page->id,
            'name'            => 'Hidden Colleague',
            'title'           => 'Site Foreman',
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSeeText(config('rdm.owner'))
            ->assertDontSee('Hidden Colleague')
            ->assertDontSee('Site Foreman');
    }

    public function test_a_seventh_team_member_is_rejected(): void
    {
        $page = PageSetting::current();

        TeamMember::factory()->count(5)->create([
            'page_setting_id' => $page->id,
        ]);

        $this->assertSame(6, $page->members()->count());

        $this->expectException(OverflowException::class);

        TeamMember::factory()->create([
            'page_setting_id' => $page->id,
        ]);
    }

    public function test_about_page_uses_custom_seo_title_and_description(): void
    {
        $page = PageSetting::current();
        $page->update([
            'seo_title'            => 'Custom About Title For Tests',
            'seo_meta_description' => 'Custom meta description used only in the about page test.',
        ]);
        PageSetting::flushCache();

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('<title>Custom About Title For Tests</title>', false)
            ->assertSee('Custom meta description used only in the about page test.', false);
    }

    public function test_home_page_uses_published_about_teaser(): void
    {
        $page = PageSetting::current();
        $page->update([
            'home_about_heading' => 'Home teaser heading for tests',
            'home_about_intro'   => 'Home teaser intro for tests.',
        ]);
        PageSetting::flushCache();

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('Home teaser heading for tests')
            ->assertSeeText('Home teaser intro for tests.')
            ->assertSeeText('About & Team');
    }

    public function test_published_testimonials_appear_on_home_and_about(): void
    {
        $page = PageSetting::current();
        $page->update([
            'testimonials_heading' => 'Client quotes for tests',
            'testimonials'         => [
                [
                    'quote'  => 'Unique testimonial quote used only in tests.',
                    'name'   => 'Test Client',
                    'suburb' => 'Woodhill',
                    'rating' => 5,
                ],
            ],
        ]);
        PageSetting::flushCache();

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('Client quotes for tests')
            ->assertSeeText('Unique testimonial quote used only in tests.')
            ->assertSeeText('Test Client')
            ->assertSeeText('Woodhill');

        $this->get(route('about'))
            ->assertOk()
            ->assertSeeText('Unique testimonial quote used only in tests.')
            ->assertSeeText('Test Client');
    }

    public function test_home_page_uses_published_hero_copy(): void
    {
        $page = PageSetting::current();
        $page->update([
            'home_heading' => 'Custom hero heading for designer tests',
            'home_intro'   => 'Custom hero intro for designer tests.',
        ]);
        PageSetting::flushCache();

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('Custom hero heading for designer tests')
            ->assertSeeText('Custom hero intro for designer tests.');
    }

    public function test_contact_page_uses_published_heading(): void
    {
        $page = PageSetting::current();
        $page->update([
            'contact_heading' => 'Custom contact heading for tests',
            'contact_intro'   => 'Custom contact intro for tests.',
        ]);
        PageSetting::flushCache();

        $this->get(route('contact'))
            ->assertOk()
            ->assertSeeText('Custom contact heading for tests')
            ->assertSeeText('Custom contact intro for tests.');
    }

    public function test_team_group_photo_renders_with_caption_when_uploaded(): void
    {
        $page = PageSetting::current();
        $page->update([
            'team_group_photo'         => 'team/rdm-group.jpg',
            'team_group_photo_caption' => 'The RDM crew on a Garsfontein renovation.',
        ]);
        PageSetting::flushCache();

        $response = $this->get(route('about'))->assertOk();
        $response->assertSee('storage/team/rdm-group.jpg', false);
        $response->assertSeeText('The RDM crew on a Garsfontein renovation.');
    }

    public function test_group_photo_absent_when_no_upload(): void
    {
        PageSetting::current();
        PageSetting::flushCache();

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('team_group_photo_caption', false)
            ->assertDontSee('rdm-group.jpg', false);
    }

    public function test_empty_testimonials_hide_the_section(): void
    {
        $page = PageSetting::current();
        $page->update([
            'testimonials_heading' => 'Should not appear when empty',
            'testimonials'         => [],
        ]);
        PageSetting::flushCache();

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Should not appear when empty')
            ->assertDontSee('Ruben was on site, on time');
    }

    /**
     * Guard against the "publish button silently rejected the save" regression:
     * every seeded value must pass the ManageAboutTeam validation rules so an
     * admin can open the page and hit Publish without having to shorten seeded
     * copy first.
     */
    public function test_publishing_the_seeded_page_persists_edits_end_to_end(): void
    {
        PageSetting::current();
        PageSetting::flushCache();

        $admin = User::factory()->create();
        $this->actingAs($admin);

        Livewire::test(ManageAboutTeam::class)
            ->assertOk()
            ->set('data.about_heading', 'A brand new About & Team heading')
            ->set('data.home_heading', 'A brand new homepage headline')
            ->set('data.contact_heading', 'A brand new contact heading')
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        PageSetting::flushCache();
        $reloaded = PageSetting::current();

        $this->assertSame('A brand new About & Team heading', $reloaded->about_heading);
        $this->assertSame('A brand new homepage headline', $reloaded->home_heading);
        $this->assertSame('A brand new contact heading', $reloaded->contact_heading);

        $this->get(route('about'))
            ->assertOk()
            ->assertSeeText('A brand new About & Team heading');

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('A brand new homepage headline');

        $this->get(route('contact'))
            ->assertOk()
            ->assertSeeText('A brand new contact heading');
    }
}
