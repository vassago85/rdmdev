<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The `app_settings` table was originally created with SMTP columns
 * (mail_host / mail_port / mail_username / mail_password / mail_encryption)
 * and was later rewritten in-place to use Mailgun HTTP API columns instead.
 * Laravel tracks migrations by filename, so any server that ran the original
 * version of `create_app_settings_table.php` will NOT pick up the rewrite —
 * it'll still have the old SMTP columns and the save form will 500 with
 * "Unknown column 'mailgun_domain'".
 *
 * This migration brings any already-deployed schema in line with the new
 * Mailgun model. It uses `Schema::hasColumn` guards so it's a safe no-op on
 * fresh installs that already created the table with the right columns.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('app_settings')) {
            return;
        }

        // ─── Drop legacy SMTP columns if they're still around ──────────────
        $smtpColumns = [
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
        ];

        $toDrop = array_values(array_filter(
            $smtpColumns,
            fn (string $col) => Schema::hasColumn('app_settings', $col),
        ));

        if (! empty($toDrop)) {
            Schema::table('app_settings', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }

        // ─── Add Mailgun columns if missing ────────────────────────────────
        Schema::table('app_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('app_settings', 'mailgun_domain')) {
                $table->string('mailgun_domain')->nullable()->after('mailer');
            }
            if (! Schema::hasColumn('app_settings', 'mailgun_secret')) {
                $table->text('mailgun_secret')->nullable()->after('mailgun_domain');
            }
            if (! Schema::hasColumn('app_settings', 'mailgun_endpoint')) {
                $table->string('mailgun_endpoint')->default('api.mailgun.net')->after('mailgun_secret');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('app_settings')) {
            return;
        }

        Schema::table('app_settings', function (Blueprint $table) {
            $mailgunColumns = ['mailgun_domain', 'mailgun_secret', 'mailgun_endpoint'];
            $toDrop = array_values(array_filter(
                $mailgunColumns,
                fn (string $col) => Schema::hasColumn('app_settings', $col),
            ));

            if (! empty($toDrop)) {
                $table->dropColumn($toDrop);
            }

            if (! Schema::hasColumn('app_settings', 'mail_host')) {
                $table->string('mail_host')->nullable();
                $table->unsignedSmallInteger('mail_port')->nullable();
                $table->string('mail_username')->nullable();
                $table->text('mail_password')->nullable();
                $table->string('mail_encryption', 16)->nullable();
            }
        });
    }
};
