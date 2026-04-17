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
                'icon'             => 'shower',
                'excerpt'          => 'Full-scope bathroom renovations in Pretoria East — from plumbing and tiling to fitting vanities, showers and finishes. Honest pricing, tidy sites, personally supervised by Ruben.',
                'description'      => <<<'HTML'
<p>Looking for a reliable contractor for your bathroom renovation in Pretoria East? RDM Developments specialises in full bathroom makeovers across Pretoria East suburbs like Garsfontein, Faerie Glen, Moreleta Park, Woodhill and Silver Lakes.</p>
<p>We handle every element of your bathroom renovation in-house or with trusted tradesmen that we have worked with for years — from stripping out, waterproofing, plumbing and electrical, through to tiling, new vanities, showers, glass, lighting and final finishes.</p>
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
<p>As a small, owner-managed business, Ruben Metcalfe is on-site and personally responsible for every bathroom we renovate. You deal with the owner directly — not a call centre, not a project manager juggling 20 sites — which means honest quotes, fewer surprises and a finish you can be proud of.</p>
HTML,
                'seo_title'        => 'Bathroom Renovations Pretoria East | RDM Developments',
                'meta_description' => 'Professional bathroom renovations in Pretoria East by owner-managed RDM Developments. Honest quotes, quality workmanship, personally supervised by Ruben Metcalfe.',
                'sort_order'       => 10,
            ],
            [
                'title'            => 'Home Renovations Pretoria East',
                'slug'             => 'home-renovations-pretoria-east',
                'tagline'          => 'Transform your home, from single rooms to full remodels.',
                'icon'             => 'home',
                'excerpt'          => 'Full home renovations across Pretoria East. Kitchens, living areas, open-plan remodels, flooring, ceilings and finishes — managed personally from quote to hand-over.',
                'description'      => <<<'HTML'
<p>RDM Developments is a Pretoria East–based home renovation company specialising in remodels that actually get finished on time and on budget. Whether you're opening up walls, redoing a kitchen, replacing flooring or upgrading an entire interior, we manage the job end-to-end.</p>
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
<p>We don't spread ourselves across the whole of Gauteng. By staying focused on Pretoria East — Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Silver Lakes, Olympus and surrounding areas — we can be on-site quickly, respond to issues the same day, and build long-term relationships with homeowners.</p>
HTML,
                'seo_title'        => 'Home Renovations Pretoria East | RDM Developments',
                'meta_description' => 'Owner-managed home renovations in Pretoria East. Kitchens, flooring, open-plan remodels and full interior upgrades. Honest, reliable, personally supervised.',
                'sort_order'       => 20,
            ],
            [
                'title'            => 'Building Contractors Pretoria East',
                'slug'             => 'building-contractors-pretoria-east',
                'tagline'          => 'Small builds, additions and alterations, properly managed.',
                'icon'             => 'hammer',
                'excerpt'          => 'Reliable building contractors in Pretoria East for additions, alterations, second storeys, granny flats and boundary work. Small enough to care, experienced enough to deliver.',
                'description'      => <<<'HTML'
<p>Finding a reliable building contractor in Pretoria East is difficult — too many builders take on more than they can manage, and homeowners pay the price. RDM Developments stays deliberately small so that every build we take on is personally supervised by the owner, Ruben Metcalfe.</p>
<h3>Building services we offer</h3>
<ul>
  <li>Home additions and extensions</li>
  <li>Second-storey additions</li>
  <li>Granny flats, cottages and garden suites</li>
  <li>Structural alterations</li>
  <li>Boundary walls and retaining walls</li>
  <li>Patios, braai rooms and outdoor living</li>
</ul>
<p>We work across Pretoria East — Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Olympus, Wapadrand, Silver Lakes — delivering honest quotes with clear scope, timelines and payment milestones.</p>
HTML,
                'seo_title'        => 'Building Contractors Pretoria East | RDM Developments',
                'meta_description' => 'Trusted building contractors in Pretoria East for additions, alterations, granny flats and boundary walls. Owner-managed by Ruben Metcalfe.',
                'sort_order'       => 30,
            ],
            [
                'title'            => 'Garage Conversions Pretoria East',
                'slug'             => 'garage-conversions-pretoria-east',
                'tagline'          => 'Turn your garage into a flat, office or living space.',
                'icon'             => 'door',
                'excerpt'          => 'Convert single or double garages in Pretoria East into a flatlet, home office, studio or additional living space — fully finished, compliant and ready to use.',
                'description'      => <<<'HTML'
<p>Garage conversions are one of the fastest ways to add value and usable space to a Pretoria East home. RDM Developments converts single and double garages into flatlets, offices, studios, spare bedrooms or open living spaces — complete with plumbing, electrical and finishes.</p>
<h3>What a garage conversion includes</h3>
<ul>
  <li>Insulation and interior wall framing</li>
  <li>Replacement of the garage door with wall, windows or sliding doors</li>
  <li>Floor levelling and new flooring</li>
  <li>Ceiling, lighting and power points</li>
  <li>Optional plumbing for a kitchenette or bathroom</li>
  <li>Full interior finishes — paint, trim, skirting</li>
</ul>
<p>We serve Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Olympus and the broader Pretoria East market, with honest pricing and a finish that looks like it was always part of the house.</p>
HTML,
                'seo_title'        => 'Garage Conversions Pretoria East | RDM Developments',
                'meta_description' => 'Garage conversions in Pretoria East — flatlets, offices, studios and living spaces. Personally supervised by RDM Developments.',
                'sort_order'       => 40,
            ],
            [
                'title'            => 'Painting & Waterproofing Pretoria East',
                'slug'             => 'painting-waterproofing-pretoria-east',
                'tagline'          => 'Interior & exterior painting, roof and wall waterproofing.',
                'icon'             => 'brush',
                'excerpt'          => 'Professional interior and exterior painting plus roof and wall waterproofing in Pretoria East. Proper prep, quality products and a clean site.',
                'description'      => <<<'HTML'
<p>Paint and waterproofing are only as good as the preparation behind them. RDM Developments takes the long-route approach — proper cleaning, crack repair, priming and quality products — so your paint and waterproofing last years, not seasons.</p>
<h3>Services we provide</h3>
<ul>
  <li>Interior painting — walls, ceilings, trim</li>
  <li>Exterior painting — walls, fascias, boundary walls</li>
  <li>Roof waterproofing — flat roofs, IBR and tile</li>
  <li>Wall waterproofing and rising damp treatments</li>
  <li>Parapet walls, box gutters and flashings</li>
  <li>Crack sealing and plaster repair prior to paint</li>
</ul>
<p>We work throughout Pretoria East and neighbouring suburbs, offering homeowners a no-nonsense quote that covers every step of the job — not just the pretty finish at the end.</p>
HTML,
                'seo_title'        => 'Painting & Waterproofing Pretoria East | RDM Developments',
                'meta_description' => 'Painting and waterproofing specialists in Pretoria East. Interior, exterior, roof and wall solutions, personally supervised by RDM Developments.',
                'sort_order'       => 50,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(['slug' => $data['slug']], $data + ['is_published' => true]);
        }
    }
}
