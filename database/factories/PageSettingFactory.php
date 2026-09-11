<?php

namespace Database\Factories;

use App\Models\PageSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageSetting>
 */
class PageSettingFactory extends Factory
{
    protected $model = PageSetting::class;

    public function definition(): array
    {
        return array_merge(PageSetting::defaultAttributes(), [
            'about_heading' => fake()->sentence(6),
            'seo_title'     => fake()->sentence(5),
        ]);
    }
}
