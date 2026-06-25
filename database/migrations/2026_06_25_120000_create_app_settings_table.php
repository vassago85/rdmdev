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

            // ─── Outgoing mail (Mailgun HTTP API) ─────────────────────────
            // 'mailgun' or 'log'. 'log' just writes to laravel.log without
            // sending — useful for local dev.
            $table->string('mailer', 32)->default('mailgun');
            // The sending domain you've added to Mailgun
            // (e.g. mg.rdmdev.co.za). NOT your website domain unless
            // you've explicitly set Mailgun up that way.
            $table->string('mailgun_domain')->nullable();
            // Mailgun "Sending API key" — encrypted at rest via the
            // model's `encrypted` cast. NOT the SMTP password.
            $table->text('mailgun_secret')->nullable();
            // api.mailgun.net (US, default) or api.eu.mailgun.net (EU region).
            $table->string('mailgun_endpoint')->default('api.mailgun.net');

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
