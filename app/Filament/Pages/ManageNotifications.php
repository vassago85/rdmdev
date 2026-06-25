<?php

namespace App\Filament\Pages;

use App\Models\AppSetting;
use App\Services\NtfyService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class ManageNotifications extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $title = 'Notifications';

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?string $slug = 'notifications';

    protected static string $view = 'filament.pages.manage-notifications';

    protected static ?int $navigationSort = 90;

    public ?array $data = [];

    public function mount(): void
    {
        $row = AppSetting::current();

        $this->form->fill([
            'mailer'             => $row->mailer ?: 'smtp',
            'mail_host'          => $row->mail_host,
            'mail_port'          => $row->mail_port,
            'mail_username'      => $row->mail_username,
            'mail_password'      => $row->mail_password,
            'mail_encryption'    => $row->mail_encryption,
            'mail_from_address'  => $row->mail_from_address,
            'mail_from_name'     => $row->mail_from_name,
            'enquiry_to'         => $row->enquiry_to ?: config('rdm.enquiry_to'),
            'ntfy_enabled'       => $row->ntfy_enabled,
            'ntfy_server'        => $row->ntfy_server ?: 'https://ntfy.sh',
            'ntfy_topic'         => $row->ntfy_topic,
            'ntfy_token'         => $row->ntfy_token,
            'ntfy_priority'      => $row->ntfy_priority ?: 4,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Tabs::make('settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Email (SMTP)')
                            ->icon('heroicon-o-envelope')
                            ->schema($this->mailSchema()),

                        Forms\Components\Tabs\Tab::make('Push (ntfy)')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->schema($this->ntfySchema()),
                    ])->columnSpanFull(),
            ]);
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function mailSchema(): array
    {
        return [
            Forms\Components\Section::make('SMTP server')
                ->description('For Mailgun: host = smtp.mailgun.org (or smtp.eu.mailgun.org), port = 587, encryption = TLS, username = postmaster@mg.yourdomain, password = the SMTP password from Mailgun → Sending → Domain settings → SMTP credentials.')
                ->schema([
                    Forms\Components\Select::make('mailer')
                        ->label('Driver')
                        ->options([
                            'smtp' => 'SMTP',
                            'log'  => 'Log (write to laravel.log, don\'t send) — for testing',
                        ])
                        ->required()
                        ->native(false),
                    Forms\Components\TextInput::make('mail_host')
                        ->label('SMTP host')
                        ->placeholder('smtp.mailgun.org')
                        ->maxLength(191),
                    Forms\Components\TextInput::make('mail_port')
                        ->label('Port')
                        ->numeric()
                        ->placeholder('587'),
                    Forms\Components\Select::make('mail_encryption')
                        ->label('Encryption')
                        ->options([
                            ''    => 'None',
                            'tls' => 'TLS (recommended for port 587)',
                            'ssl' => 'SSL (port 465)',
                        ])
                        ->native(false),
                    Forms\Components\TextInput::make('mail_username')
                        ->label('SMTP username')
                        ->autocomplete('off')
                        ->maxLength(191),
                    Forms\Components\TextInput::make('mail_password')
                        ->label('SMTP password')
                        ->password()
                        ->revealable()
                        ->autocomplete('new-password')
                        ->maxLength(500)
                        ->helperText('Stored encrypted in the database.'),
                ])->columns(2),

            Forms\Components\Section::make('From address')
                ->description('What the outgoing emails will be sent as. Most providers require the From address to be on a domain you own and have verified with them.')
                ->schema([
                    Forms\Components\TextInput::make('mail_from_address')
                        ->label('From email')
                        ->email()
                        ->placeholder('no-reply@rdmdev.co.za')
                        ->maxLength(191),
                    Forms\Components\TextInput::make('mail_from_name')
                        ->label('From name')
                        ->placeholder('RDM Developments')
                        ->maxLength(191),
                ])->columns(2),

            Forms\Components\Section::make('Enquiry delivery')
                ->description('Where contact-form enquiries from the website are emailed to.')
                ->schema([
                    Forms\Components\TextInput::make('enquiry_to')
                        ->label('Send enquiries to')
                        ->email()
                        ->placeholder('ruben@rdmdev.co.za')
                        ->required()
                        ->maxLength(191),
                ]),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function ntfySchema(): array
    {
        return [
            Forms\Components\Section::make('ntfy.sh push notifications')
                ->description('When enabled, the owner gets an instant push notification on their phone every time a new enquiry comes in. Install the "ntfy" app on Android / iOS, subscribe to your topic, and you\'re done — no app store account, no server-side setup beyond what\'s here.')
                ->schema([
                    Forms\Components\Toggle::make('ntfy_enabled')
                        ->label('Enable push notifications')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('ntfy_server')
                        ->label('Server URL')
                        ->placeholder('https://ntfy.sh')
                        ->url()
                        ->maxLength(191)
                        ->helperText('Use https://ntfy.sh unless you self-host.'),

                    Forms\Components\TextInput::make('ntfy_topic')
                        ->label('Topic name')
                        ->placeholder('rdmdev-enquiries-' . substr(bin2hex(random_bytes(4)), 0, 8))
                        ->maxLength(64)
                        ->helperText('Anything goes — but treat it like a password (anyone who knows the topic can send to it on ntfy.sh). Make it long and unguessable.')
                        ->required(fn (Forms\Get $get) => (bool) $get('ntfy_enabled')),

                    Forms\Components\Select::make('ntfy_priority')
                        ->label('Default priority')
                        ->options([
                            1 => '1 — Min (silent)',
                            2 => '2 — Low',
                            3 => '3 — Default',
                            4 => '4 — High (recommended)',
                            5 => '5 — Max (urgent alert)',
                        ])
                        ->native(false),

                    Forms\Components\TextInput::make('ntfy_token')
                        ->label('Access token (optional)')
                        ->password()
                        ->revealable()
                        ->autocomplete('new-password')
                        ->maxLength(500)
                        ->helperText('Only needed if your topic requires authentication (e.g. a paid ntfy.sh plan with reserved topics, or a self-hosted instance). Stored encrypted.')
                        ->columnSpanFull(),
                ])->columns(2),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        AppSetting::query()->updateOrCreate(['id' => 1], $data);

        Notification::make()
            ->title('Settings saved')
            ->body('Changes are live immediately — no restart required.')
            ->success()
            ->send();
    }

    /**
     * Send a one-off test email using the values currently in the form (which
     * may be unsaved). This lets us validate new credentials BEFORE persisting
     * them and accidentally breaking the live mailer.
     */
    public function sendTestEmailAction(): Action
    {
        return Action::make('sendTestEmail')
            ->label('Send test email')
            ->icon('heroicon-o-paper-airplane')
            ->color('gray')
            ->form([
                Forms\Components\TextInput::make('to')
                    ->label('Send test to')
                    ->email()
                    ->required()
                    ->default(fn () => $this->data['enquiry_to'] ?? config('rdm.enquiry_to')),
            ])
            ->action(function (array $data): void {
                $this->applyMailFormStateToConfig();

                try {
                    Mail::raw(
                        "This is a test email from the RDM Developments admin panel.\n\n"
                        . 'Sent at ' . now()->format('d M Y H:i:s') . ' SAST.',
                        function ($message) use ($data) {
                            $message->to($data['to'])->subject('RDM Developments mail test');
                        }
                    );

                    Notification::make()
                        ->title('Test email sent')
                        ->body('Delivered to ' . $data['to'] . '. Check the inbox (and spam).')
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Test email failed')
                        ->body($e->getMessage())
                        ->danger()
                        ->persistent()
                        ->send();
                }
            });
    }

    /**
     * Send a test ntfy push using the current (possibly unsaved) form values.
     */
    public function sendTestPushAction(): Action
    {
        return Action::make('sendTestPush')
            ->label('Send test push')
            ->icon('heroicon-o-device-phone-mobile')
            ->color('gray')
            ->action(function (NtfyService $ntfy): void {
                $state = $this->form->getState();

                $server = $state['ntfy_server'] ?: 'https://ntfy.sh';
                $topic  = $state['ntfy_topic'] ?? null;
                $token  = $state['ntfy_token'] ?? null;

                if (empty($topic)) {
                    Notification::make()
                        ->title('Set a topic first')
                        ->body('Enter a topic name on the Push tab, then try again.')
                        ->warning()
                        ->send();
                    return;
                }

                try {
                    $ntfy->publish(
                        server: $server,
                        topic: $topic,
                        token: $token,
                        title: 'RDM Developments test',
                        message: 'This is a test notification from the admin panel — sent at '
                            . now()->format('d M Y H:i:s') . ' SAST.',
                        opts: [
                            'priority' => (int) ($state['ntfy_priority'] ?? 4),
                            'tags'     => ['white_check_mark'],
                            'click'    => url('/admin'),
                        ],
                    )->throw();

                    Notification::make()
                        ->title('Test push sent')
                        ->body('Subscribe to the "' . $topic . '" topic in the ntfy app to receive it.')
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Test push failed')
                        ->body($e->getMessage())
                        ->danger()
                        ->persistent()
                        ->send();
                }
            });
    }

    /**
     * Override mail config in-memory for this request using whatever the user
     * currently has in the form. Used by the "Send test email" action so a
     * test can run against unsaved values.
     */
    protected function applyMailFormStateToConfig(): void
    {
        $state = $this->form->getState();

        Config::set('mail.default', $state['mailer'] ?: 'smtp');
        Config::set('mail.mailers.smtp.host', $state['mail_host'] ?: null);
        Config::set('mail.mailers.smtp.port', $state['mail_port'] ?: null);
        Config::set('mail.mailers.smtp.username', $state['mail_username'] ?: null);
        Config::set('mail.mailers.smtp.password', $state['mail_password'] ?: null);
        Config::set('mail.mailers.smtp.encryption', $state['mail_encryption'] ?: null);

        if (! empty($state['mail_from_address'])) {
            Config::set('mail.from.address', $state['mail_from_address']);
        }
        if (! empty($state['mail_from_name'])) {
            Config::set('mail.from.name', $state['mail_from_name']);
        }

        // The Mail manager caches resolved mailers per name; purge so the next
        // send() picks up our overrides instead of a previously-built transport.
        app('mail.manager')->forgetMailers();
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->sendTestEmailAction(),
            $this->sendTestPushAction(),
        ];
    }
}
