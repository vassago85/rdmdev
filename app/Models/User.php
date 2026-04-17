<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Any user in the users table is allowed into the Filament admin panel.
     * This is a small, owner-run site — the only account we create is the
     * owner's. If that ever changes, tighten this (e.g. role check or
     * ->hasVerifiedEmail()).
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
