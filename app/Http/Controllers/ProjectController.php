<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = Project::published()->with(['images'])->ordered();

        if (in_array($type, [Project::TYPE_RENOVATION, Project::TYPE_BUILD], true)) {
            $query->where('project_type', $type);
        }

        $projects = $query->paginate(12)->withQueryString();

        return view('projects.index', [
            'projects'        => $projects,
            'activeType'      => $type,
            'pageTitle'       => 'Completed Projects in Pretoria East | RDM Developments',
            'metaDescription' => 'A gallery of completed building and renovation projects across Pretoria East — including before-and-after renovations and new builds.',
        ]);
    }

    public function show(Project $project)
    {
        abort_unless($project->is_published, 404);

        $project->load(['beforeImages', 'afterImages', 'galleryImages', 'images']);

        $related = Project::published()
            ->where('id', '!=', $project->id)
            ->when($project->project_type, fn ($q) => $q->where('project_type', $project->project_type))
            ->ordered()
            ->take(3)
            ->get();

        return view('projects.show', [
            'project'         => $project,
            'related'         => $related,
            'pageTitle'       => $project->seoTitle(),
            'metaDescription' => $project->metaDescription(),
        ]);
    }
}
