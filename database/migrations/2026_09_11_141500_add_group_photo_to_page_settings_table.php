<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            $table->string('team_group_photo')->nullable();
            $table->string('team_group_photo_caption')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            $table->dropColumn(['team_group_photo', 'team_group_photo_caption']);
        });
    }
};
