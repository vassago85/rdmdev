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
                'title'            => 'Bathroom Renovations Pretoria East',
                'slug'             => 'bathroom-renovations-pretoria-east',
                'tagline'          => 'Complete bathroom renovations, done right the first time.',
                'icon'             => 'bath',
                'excerpt'          => 'Full-scope bathroom renovations in Pretoria East — plumbing, waterproofing, tiling, vanities, showers and finishes. Honest quotes, clean finishes, and work personally supervised by Ruben.',
                'description'      => <<<'HTML'
<p>RDM Developments handles complete bathroom renovations across Pretoria East — from Garsfontein and Faerie Glen to Moreleta Park, Woodhill and Silver Lakes. Every job is personally supervised by the owner, Ruben Metcalfe, so quotes stay honest, timelines stay realistic, and the finish matches what you agreed to.</p>
<h3>What's included in a bathroom renovation</h3>
<ul>
  <li>Strip-out, rubble removal and site protection</li>
  <li>Plumbing rework — hot/cold lines, drains, mixers, geysers</li>
  <li>Waterproofing of showers, wet walls and floors</li>
  <li>Wall and floor tiling to your chosen finish</li>
  <li>New vanities, showers, baths, toilets, mirrors and lighting</li>
  <li>Full site clean-up and hand-over</li>
</ul>
<h3>Why homeowners in Pretoria East choose RDM</h3>
<p>We stay small on purpose. Ruben runs every bathroom we renovate, which means you deal with the owner — not a call centre, not a project manager juggling 20 sites. The result is honest communication, fewer surprises, and a bathroom that's properly finished rather than rushed to the next job.</p>
HTML,
                'seo_title'        => 'Bathroom Renovations Pretoria East | RDM Developments',
                'meta_description' => 'Owner-managed bathroom renovations in Pretoria East. Honest quotes, clean finishes, personally supervised by Ruben Metcalfe — Garsfontein, Faerie Glen, Moreleta Park and surrounds.',
                'sort_order'       => 10,
            ],
            [
                'title'            => 'Home Renovations Pretoria East',
                'slug'             => 'home-renovations-pretoria-east',
                'tagline'          => 'Full home renovations and remodels, done to a proper finish.',
                'icon'             => 'house',
                'excerpt'          => 'Full home renovations across Pretoria East — kitchens, open-plan remodels, flooring, ceilings and interior finishes. Managed personally from quote to hand-over.',
                'description'      => <<<'HTML'
<p>RDM Developments is a Pretoria East home renovation specialist focused on remodels that actually get finished — on time, on budget, and to the finish you were promised. Whether you're opening up walls, replacing a kitchen, or giving an older home a proper refresh, we manage the job end-to-end.</p>
<h3>Typical home renovation scope</h3>
<ul>
  <li>Kitchen renovations and upgrades</li>
  <li>Open-plan remodels and wall removals</li>
  <li>New flooring — tile, laminate, vinyl</li>
  <li>Ceilings, cornices, bulkheads and lighting</li>
  <li>Interior and exterior painting</li>
  <li>Plumbing and electrical upgrades</li>
</ul>
<h3>Owner-supervised, Pretoria East–focused</h3>
<p>We don't spread ourselves across the whole of Gauteng. By staying focused on Pretoria East — Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Silver Lakes, Olympus and surrounding suburbs — we can be on-site quickly, respond to issues the same day, and build the kind of reputation that leads to referrals rather than online complaints.</p>
HTML,
                'seo_title'        => 'Home Renovations Pretoria East | RDM Developments',
                'meta_description' => 'Owner-managed home renovations in Pretoria East. Kitchens, flooring, open-plan remodels and interior upgrades — honest, reliable, personally supervised.',
                'sort_order'       => 20,
            ],
            [
                'title'            => 'Building Contractors Pretoria East',
                'slug'             => 'building-contractors-pretoria-east',
                'tagline'          => 'Small builds, additions and alterations — properly managed.',
                'icon'             => 'hammer',
                'excerpt'          => 'Reliable building contractors in Pretoria East for additions, alterations, second storeys, granny flats and boundary work. Small enough to care, experienced enough to deliver.',
                'description'      => <<<'HTML'
<p>Finding a reliable building contractor in Pretoria East is harder than it should be — too many builders take on more than they can manage and the homeowner ends up paying for it. RDM Developments stays deliberately small so every build is personally supervised by the owner, Ruben Metcalfe, from foundations to final clean-up.</p>
<h3>Building services we offer</h3>
<ul>
  <li>Home additions and extensions</li>
  <li>Second-storey additions</li>
  <li>Granny flats, cottages and garden suites</li>
  <li>Structural alterations</li>
  <li>Boundary walls and retaining walls</li>
  <li>Patios, braai rooms and outdoor living</li>
</ul>
<p>We work across Pretoria East — Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Olympus, Wapadrand and Silver Lakes — with clear scope, realistic timelines and honest payment milestones.</p>
HTML,
                'seo_title'        => 'Building Contractors Pretoria East | RDM Developments',
                'meta_description' => 'Reliable building contractors in Pretoria East for additions, alterations, granny flats and boundary walls. Owner-managed by Ruben Metcalfe.',
                'sort_order'       => 30,
            ],
            [
                'title'            => 'Custom Building Projects Pretoria East',
                'slug'             => 'custom-building-projects-pretoria-east',
                'tagline'          => 'One-off builds tailored to your home and the way you live.',
                'icon'             => 'layers',
                'excerpt'          => 'Custom building projects in Pretoria East — garage conversions, flatlets, studios, braai rooms, outdoor living and other one-off work tailored to your property.',
                'description'      => <<<'HTML'
<p>Not every job fits into a standard category. RDM Developments takes on custom building projects across Pretoria East — the additions, conversions and standalone structures that need a builder who's willing to plan them properly rather than force a template onto your home.</p>
<h3>Custom projects we typically handle</h3>
<ul>
  <li>Garage conversions — flatlets, home offices, studios and additional living space</li>
  <li>Self-contained cottages and outbuildings</li>
  <li>Braai rooms, outdoor kitchens and covered patios</li>
  <li>Feature walls, built-ins and bespoke joinery</li>
  <li>Carports, pergolas and boundary features</li>
  <li>Small commercial fit-outs</li>
</ul>
<h3>Properly planned, personally run</h3>
<p>Custom work lives or dies on the detail. That's why Ruben personally scopes, quotes and runs these projects — making sure the finish matches the existing house, the paperwork is in order where it needs to be, and the new space actually does what you need it to do.</p>
HTML,
                'seo_title'        => 'Custom Building Projects Pretoria East | RDM Developments',
                'meta_description' => 'Custom building projects in Pretoria East — garage conversions, flatlets, studios, braai rooms and one-off work. Personally supervised by RDM Developments.',
                'sort_order'       => 40,
            ],
            [
                'title'            => 'Painting & Waterproofing Pretoria East',
                'slug'             => 'painting-waterproofing-pretoria-east',
                'tagline'          => 'Interior & exterior painting, roof and wall waterproofing.',
                'icon'             => 'paintbrush',
                'excerpt'          => 'Professional interior and exterior painting plus roof and wall waterproofing in Pretoria East. Proper prep, quality products and a clean, tidy site from start to finish.',
                'description'      => <<<'HTML'
<p>Paint and waterproofing are only ever as good as the preparation behind them. RDM Developments takes the proper-prep approach — cleaning, crack repair, priming, and quality products — so the paint and waterproofing on your Pretoria East home lasts for years rather than a season or two.</p>
<h3>Services we provide</h3>
<ul>
  <li>Interior painting — walls, ceilings, trim</li>
  <li>Exterior painting — walls, fascias, boundary walls</li>
  <li>Roof waterproofing — flat roofs, IBR and tile</li>
  <li>Wall waterproofing and rising damp treatments</li>
  <li>Parapet walls, box gutters and flashings</li>
  <li>Crack sealing and plaster repair prior to paint</li>
</ul>
<p>We work throughout Pretoria East and neighbouring suburbs, and the quote you get covers every step of the job — not just the pretty finish at the end.</p>
HTML,
                'seo_title'        => 'Painting & Waterproofing Pretoria East | RDM Developments',
                'meta_description' => 'Painting and waterproofing specialists in Pretoria East. Interior, exterior, roof and wall solutions, personally supervised by RDM Developments.',
                'sort_order'       => 50,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(['slug' => $data['slug']], $data + ['is_published' => true]);
        }

        // Retire the old "Garage Conversions" service — its content now lives
        // inside the broader "Custom Building Projects" service. We unpublish
        // rather than delete so any inbound links don't 404 immediately;
        // the controller aborts 404 for unpublished records but search
        // engines will have a chance to pick up the new canonical.
        Service::where('slug', 'garage-conversions-pretoria-east')
            ->update(['is_published' => false]);
    }
}
