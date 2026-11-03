<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\TeamMember;
use App\Support\ResponsiveImage;
use Illuminate\Console\Command;

class GenerateImageVariants extends Command
{
    protected $signature = 'images:variants';

    protected $description = 'Write WebP width variants for project and owner photos';

    public function handle(): int
    {
        $owner = public_path('images/ruben-metcalfe.jpg');
        ResponsiveImage::fromPublicRelative('images/ruben-metcalfe.jpg');
        ResponsiveImage::avatar($owner, asset('images/ruben-metcalfe.jpg'), 96);

        $picture = ResponsiveImage::fromPublicRelative('images/ruben-metcalfe.jpg');

        if (($picture['below_1200'] ?? false) === true) {
            $this->warn('Owner photo is '.$picture['original_width'].'px wide, under 1200px. The large homepage photo is not upscaled.');
        }

        Project::query()->select(['id', 'featured_image', 'cover_image'])->each(function (Project $project) {
            ResponsiveImage::fromStoragePath($project->featured_image);
            ResponsiveImage::fromStoragePath($project->cover_image);
        });

        ProjectImage::query()->select(['id', 'path'])->each(function (ProjectImage $image) {
            ResponsiveImage::fromStoragePath($image->path);
        });

        TeamMember::query()->whereNotNull('photo')->select(['id', 'photo'])->each(function (TeamMember $member) {
            ResponsiveImage::fromStoragePath($member->photo);
            $absolute = storage_path('app/public/'.$member->photo);
            ResponsiveImage::avatar($absolute, asset('storage/'.$member->photo), 96);
        });

        $this->info('Image variants are ready.');

        return self::SUCCESS;
    }
}
