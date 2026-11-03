<?php

namespace App\Models;

use App\Support\ResponsiveImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): string
    {
        return asset('storage/' . $this->path);
    }

    /** @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool} */
    public function picture(): array
    {
        return ResponsiveImage::fromStoragePath($this->path);
    }
}
