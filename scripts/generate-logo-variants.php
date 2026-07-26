<?php

$srcPath = __DIR__ . '/../public/images/rdmdev-logo-960.png';
$src = imagecreatefrompng($srcPath);

if (! $src) {
    fwrite(STDERR, "Failed to open {$srcPath}\n");
    exit(1);
}

$sw = imagesx($src);
$sh = imagesy($src);

foreach ([128, 256] as $w) {
    $h = (int) round($sh * ($w / $sw));
    $dst = imagecreatetruecolor($w, $h);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $sw, $sh);

    $png = __DIR__ . "/../public/images/rdmdev-logo-{$w}.png";
    imagepng($dst, $png, 9);

    if (function_exists('imagewebp')) {
        imagewebp($dst, __DIR__ . "/../public/images/rdmdev-logo-{$w}.webp", 82);
    }

    imagedestroy($dst);
    echo "wrote {$w}x{$h}\n";
}

imagedestroy($src);
