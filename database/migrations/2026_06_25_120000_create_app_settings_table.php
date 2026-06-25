<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();

            // ─── Email (SMTP) ──────────────────────────────────────────────
            $table->string('mailer', 32)->default('smtp');
            $table->string('mail_host')->nullable();
            $table->unsignedSmallInteger('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            // Encrypted at rest via the model's `encrypted` cast.
            $table->text('mail_password')->nullable();
            // 'tls', 'ssl', or NULL/empty for no encryption.
            $table->string('mail_encryption', 16)->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();
            // Where contact-form enquiries get emailed.
            $table->string('enquiry_to')->nullable();

            // ─── Push notifications (ntfy.sh) ─────────────────────────────
            $table->boolean('ntfy_enabled')->default(false);
            $table->string('ntfy_server')->nullable();
            $table->string('ntfy_topic')->nullable();
            // Encrypted at rest. Sent as a bearer token in the Authorization
            // header — works for ntfy.sh access tokens (tk_...) or any other
            // bearer-style auth on a self-hosted instance.
            $table->text('ntfy_token')->nullable();
            // 1=min … 3=default … 5=max. NULL = let ntfy pick its default.
            $table->unsignedTinyInteger('ntfy_priority')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
