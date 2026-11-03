<?php

namespace App\Support;

/**
 * Builds width-limited WebP copies (400, 800, 1200) and a square avatar.
 * Never upscales. Variants live under storage/app/public/variants.
 */
class ResponsiveImage
{
    public const WIDTHS = [400, 800, 1200];

    /**
     * @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool}
     */
    public static function fromStoragePath(?string $relativePath): array
    {
        if (! filled($relativePath)) {
            return self::empty();
        }

        $absolute = storage_path('app/public/'.$relativePath);

        return self::fromFile($absolute, asset('storage/'.$relativePath));
    }

    /**
     * @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool}
     */
    public static function fromPublicRelative(string $relativeToPublic): array
    {
        return self::fromFile(public_path($relativeToPublic), asset($relativeToPublic));
    }

    /**
     * @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool}
     */
    public static function fromFile(string $absolute, string $originalUrl): array
    {
        $info = is_file($absolute) ? @getimagesize($absolute) : false;

        if ($info === false) {
            return self::empty($originalUrl);
        }

        $width = (int) $info[0];
        $height = (int) $info[1];
        $srcset = [];
        $widths = array_values(array_filter(self::WIDTHS, fn (int $target) => $target <= $width));

        if ($width >= 400 && ! in_array($width, $widths, true)) {
            $widths[] = $width;
        }

        foreach ($widths as $target) {

            $variant = self::writeScaled($absolute, $info, $target, 'w'.$target);

            if ($variant) {
                $srcset[] = $variant.' '.$target.'w';
            }
        }

        return [
            'src' => $originalUrl,
            'srcset' => $srcset === [] ? null : implode(', ', $srcset),
            'width' => $width,
            'height' => $height,
            'original_width' => $width,
            'below_1200' => $width < 1200,
        ];
    }

    /**
     * Small square avatar plus the large source for the owner portrait.
     * The large source is never upscaled. below_1200 is true when the file
     * is narrower than 1200px, so a larger original still needs to be requested.
     *
     * @return array{avatar: array, large: array}
     */
    public static function portrait(?string $storageRelative, string $publicFallback = 'images/ruben-metcalfe.jpg'): array
    {
        if (filled($storageRelative) && is_file(storage_path('app/public/'.$storageRelative))) {
            $absolute = storage_path('app/public/'.$storageRelative);
            $url = asset('storage/'.$storageRelative);
            $large = self::fromStoragePath($storageRelative);
        } else {
            $absolute = public_path($publicFallback);
            $url = asset($publicFallback);
            $large = self::fromPublicRelative($publicFallback);
        }

        return [
            'avatar' => self::avatar($absolute, $url, 96),
            'large' => $large,
        ];
    }

    /**
     * Square cover crop, biased to the top, for small portraits.
     *
     * @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool}
     */
    public static function avatar(string $absolute, string $fallbackUrl, int $size = 96): array
    {
        $info = is_file($absolute) ? @getimagesize($absolute) : false;

        if ($info === false) {
            return self::empty($fallbackUrl);
        }

        $url = self::writeCover($absolute, $info, $size, 'avatar'.$size);

        if (! $url) {
            return [
                'src' => $fallbackUrl,
                'srcset' => null,
                'width' => (int) $info[0],
                'height' => (int) $info[1],
                'original_width' => (int) $info[0],
                'below_1200' => ((int) $info[0]) < 1200,
            ];
        }

        return [
            'src' => $url,
            'srcset' => null,
            'width' => $size,
            'height' => $size,
            'original_width' => (int) $info[0],
            'below_1200' => ((int) $info[0]) < 1200,
        ];
    }

    /**
     * @param  array{0: int, 1: int, mime?: string}  $info
     */
    protected static function writeScaled(string $absolute, array $info, int $targetWidth, string $label): ?string
    {
        $source = self::load($absolute, $info['mime'] ?? '');

        if (! $source) {
            return null;
        }

        $srcW = imagesx($source);
        $srcH = imagesy($source);
        $targetHeight = (int) max(1, round($srcH * ($targetWidth / $srcW)));
        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($canvas === false) {
            imagedestroy($source);

            return null;
        }

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $srcW, $srcH);
        $url = self::save($canvas, $absolute, $label);
        imagedestroy($canvas);
        imagedestroy($source);

        return $url;
    }

    /**
     * @param  array{0: int, 1: int, mime?: string}  $info
     */
    protected static function writeCover(string $absolute, array $info, int $size, string $label): ?string
    {
        $source = self::load($absolute, $info['mime'] ?? '');

        if (! $source) {
            return null;
        }

        $srcW = imagesx($source);
        $srcH = imagesy($source);
        $side = min($srcW, $srcH);
        $srcX = (int) max(0, floor(($srcW - $side) / 2));
        $canvas = imagecreatetruecolor($size, $size);

        if ($canvas === false) {
            imagedestroy($source);

            return null;
        }

        imagecopyresampled($canvas, $source, 0, 0, $srcX, 0, $size, $size, $side, $side);
        $url = self::save($canvas, $absolute, $label);
        imagedestroy($canvas);
        imagedestroy($source);

        return $url;
    }

    protected static function load(string $absolute, string $mime): \GdImage|false
    {
        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($absolute),
            'image/png' => @imagecreatefrompng($absolute),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolute) : false,
            'image/gif' => @imagecreatefromgif($absolute),
            default => false,
        };
    }

    protected static function save(\GdImage $canvas, string $sourceAbsolute, string $label): ?string
    {
        $directory = storage_path('app/public/variants');

        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            return null;
        }

        $stamp = filemtime($sourceAbsolute) ?: 0;
        $key = substr(sha1($sourceAbsolute.'|'.$stamp.'|'.$label), 0, 20);
        $filename = $key.'-'.$label.'.webp';
        $target = $directory.DIRECTORY_SEPARATOR.$filename;

        if (! is_file($target)) {
            if (! function_exists('imagewebp') || ! imagewebp($canvas, $target, 82)) {
                return null;
            }
        }

        return asset('storage/variants/'.$filename);
    }

    /**
     * @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool}
     */
    protected static function empty(?string $src = null): array
    {
        return [
            'src' => (string) $src,
            'srcset' => null,
            'width' => 0,
            'height' => 0,
            'original_width' => 0,
            'below_1200' => false,
        ];
    }
}
