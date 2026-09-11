<?php

namespace Database\Factories;

use App\Models\PageSetting;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition(): array
    {
        return [
            'page_setting_id' => fn () => PageSetting::current()->id,
            'title'           => fake()->jobTitle(),
            'name'            => fake()->name(),
            'photo'           => null,
            'is_published'    => true,
            'sort_order'      => fake()->numberBetween(1, 6),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
