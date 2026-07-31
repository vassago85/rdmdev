<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('status', 20)->default('new')->after('source');
            $table->date('follow_up_at')->nullable()->after('status');
            $table->text('notes')->nullable()->after('follow_up_at');

            $table->index('status');
            $table->index('follow_up_at');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['follow_up_at']);
            $table->dropColumn(['status', 'follow_up_at', 'notes']);
        });
    }
};
