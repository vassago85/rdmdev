<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            // Stored relative paths (public disk) of photos the homeowner
            // attached to their enquiry — e.g. a snap of a leaking roof.
            $table->json('photos')->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn('photos');
        });
    }
};
