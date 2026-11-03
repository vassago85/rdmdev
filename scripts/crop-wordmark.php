<?php

/**
 * Crop the header wordmark out of the full logo so the tagline
 * ("BUILDING & RENOVATION • rdmdev.co.za") can be hidden on small screens.
 */

$srcPath = __DIR__ . '/../public/images/rdmdev-logo-256.png';
$src = imagecreatefrompng($srcPath);

if (! $src) {
    fwrite(STDERR, "Failed to open {$srcPath}\n");
    exit(1);
}

$w = imagesx($src);
$h = imagesy($src);

$inkRows = [];
for ($y = 0; $y < $h; $y++) {
    $ink = 0;
    for ($x = 0; $x < $w; $x++) {
        $alpha = (imagecolorat($src, $x, $y) >> 24) & 0x7F;
        if ($alpha < 80) {
            $ink++;
        }
    }
    if ($ink > 8) {
        $inkRows[] = $y;
    }
}

if ($inkRows === []) {
    fwrite(STDERR, "No ink found\n");
    exit(1);
}

// Split content into bands separated by a clear gap (wordmark vs tagline).
$bands = [];
$start = $inkRows[0];
$prev = $inkRows[0];
foreach (array_slice($inkRows, 1) as $y) {
    if ($y - $prev > 4) {
        $bands[] = [$start, $prev];
        $start = $y;
    }
    $prev = $y;
}
$bands[] = [$start, $prev];

fwrite(STDOUT, "size {$w}x{$h}\n");
foreach ($bands as $i => [$a, $b]) {
    fwrite(STDOUT, "band {$i}: {$a}-{$b}\n");
}

// Wordmark is everything above the last band (the tagline).
$wordmarkBottom = count($bands) > 1 ? $bands[count($bands) - 2][1] : $bands[0][1];
$top = $bands[0][0];
$cropTop = max(0, $top - 4);
$cropBottom = min($h - 1, $wordmarkBottom + 6);
$cropH = $cropBottom - $cropTop + 1;

$left = $w;
$right = 0;
for ($y = $cropTop; $y <= $cropBottom; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $alpha = (imagecolorat($src, $x, $y) >> 24) & 0x7F;
        if ($alpha < 80) {
            $left = min($left, $x);
            $right = max($right, $x);
        }
    }
}
$cropLeft = max(0, $left - 4);
$cropRight = min($w - 1, $right + 4);
$cropW = $cropRight - $cropLeft + 1;

$dst = imagecreatetruecolor($cropW, $cropH);
imagealphablending($dst, false);
imagesavealpha($dst, true);
$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefilledrectangle($dst, 0, 0, $cropW, $cropH, $transparent);
imagecopy($dst, $src, 0, 0, $cropLeft, $cropTop, $cropW, $cropH);

$out = __DIR__ . '/../public/images/rdmdev-wordmark.png';
imagepng($dst, $out, 9);

if (function_exists('imagewebp')) {
    imagewebp($dst, __DIR__ . '/../public/images/rdmdev-wordmark.webp', 90);
}

fwrite(STDOUT, "wrote wordmark {$cropW}x{$cropH}\n");

imagedestroy($dst);
imagedestroy($src);
