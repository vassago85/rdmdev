<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EnquiryStatus: string implements HasLabel, HasColor, HasIcon
{
    case New = 'new';
    case Contacted = 'contacted';
    case Quoted = 'quoted';
    case Won = 'won';
    case Lost = 'lost';
    case Spam = 'spam';

    public function getLabel(): string
    {
        return match ($this) {
            self::New       => 'New',
            self::Contacted => 'Contacted',
            self::Quoted    => 'Quoted',
            self::Won       => 'Won',
            self::Lost      => 'Lost',
            self::Spam      => 'Spam',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::New       => 'warning',
            self::Contacted => 'info',
            self::Quoted    => 'primary',
            self::Won       => 'success',
            self::Lost      => 'gray',
            self::Spam      => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::New       => 'heroicon-m-sparkles',
            self::Contacted => 'heroicon-m-phone',
            self::Quoted    => 'heroicon-m-document-text',
            self::Won       => 'heroicon-m-trophy',
            self::Lost      => 'heroicon-m-x-circle',
            self::Spam      => 'heroicon-m-shield-exclamation',
        };
    }

    /** Statuses that count as an "open" lead still needing attention. */
    public static function openValues(): array
    {
        return [self::New->value, self::Contacted->value, self::Quoted->value];
    }
}
