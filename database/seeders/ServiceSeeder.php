<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title'            => 'Building Pretoria East',
                'slug'             => 'building-pretoria-east',
                'tagline'          => 'Masonry, additions, alterations and general construction.',
                'icon'             => 'hammer',
                'excerpt'          => 'Building work across Pretoria East — masonry, additions, alterations, ceilings and general construction. Personally supervised by Ruben Metcalfe from quote to hand-over.',
                'description'      => <<<'HTML'
<p>RDM Developments handles building work across Pretoria East — from Garsfontein and Faerie Glen to Moreleta Park, Woodhill and Silver Lakes. Every job is personally supervised by the owner, Ruben Metcalfe, so quotes stay honest, timelines stay realistic, and the finish matches what you agreed to.</p>
<h3>Building services we offer</h3>
<ul>
  <li>Masonry and brickwork</li>
  <li>Home additions and alterations</li>
  <li>Ceilings, cornices and bulkheads</li>
  <li>General construction and structural work</li>
  <li>Boundary walls and retaining walls</li>
  <li>Patios and outdoor living structures</li>
</ul>
<h3>Owner-supervised, Pretoria East–focused</h3>
<p>We stay deliberately small so every build is personally supervised by Ruben — from foundations to final clean-up. Clear scope, realistic timelines and honest payment milestones.</p>
HTML,
                'seo_title'        => 'Building Contractors Pretoria East | RDM Developments',
                'meta_description' => 'Owner-managed building in Pretoria East — masonry, additions, alterations, ceilings and general construction. Personally supervised by Ruben Metcalfe.',
                'sort_order'       => 10,
            ],
            [
                'title'            => 'Bathroom Renovations Pretoria East',
                'slug'             => 'bathroom-renovations-pretoria-east',
                'tagline'          => 'Complete bathroom renovations, including the plumbing.',
                'icon'             => 'bath',
                'excerpt'          => 'Full-scope bathroom renovations in Pretoria East — including plumbing, waterproofing, tiling, vanities, showers and finishes. Honest quotes, clean finishes, personally supervised by Ruben.',
                'description'      => <<<'HTML'
<p>RDM Developments handles complete bathroom renovations across Pretoria East — from Garsfontein and Faerie Glen to Moreleta Park, Woodhill and Silver Lakes. Every job is personally supervised by the owner, Ruben Metcalfe, so quotes stay honest, timelines stay realistic, and the finish matches what you agreed to.</p>
<h3>What's included in a bathroom renovation</h3>
<ul>
  <li>Strip-out, rubble removal and site protection</li>
  <li>Plumbing — hot/cold lines, drains, mixers and geysers</li>
  <li>Waterproofing of showers, wet walls and floors</li>
  <li>Wall and floor tiling to your chosen finish</li>
  <li>New vanities, showers, baths, toilets and mirrors</li>
  <li>Full site clean-up and hand-over</li>
</ul>
<p>We do not offer electrical work. Where a bathroom renovation needs electrical work, you appoint your own registered electrician.</p>
<h3>Why homeowners in Pretoria East choose RDM</h3>
<p>We stay small on purpose. Ruben runs every bathroom we renovate, which means you deal with the owner — not a call centre, not a project manager juggling 20 sites. The result is honest communication, fewer surprises, and a bathroom that's properly finished rather than rushed to the next job.</p>
HTML,
                'seo_title'        => 'Bathroom Renovations Pretoria East | RDM Developments',
                'meta_description' => 'Owner-managed bathroom renovations in Pretoria East, including plumbing. Honest quotes, clean finishes — Garsfontein, Faerie Glen, Moreleta Park and surrounds.',
                'sort_order'       => 20,
            ],
            [
                'title'            => 'Tiling Pretoria East',
                'slug'             => 'tiling-pretoria-east',
                'tagline'          => 'Wall and floor tiling, done properly.',
                'icon'             => 'grid',
                'excerpt'          => 'Professional wall and floor tiling in Pretoria East — bathrooms, living areas and outdoor spaces. Proper prep, clean lines and a tidy site.',
                'description'      => <<<'HTML'
<p>RDM Developments provides wall and floor tiling across Pretoria East. Whether it's a bathroom, living area or outdoor space, we prepare the substrate properly so the finish lasts — not just looks good on day one.</p>
<h3>Tiling work we handle</h3>
<ul>
  <li>Wall and floor tiling</li>
  <li>Bathroom and wet-area tiling</li>
  <li>Living areas and passages</li>
  <li>Outdoor and patio tiling</li>
  <li>Tile repairs and replacements</li>
  <li>Proper substrate prep and waterproofing where required</li>
</ul>
<p>Quotes cover preparation and finish — not just the visible tile work at the end.</p>
HTML,
                'seo_title'        => 'Tiling Pretoria East | RDM Developments',
                'meta_description' => 'Professional wall and floor tiling in Pretoria East. Proper prep, clean finishes, personally supervised by RDM Developments.',
                'sort_order'       => 30,
            ],
            [
                'title'            => 'Waterproofing Pretoria East',
                'slug'             => 'waterproofing-pretoria-east',
                'tagline'          => 'Roof, wall and wet-area waterproofing that lasts.',
                'icon'             => 'droplet',
                'excerpt'          => 'Roof, wall and wet-area waterproofing in Pretoria East. Proper prep, quality products and work personally supervised by Ruben Metcalfe.',
                'description'      => <<<'HTML'
<p>Waterproofing is only as good as the preparation behind it. RDM Developments takes the proper-prep approach — cleaning, crack repair and quality products — so the waterproofing on your Pretoria East home lasts for years rather than a season or two.</p>
<h3>Waterproofing services</h3>
<ul>
  <li>Roof waterproofing — flat roofs, IBR and tile</li>
  <li>Wall waterproofing and rising damp treatments</li>
  <li>Shower and wet-area waterproofing</li>
  <li>Parapet walls, box gutters and flashings</li>
  <li>Crack sealing prior to waterproofing</li>
</ul>
<p>We work throughout Pretoria East and neighbouring suburbs, with clear quotes that cover every step of the job.</p>
HTML,
                'seo_title'        => 'Waterproofing Pretoria East | RDM Developments',
                'meta_description' => 'Roof, wall and wet-area waterproofing in Pretoria East. Proper prep, quality products, personally supervised by RDM Developments.',
                'sort_order'       => 40,
            ],
            [
                'title'            => 'Painting Pretoria East',
                'slug'             => 'painting-pretoria-east',
                'tagline'          => 'Interior and exterior painting, properly prepared.',
                'icon'             => 'paintbrush',
                'excerpt'          => 'Interior and exterior painting in Pretoria East. Proper prep, quality products and a clean, tidy site from start to finish.',
                'description'      => <<<'HTML'
<p>Paint is only ever as good as the preparation behind it. RDM Developments takes the proper-prep approach — cleaning, crack repair, priming and quality products — so the paint on your Pretoria East home lasts for years rather than a season or two.</p>
<h3>Painting services</h3>
<ul>
  <li>Interior painting — walls, ceilings, trim</li>
  <li>Exterior painting — walls, fascias, boundary walls</li>
  <li>Crack sealing and plaster repair prior to paint</li>
  <li>Priming and surface preparation</li>
</ul>
<p>The quote you get covers every step of the job — not just the pretty finish at the end.</p>
HTML,
                'seo_title'        => 'Painting Pretoria East | RDM Developments',
                'meta_description' => 'Interior and exterior painting in Pretoria East. Proper prep, quality products, personally supervised by RDM Developments.',
                'sort_order'       => 50,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['is_published' => true]
            );
        }

        // Retire services that no longer match what the business offers.
        // Unpublish (don't delete) so inbound links don't hard-404 from the
        // DB side — the controller already aborts 404 for unpublished rows.
        Service::whereIn('slug', [
            'home-renovations-pretoria-east',
            'building-contractors-pretoria-east',
            'custom-building-projects-pretoria-east',
            'painting-waterproofing-pretoria-east',
            'garage-conversions-pretoria-east',
        ])->update(['is_published' => false]);
    }
}
