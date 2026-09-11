<?php

namespace Database\Seeders;

use App\Models\PageSetting;
use Illuminate\Database\Seeder;

class PageSettingSeeder extends Seeder
{
    public function run(): void
    {
        PageSetting::flushCache();
        PageSetting::current();
    }
}
