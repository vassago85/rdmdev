<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel, HasColor
{
    case Admin = 'admin';
    case Editor = 'editor';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin  => 'Administrator',
            self::Editor => 'Editor',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Admin  => 'primary',
            self::Editor => 'gray',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Admin  => 'Full access, including deleting records and managing users.',
            self::Editor => 'Can manage content and enquiries, but cannot delete records or manage users.',
        };
    }
}
