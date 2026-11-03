<?php

namespace App\Support;

class MetaDescription
{
    public const EXPLICIT_MAX = 160;

    public const FALLBACK_MAX = 155;

    public static function from(?string $explicit, ?string $fallback = null): string
    {
        $explicit = self::clean($explicit);

        if ($explicit !== '') {
            return mb_strlen($explicit) <= self::EXPLICIT_MAX
                ? $explicit
                : self::trimToWord($explicit, self::FALLBACK_MAX);
        }

        return self::trimToWord(self::clean($fallback), self::FALLBACK_MAX);
    }

    /**
     * Last-line tidy for a description that is already chosen.
     * Values within the explicit max are kept. Longer text is cut on a
     * word boundary under 155 characters, with no ellipsis.
     */
    public static function finish(?string $text): string
    {
        $text = self::clean($text);

        if ($text === '' || mb_strlen($text) <= self::EXPLICIT_MAX) {
            return $text;
        }

        return self::trimToWord($text, self::FALLBACK_MAX);
    }

    public static function clean(?string $text): string
    {
        $text = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/#\S+/u', '', $text) ?? $text;
        $text = preg_replace('/\s{2,}/u', ' ', $text) ?? $text;

        return trim($text);
    }

    public static function trimToWord(string $text, int $limit = self::FALLBACK_MAX): string
    {
        $text = trim($text);

        if ($text === '' || mb_strlen($text) <= $limit) {
            return $text;
        }

        $slice = mb_substr($text, 0, $limit);
        $break = mb_strrpos($slice, ' ');

        if ($break === false) {
            return rtrim($slice);
        }

        return rtrim(mb_substr($text, 0, $break), " \t,;:-—");
    }
}
