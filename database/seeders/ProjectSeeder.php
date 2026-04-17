<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title'        => 'Bathroom Renovation in Garsfontein',
                'slug'         => 'bathroom-renovation-garsfontein',
                'category'     => 'Bathroom',
                'project_type' => Project::TYPE_RENOVATION,
                'location'     => 'Garsfontein',
                'description'  => '<p>Complete main-bathroom renovation in Garsfontein, Pretoria East. Full strip-out, re-plumb, new shower with frameless glass, floor-to-ceiling tiling, new vanity and LED lighting.</p>',
                'completed_on' => now()->subMonths(2)->startOfMonth(),
                'is_featured'  => true,
                'is_published' => true,
                'sort_order'   => 10,
                'seo_title'    => 'Bathroom Renovation in Garsfontein, Pretoria East | RDM Developments',
                'meta_description' => 'A full bathroom renovation in Garsfontein, Pretoria East. See the before and after photos of this RDM Developments project.',
            ],
            [
                'title'        => 'Kitchen & Open-Plan Remodel in Faerie Glen',
                'slug'         => 'kitchen-open-plan-faerie-glen',
                'category'     => 'Kitchen',
                'project_type' => Project::TYPE_RENOVATION,
                'location'     => 'Faerie Glen',
                'description'  => '<p>Opened up a closed kitchen into the living area in Faerie Glen — new flooring, bulkhead ceiling with downlights, new cabinetry and stone tops.</p>',
                'completed_on' => now()->subMonths(5)->startOfMonth(),
                'is_featured'  => true,
                'is_published' => true,
                'sort_order'   => 20,
                'seo_title'    => 'Kitchen Renovation in Faerie Glen, Pretoria East | RDM Developments',
                'meta_description' => 'Kitchen and open-plan renovation in Faerie Glen, Pretoria East. See before and after images of this RDM Developments project.',
            ],
            [
                'title'        => 'Double Garage Conversion in Moreleta Park',
                'slug'         => 'garage-conversion-moreleta-park',
                'category'     => 'Garage Conversion',
                'project_type' => Project::TYPE_RENOVATION,
                'location'     => 'Moreleta Park',
                'description'  => '<p>Converted a double garage into a self-contained flatlet in Moreleta Park, Pretoria East — with kitchenette, bathroom and separate entrance.</p>',
                'completed_on' => now()->subMonths(8)->startOfMonth(),
                'is_featured'  => true,
                'is_published' => true,
                'sort_order'   => 30,
                'seo_title'    => 'Garage Conversion in Moreleta Park, Pretoria East | RDM Developments',
                'meta_description' => 'A double garage converted into a self-contained flatlet in Moreleta Park, Pretoria East, by RDM Developments.',
            ],
            [
                'title'        => 'Single-Storey Home Extension in Woodhill',
                'slug'         => 'home-extension-woodhill',
                'category'     => 'Extension',
                'project_type' => Project::TYPE_BUILD,
                'location'     => 'Woodhill',
                'description'  => '<p>Added a new bedroom suite and study to an existing home in Woodhill, Pretoria East — matched brickwork, roof and finishes to the existing house.</p>',
                'completed_on' => now()->subMonths(3)->startOfMonth(),
                'is_featured'  => true,
                'is_published' => true,
                'sort_order'   => 40,
                'seo_title'    => 'Home Extension in Woodhill, Pretoria East | RDM Developments',
                'meta_description' => 'A single-storey home extension in Woodhill, Pretoria East — new bedroom suite and study, by RDM Developments.',
            ],
            [
                'title'        => 'Granny Flat Build in Silver Lakes',
                'slug'         => 'granny-flat-silver-lakes',
                'category'     => 'New Build',
                'project_type' => Project::TYPE_BUILD,
                'location'     => 'Silver Lakes',
                'description'  => '<p>Full granny flat built from the ground up in Silver Lakes, Pretoria East — one-bed, one-bath with open-plan living and a covered patio.</p>',
                'completed_on' => now()->subMonths(6)->startOfMonth(),
                'is_featured'  => false,
                'is_published' => true,
                'sort_order'   => 50,
                'seo_title'    => 'Granny Flat in Silver Lakes, Pretoria East | RDM Developments',
                'meta_description' => 'Custom granny flat built in Silver Lakes, Pretoria East — by owner-managed RDM Developments.',
            ],
            [
                'title'        => 'Exterior Painting & Waterproofing in Olympus',
                'slug'         => 'painting-waterproofing-olympus',
                'category'     => 'Painting & Waterproofing',
                'project_type' => Project::TYPE_RENOVATION,
                'location'     => 'Olympus',
                'description'  => '<p>Full exterior repaint, roof waterproofing and boundary wall refresh in Olympus, Pretoria East.</p>',
                'completed_on' => now()->subMonths(1)->startOfMonth(),
                'is_featured'  => false,
                'is_published' => true,
                'sort_order'   => 60,
                'seo_title'    => 'Exterior Painting in Olympus, Pretoria East | RDM Developments',
                'meta_description' => 'Exterior painting and waterproofing in Olympus, Pretoria East by RDM Developments.',
            ],
        ];

        foreach ($projects as $p) {
            Project::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
